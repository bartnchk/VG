<?php

defined('_JEXEC') or exit();

class PlantsControllerCities extends JControllerAdmin
{
    public function getModel( $name = 'City', $prefix = 'PlantsModel', $config = array() )
    {
        return parent::getModel( $name, $prefix, $config );
    }
}