<?php

require_once(__DIR__ . '/Menus/PrimaryMenuWalker.php');

// Désactiver l'éditeur "Gutenberg" de Wordpress
add_filter('use_block_editor_for_post', '__return_false');

// Activer les images sur les articles
add_theme_support('post-thumbnails');

// Enregistrer un (type de ressource) seul custom post-type pour "nos voyages"
register_post_type('trip', [
    'label' => 'Voyages', // plural
    'labels' => [ // interface admin
        'name' => 'Voyages',
        'singular_name' => 'Voyage',
    ],
    'description' => 'La ressource permettant de gérer les voyages qui ont été effectués',
    'public' => true, // accessible dans l'affichage et dans l'interface admin (formulaire de contact : false)
    'menu_position' => 5,
    'menu_icon' => 'dashicons-palmtree', // https://developer.wordpress.org/resource/dashicons/#share-alt
	'supports' => ['thumbnail', 'title', 'editor'], // préciser qu'on veut avoir une thumbnail pour chaque custom_post_type (ligne 7)
	'rewrite' => ['slug' => 'voyages'], // changer le slug de l'URL
]);

// Récupérer les trips via une requête WordPress pour ne pas polluer notre HTML
function dw_get_trips($postPerPage = 5) {
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

register_nav_menu('primary', 'Emplacement de la navigation principale de haut de page');
register_nav_menu('footer', 'Emplacement de la navigation principale de pied de page');



