<?php

//require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');
require_once( __DIR__ . '/Menus/PrimaryMenuItem.php' );
require_once (__DIR__ . '/Forms/BaseFormController.php');
require_once (__DIR__ . '/Forms/ContactFormController.php');
require_once (__DIR__ . '/Forms/Sanitizers/BaseSanitizer.php');
require_once (__DIR__ . '/Forms/Sanitizers/TextSanitizer.php');
require_once (__DIR__ . '/Forms/Sanitizers/EmailSanitizer.php');
require_once (__DIR__ . '/Forms/Validators/BaseValidator.php');
require_once (__DIR__ . '/Forms/Validators/RequiredValidator.php');
require_once (__DIR__ . '/Forms/Validators/EmailValidator.php');
require_once (__DIR__ . '/Forms/Validators/AcceptedValidator.php');
require_once (__DIR__ . '/CustomSearchQuery.php');

// Lancer la session PHP
add_action('init', 'dw_boot_theme', 1);

function dw_boot_theme() {

	load_theme_textdomain('dw', __DIR__ . '/locales');

	if ( ! session_id() ) {
		session_start();
	}
}

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
	'has_archive' => true,

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


// Enregistrer une taxonomie (façon de classifier es posts pour les pays où des voyages ont eu lieu)
register_taxonomy('country', ['trip'], [
	'labels' => [
		'name' => 'Pays',
		'singular-name' => 'Pays',
	],
	'description' => 'Pays visités et exploités dans nos récits de voyages',
	 'public' => true,
	'hierarchical' => true,
]);

// Récupérer les trips via une requête WordPress pour ne pas polluer notre HTML
function dw_get_trips( $postPerPage = 5, $search = null ) {
	// On instancie l'objet WP_QUERY
	$trips = new DW_CustomSearchQuery( [
		'post_type'      => 'trip',
		'posts_per_page' => $postPerPage,
		'orderby'        => 'date',
		'order'          => 'desc',
		's'              => strlen($search) ? $search : null,
		// recherche plus précis ou exact
	] );

	if($search) {
		// TODO : ajouter la recherche à la query
		$trips->set('s', $search);
	}

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

	$form = new ContactFormController($_POST);

}

function dw_get_contact_field_value($field) {
	if (!isset($_SESSION['feedback_contact_form'])) {
		return '';
	}
	return $_SESSION['feedback_contact_form']['data'][$field] ?? '';
}

function dw_get_contact_field_error($field) {

	if (!isset($_SESSION['feedback_contact_form'])) {
		return '';
	}

	if (!($_SESSION['feedback_contact_form']['errors'][$field] ?? null)) {
		return '';
	}

	return '<p class="form__error">Problème : '. $_SESSION['feedback_contact_form']['errors'][$field] . '</p>';
}

/*
 * Utilitaire pour charger un fichier compilé par mix avec cache bursting
 */

function dw_mix($path) {
	// $path = 'js/script.js';
	// return get_stylesheet_directory_uri() . '/public/' . $path;

	$path = '/' . ltrim($path, '/');

	// Check si fichier demandé existe

	if(! realpath(__DIR__ . '/public' . $path)) {
		return;
	}

	// Check si fichier mix-manifeste existe, sinon retourner le fichier sans cache bursting

	if(! ($manifest = realpath(__DIR__ . '/public/mix-manifest.json'))) {
		return get_stylesheet_directory_uri() . '/public' . $path;
	}

	// Ouvrir le fichier mix-manifeste et lire le JSON

	$manifest = json_decode(file_get_contents($manifest), true);

	// Check si fichier demandé est présent dans le mix-manifeste, sinon retourner le fichier sans cache bursting

	if(!array_key_exists($path, $manifest)) {
		return get_stylesheet_directory_uri() . '/public' . $path;
	}

	// C'est OK, on génère l'URL vers la ressource sur base du nom de fichier avec cache bursting

	return get_stylesheet_directory_uri() . '/public' . $manifest[$path];
}


/*
 * On va se plugger dans l'exécution de la requête de recherche pour la contraindre à chercher dans les articles uniquement
 */

function dw_configure_search_query($query) {

	if($query->is_search && !is_admin() && !is_a($query, DW_CustomSearchQuery::class)) {
		$query->set('post_type', ['post', /*'trip'*/]);
	}

	// Système de filtre custom (sans passer par la méthode WP)
	/*if(is_archive() && isset($_GET['filter-country'])) {
		$query->set('tax_query', [
			['taxonomy' => 'country', 'field' => 'slug', 'terms' => explode(',', $_GET['filter-country'])]
		]);
	}*/

	return $query;
}

add_filter('pre_get_posts', 'dw_configure_search_query');

/*
 * Fonction permettant d'inclure des composants et d'y injecter des variables locales (scope de l'appel de la fonction)
 */

function dw_include(string $partial, array $variables = []) {

	extract($variables); // $modifier = 'search'

	include(__DIR__ . '/partials/' . $partial . '.php');
}






