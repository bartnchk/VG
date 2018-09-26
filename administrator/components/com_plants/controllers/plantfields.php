<?php

defined('_JEXEC') or exit();

class PlantsControllerPlantfields extends JControllerAdmin
{
    public function getModel($name = 'Plantfield', $prefix = 'PlantsModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}