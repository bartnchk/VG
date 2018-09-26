<?php


defined('_JEXEC') or die;

class PlantsModelCategories extends JModelList
{

	public function __construct( array $config = array() )
	{
		//add fields for sort list
		$config['filter_fields'] = array(
			'id',
			'title',
			'state'
		);

		parent::__construct( $config );
	}

	public function getListQuery()
	{

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__z_plants_plant_category');

		$orderCol = $db->escape($this->getState('list.ordering', 'id'));
		$orderDir = $db->escape($this->getState('list.direction', 'desc'));

		//SQL ORDER BY id DESC
		$query->order($orderCol. ' ' .$orderDir);

		return $query;
	}


}
