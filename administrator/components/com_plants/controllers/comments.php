<?php

defined('_JEXEC') or die;

class PlantsControllerComments extends JControllerAdmin
{
	public function getModel( $name = 'Comment', $prefix = 'PlantsModel', $config = array() ) {
		return parent::getModel( $name, $prefix, $config );
	}
}