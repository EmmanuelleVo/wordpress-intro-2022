<?php

//require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');
require_once( __DIR__ . '/Menus/PrimaryMenuItem.php' );

// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter( 'use_block_editor_for_post', '__return_false' );

// Activer les images sur les articles
add_theme_support( 'post-thumbnails' );

// Enregistrer un (type de ressource) seul custom post-type pour "nos voyages"
register_post_type( 'trip', [
	'label'         => 'Voyages',
	// plural
	'labels'        => [ // interface admin
		'name' => 'Voyages',
		'singular_name' => 'Voyage',
	],
	'description'   => 'La ressource permettant de gérer les voyages qui ont été effectués',
	'public'        => true,
	// accessible dans l'affichage et dans l'interface admin (formulaire de contact : false)
	'menu_position' => 5,
	'menu_icon'     => 'dashicons-palmtree',
	// https://developer.wordpress.org/resource/dashicons/#share-alt
	'supports'      => [ 'thumbnail', 'title', 'editor' ],
	// préciser qu'on veut avoir une thumbnail pour chaque custom_post_type (ligne 7)
	'rewrite'       => [ 'slug' => 'voyages' ],
	// changer le slug de l'URL
] );


register_post_type('message', [
	'label' => 'Messages de contact',
	'labels' => [
		'name' => 'Messages de contact',
		'singular_name' => 'Message de contact',
	],
	'description' => "Les messages envoyés par les utilisateurs via le formulaire de contact",
	'public' => false, //accessible dans l'interface admin (formulaire de contact: false)
	'show_ui' => true,
	'menu_position' => 10,
	'menu_icon' => 'dashicons-buddicons-pm',
	'capabilities' => [
		'create_posts' => false, //enlever le bouton add new
	],
	'map_meta_cap' => true,

	// TODO hook : afficher du code html avec les infos au lieu d'un wysiwyg
]);




// Récupérer les trips via une requête WordPress pour ne pas polluer notre HTML
function dw_get_trips( $postPerPage = 5 ) {
	// On instancie l'objet WP_QUERY
	$trips = new WP_Query( [
		'post_type'      => 'trip',
		'posts_per_page' => $postPerPage,
		'orderby'        => 'date',
		'order'          => 'desc',
	] );

	// On retourne l'objet WP_QUERY
	return $trips;
}

// Enregistrer les menus de navigation

register_nav_menu( 'primary', 'Emplacement de la navigation principale de haut de page' );
register_nav_menu( 'footer', 'Emplacement de la navigation principale de pied de page' );

//

/* *****
 * Définition de la fonction retournant un menu de navigation sous forme d'un tabeau de liens de niveau 0
 * *****/
function dw_get_menu_items( $location ) {
	$links = [];
	// 1. Récupérer le menu qui correspond à l'emplacement souhaité
	$locations = get_nav_menu_locations(); // retourne [tous les menus de nav qui ont été enregistrés - ici : primary et footer]
	if ( $locations[ $location ] ?? null ) { // null coalescing operator : if key=primary|footer : primary|footer and not null else if !key : null / === array_key_exist($location, $locations)
		$menu = $locations[ $location ];


		// 2. Récupérer tous les éléments (liens) du menu en question
		$posts = wp_get_nav_menu_items( $menu );

		// 3. Traiter chaque élément du menu pour le transformer en objet
		foreach ( $posts as $post ) {
			// Créer une instance d'un objet personnalisé à partir de $post
			$link = new PrimaryMenuItem( $post );

			// Ajouter cette instance soit ) $links (si niveau 0) ou soit en tant que sous-élément d'un link déjà existant
			if ( $link->isSubItem() ) {
				// Ajouter l'instance comme enfant d'un $links existant,
				foreach ( $links as $existing ) {
					if ( $existing->isParentFor( $link ) ) {
						$existing->addSubItem( $link );
					}
				}
			} else {
				$links[] = $link;
			}


		}

		/*$links = array_map(function($result) {
			// Récupérer l'objet de la page courante
			global $post;

			$link = new \stdClass();

			$link->url = $result->url;
			$link->label = $result->title;
			$link->modifiers = [];

			// Est-ce que le lien représente la page courante ?
			if(intval($result->object_id) === intval($post->ID)) {
				$link->modifiers[] = 'current';
			}

			// Est-ce que le lien possède une icone (ACF) à afficher ?
			if($icon = get_field('icon', $result->ID)) {
				$link->modifiers[] = $icon;
			}


			return $link;
		}, $links);
		*/

	}

	// 4. Retourner les éléments de niveau 0
	return $links;
}

// Enregistrer le traitement du formulaire de contact personnalisé

add_action( 'admin_post_submit_contact_form', 'dw_handle_submit_contact_form' );

function dw_handle_submit_contact_form()
{
	$nonce = $_POST['_wpnonce'];

	if(wp_verify_nonce($nonce, 'nonce_check_contact_form')) {
		$firstName = sanitize_text_field($_POST['firstName']);
		$lastName = sanitize_text_field($_POST['lastName']);
		$email = sanitize_email($_POST['email']);
		$phone = sanitize_text_field($_POST['phone']);
		$message = sanitize_text_field($_POST['message']);

		if ($firstName && $lastName && $email && $message) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				if (($_POST['rules'] ?? null) === '1') {
					// Stocker en DB
					$id = wp_insert_post([
						'post_type' => 'message',
						'post_title' => 'Message de ' . $firstName . ' ' . $lastName,
						'post_content' => $message,
						'post_status' => 'publish'
					]);
					// Envoyer un mail
					$content = 'Bonjour, un nouveau message de contact à été envoyé. </br>';
					$content .= 'Pour le visualiser : ' . get_edit_post_link($id);
					wp_mail('thienan_vo@live.be', 'Nouveau message', $content);
					// TODO : configurer serveur mail (ex: mailgun)
				} else {
					// TODO : erreur de validation de type "conditions générales"
				}
			} else {
				// TODO : erreur de validation de type "email incorrect"
			}
		} else {
			// TODO : erreur de validation de type "required"
		}


	} else {
		//TODO : afficher un message d'erreur : "unauthorized access"
	}


}
