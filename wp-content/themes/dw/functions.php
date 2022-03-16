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
		'name'          => 'Voyages',
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


register_post_type( 'message', [
	'label'         => 'Messages de contact',
	'labels'        => [
		'name'          => 'Messages de contact',
		'singular_name' => 'Message de contact',
	],
	'description'   => "Les messages envoyés par les utilisateurs via le formulaire de contact",
	'public'        => false, //accessible dans l'interface admin (formulaire de contact: false)
	'show_ui'       => true,
	'menu_position' => 10,
	'menu_icon'     => 'dashicons-buddicons-pm',
	'capabilities'  => [
		'create_posts' => false, //enlever le bouton add new
	],
	'map_meta_cap'  => true,

	// TODO hook : afficher du code html avec les infos au lieu d'un wysiwyg
] );


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

	if ( ! ( $locations[ $location ] ?? null ) ) { // null coalescing operator : if key=primary|footer : primary|footer and not null else if !key : null / === array_key_exist($location, $locations)
		return $links;
	}

	$menu = $locations[ $location ];

	// 2. Récupérer tous les éléments (liens) du menu en question
	$posts = wp_get_nav_menu_items( $menu );

	// 3. Traiter chaque élément du menu pour le transformer en objet
	foreach ( $posts as $post ) {
		// Créer une instance d'un objet personnalisé à partir de $post
		$link = new PrimaryMenuItem( $post );

		// Ajouter cette instance soit ) $links (si niveau 0) ou soit en tant que sous-élément d'un link déjà existant
		if ( ! ( $link->isSubItem() ) ) {
			$links[] = $link;
			continue; // = return d'une boucle
		}
		// Ajouter l'instance comme enfant d'un $links existant,
		foreach ( $links as $existing ) {
			if ( ! $existing->isParentFor( $link ) ) {
				continue;
			}
			$existing->addSubItem( $link );
		}
	}

	// 4. Retourner les éléments de niveau 0
	return $links;
}

// Enregistrer le traitement du formulaire de contact personnalisé

add_action( 'admin_post_submit_contact_form', 'dw_handle_submit_contact_form' );

function dw_handle_submit_contact_form() {

	if ( ! dw_verify_contact_form_nonce() ) {
		//TODO : afficher un message d'erreur : "unauthorized access"
		return;
	}

	$data = dw_sanitize_contact_form_data();

	if ( $errors = dw_validate_contact_form_data( $data ) ) {
		//TODO : toutes les erreurs de validation
		return;
	}

	// Stocker en DB
	$id = wp_insert_post( [
		'post_type'    => 'message',
		'post_title'   => 'Message de ' . $data['firstname'] . ' ' . $data['lastname'],
		'post_content' => $data['message'],
		'post_status'  => 'publish'
	] );
	// Envoyer un mail
	$content = 'Bonjour, un nouveau message de contact à été envoyé. <br>';
	$content .= 'Pour le visualiser : ' . get_edit_post_link( $id );
	wp_mail( 'thienan_vo@live.be', 'Nouveau message', $content );
	// TODO : configurer serveur mail (ex: mailgun - plugin)


}

function dw_verify_contact_form_nonce() {
	$nonce = $_POST['_wpnonce'];

	return wp_verify_nonce( $nonce, 'nonce_check_contact_form' );
}

function dw_sanitize_contact_form_data() {
	return [
		'firstName' => sanitize_text_field( $_POST['firstName'] ?? null ),
		'lastName'  => sanitize_text_field( $_POST['lastName'] ?? null ),
		'email'     => sanitize_email( $_POST['email'] ?? null ),
		'phone'     => sanitize_text_field( $_POST['phone'] ?? null ), //TODO
		'message'   => sanitize_text_field( $_POST['message'] ?? null ),
		'rules'     => $_POST['rules'] ?? null,
	];
}

function dw_validate_contact_form_data( $data ) {

	$errors = [];

	//TODO : validation
	/*if ( $firstName && $lastName && $email && $message ) {
	if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
	if ( ( $_POST['rules'] ?? null ) === '1' ) {*/

	$required = [ 'firstname', 'lastname', 'email', 'message' ];
	$email    = [ 'email' ];
	$accepted = [ 'rules' ];

	foreach ( $data as $key => $value ) {
		if ( in_array( $key, $required ) && ! $value ) {
			$errors[ $key ] = 'required';
			continue;
		}

		if ( in_array( $key, $email ) && ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			$errors[ $key ] = 'email';
			continue;
		}

		if ( in_array( $key, $accepted ) && $value == ! '1' ) {
			$errors[ $key ] = 'accepted';
			continue;
		}
	}

	return $errors ? $errors : false;
}
