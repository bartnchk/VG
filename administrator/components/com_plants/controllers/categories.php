<?php

defined('_JEXEC') or die;

class PlantsControllerCategories extends JControllerAdmin
{
	public function getModel( $name = 'Category', $prefix = 'PlantsModel', $config = array() )
    {
		return parent::getModel( $name, $prefix, $config );
	}
}