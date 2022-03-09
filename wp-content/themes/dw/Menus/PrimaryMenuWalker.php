<?php

class PrimaryMenuWalker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth=0, $args=null, $id=0) // & : référence à la variable qui a été fournie, modifier $output du scope précédent
	{
		// $args : objet du menu
		// $item : item du menu

		$icon = get_field('icon', $item);
		$modifiers = [];

		if($item->current) {
			$modifiers[] = 'current';
		}

		if($item->type === 'custom') {
			$modifiers[] = 'url';
		}
		var_dump($modifiers);

		if($icon) {
			$modifiers[] = $icon;
		}

		$output .= '<li class="' .
		           $this->generateBemClasses('nav__item', $modifiers) .
		           '"><a class="nav__link"
		            href="' .$item->url .'"'
		           . ($item->attr_title?'title="' . $item->attr_title . '"': '')
		           . '>' . $item->title .'</a>';
	}

	function end_el(&$output, $item, $depth=0, $args=null)
	{
		$output .= '</li>';
	}

	function generateBemClasses(string $base, array $modifiers = [])
	{
		// Objectif : "nav__item nav__item--current nav__item--url"
		$value = $base;

		foreach ($modifiers as $modifier) {
			$value .= ' ' . $base . '--' .$modifier;
		}

		return $value;

	}
}