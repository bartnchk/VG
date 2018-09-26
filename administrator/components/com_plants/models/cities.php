<?php

defined('_JEXEC') or exit();

class PlantsModelCities extends JModelList
{
    public function __construct($config = array()) {

        $config['filter_fields'] = array(
            'id',
            'name_en'
        );

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__sxgeo_cities');

        $query->order($this->_db->escape($this->getState('list.ordering', 'id')).' '.
            $this->_db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('name_en', 'ASC');
    }


}