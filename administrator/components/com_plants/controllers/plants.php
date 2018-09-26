<?php

defined('_JEXEC') or exit();

class PlantsControllerPlants extends JControllerAdmin
{
    public function getModel($name = 'Plant', $prefix = 'PlantsModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}