<?php

defined('_JEXEC') or die;

class PlantsViewPost extends JViewLegacy
{
    protected $post;

    public function display( $tpl = null )
    {
        $this->post = $this->get('Post');
		return parent::display( $tpl );
	}
}