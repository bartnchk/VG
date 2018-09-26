<?php

defined('_JEXEC') or exit();

class PlantsModelPlantfields extends JModelList {

    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('f.*, t.title AS plant_type');
        $query->from('#__z_plants_fields f');
        $query->innerJoin('#__z_plants_plant_types t ON t.id = f.plant_type_id');

        return $query;
    }
}