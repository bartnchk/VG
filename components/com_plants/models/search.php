<?php

defined('_JEXEC') or die;

class PlantsModelSearch extends JModelList
{
    public function getListQuery()
    {
        $input = JFactory::getApplication()->input;
        $search_query = $input->get('search_query', '', 'String');

        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_plants');
        $query->where( 'sort_name LIKE ' . $this->_db->quote('%' . $search_query . '%') );
        $query->orWhere( 'barcode LIKE ' . $this->_db->quote('%' . $search_query . '%') );
        $query->orWhere( 'manufactured LIKE ' . $this->_db->quote('%' . $search_query . '%') );
        $query->andWhere('published = 1');

        return $query;
    }
}