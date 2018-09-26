<?php

defined('_JEXEC') or exit();

class PlantsTablePlant extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__z_plants_plants', 'id', $db);
    }
}