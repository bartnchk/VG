<?php

defined('_JEXEC') or die;

class PlantsControllerPosts extends JControllerAdmin
{
	public function getModel( $name = 'Post', $prefix = 'PlantsModel', $config = array() )
    {
		return parent::getModel( $name, $prefix, $config );
	}
}