<?php

defined('_JEXEC') or exit();

class PlantsModelPlants extends JModelList
{
    public function __construct($config = array())
    {

        $config['filter_fields'] = array(
            'p.sort_name',
            't.title',
            'u.id',
            'p.published',
            'p.id'
        );

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('p.*, u.name, t.title as type');
        $query->from('#__z_plants_plants p');
        $query->leftJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->leftJoin('#__users u ON u.id = up.user_id');
        $query->leftJoin('#__z_plants_plant_types t ON t.id = p.plant_type_id');

        $query->order($this->_db->escape($this->getState('list.ordering', 'id')).' '.
            $this->_db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('p.sort_name', 'ASC');
    }
}