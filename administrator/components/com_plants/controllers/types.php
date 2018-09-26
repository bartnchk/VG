<?php

defined('_JEXEC') or die;

class PlantsControllerTypes extends JControllerAdmin
{

	public function getModel( $name = 'Type', $prefix = 'PlantsModel', $config = array() ) {
		return parent::getModel( $name, $prefix, $config );
	}


}