<?php

defined('_JEXEC') or exit();

class PlantsTablePost extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__z_plants_posts', 'id', $db);
    }
}