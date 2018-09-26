<?php

defined('_JEXEC') or exit();

class PlantsModelProducts extends JModelList {

    protected function getListQuery() {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select('p.*')
            ->from('#__z_shop_products AS p');

        $query
            ->select('c.title AS category_title')
            ->from('#__z_shop_categories AS c')
            ->where('p.id_category = c.id');

        $query->order($db->escape($this->getState('list.ordering', 'id')).' '.
            $db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    public function __construct($config = array()) {

        $config['filter_fields'] = array(
            'title',
            'id_category',
            'ordering',
            'published'
        );
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {

        parent::populateState('id', 'ASC');
    }

}