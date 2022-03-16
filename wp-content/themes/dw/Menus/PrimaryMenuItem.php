<?php

class PrimaryMenuItem
{
	public $url;
	public $label;
	public $title;
	public $subitems = [];

	protected $post;

	public function __construct($post)
	{
		$this->url = $post->url;
		$this->label = $post->title;
		$this->title = $post->attr_title;
		$this->post = $post;
	}

	public function hasSubItems()
	{
		return !empty($this->subitems); // renvoie true si [] pas vide
	}

	public function isSubItem()
	{
		return boolval($this->post->menu_item_parent); // si true ou false
	}

	public function getParentId()
	{
		return $this->post->menu_item_parent;
	}

	public function isParentFor(PrimaryMenuItem $instance)
	{
		return ($this->post->ID == $instance->getParentId()); // return true or false
	}

	public function addSubItem(PrimaryMenuItem $instance)
	{
		return ($this->subitems[] = $instance);
	}

	public function getBemClasses($base)
    {
        $icon = get_field('icon', $this->post);
        $modifiers = [];

        if($icon) {
            $modifiers[] = $icon;
        }

        /*if($this->post->object_id == get_queried_object_id()) {
            $modifiers[] = 'current';
        }*/
	    if($this->isCurrent()) {
		    $modifiers[] = 'current';
	    }

        /*if(in_array('menu-item-type-custom', $this->post->classes)) {
            $modifiers[] = 'custom';
        }*/
	    if($this->post->type === 'custom') {
		    $modifiers[] = 'url';
	    }

        $value = $base;

        foreach ($modifiers as $modifier) {
            $value .= ' ' . $base . '--' . $modifier;
        }

        return $value;
    }

	public function isCurrent()
	{
		return ($_SERVER['REQUEST_URI'] == parse_url($this->post->url, PHP_URL_PATH));
	}
}