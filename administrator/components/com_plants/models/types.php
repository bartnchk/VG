<?php


defined('_JEXEC') or die;

class PlantsModelTypes extends JModelList
{

	public function __construct( array $config = array() )
	{
		//add fields for sort list

		$config['filter_fields'] = array(
			'id',
			'title',
			'category_id',
			'state'
		);

		parent::__construct( $config );
	}

	public function getListQuery()
	{

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('t.* , c.title AS category_name');
		$query->from('#__z_plants_plant_types t');
		$query->innerJoin('#__z_plants_plant_category c ON t.category_id = c.id');

		$orderCol = $db->escape($this->getState('list.ordering', 'id'));
		$orderDir = $db->escape($this->getState('list.direction', 'desc'));

		//SQL ORDER BY id DESC
		$query->order($orderCol. ' ' .$orderDir);

		return $query;
	}


}
