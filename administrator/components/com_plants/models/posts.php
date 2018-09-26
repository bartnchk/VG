<?php

defined('_JEXEC') or exit();

class PlantsModelPosts extends JModelList
{
    protected function getListQuery()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_posts');

        return $query;
    }
}