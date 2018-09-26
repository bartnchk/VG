<?php

defined('_JEXEC') or die;

class PlantsModelSubscribers extends JModelList
{
	public function getListQuery()
    {
		$query = $this->_db->getQuery(true);

		$query->select('*');
		$query->from('#__z_plants_subscribers');

		$subscribers_search = $this->getState('filter.search');

		if($subscribers_search)
		{
			$query->where($this->_db->quoteName('email') .' LIKE '. $this->_db->quote($subscribers_search));
		}

		$this->_db->setQuery($query);

		return $query;
	}
}
