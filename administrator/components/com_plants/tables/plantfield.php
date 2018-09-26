<?php

defined('_JEXEC') or exit();

class PlantsTablePlantfield extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__z_plants_fields', 'id', $db);
    }
}