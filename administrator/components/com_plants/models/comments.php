<?php


defined('_JEXEC') or die;

class PlantsModelComments extends JModelList {

	public function __construct( array $config = array() )
	{
		$config['filter_fields'] = array(
			'c.created_at',
			'c.user_id',
			'c.state',
			'pl.sort_name'
		);

		parent::__construct( $config );
	}


	protected function getListQuery()
	{
        $orderCol  = $this->state->get('list.ordering', 'a.name');
        $orderDirn = $this->state->get('list.direction', 'ASC');



	    $query = $this->_db->getQuery(true);

		$query->select('c.id, c.user_id, c.plant_id, c.comment, c.created_at, c.state, ph.src , pl.sort_name, us.first_name, us.last_name, us.email');
		$query->from('#__z_plants_comments AS c');
		$query->innerJoin('#__z_plants_plants AS pl ON pl.id = c.plant_id');
		$query->innerJoin('#__z_users AS us ON us.juser_id = c.user_id');
		$query->leftJoin('#__z_plants_plant_photos AS ph ON ph.plant_id = pl.id');
        $query->group('c.id');

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));

//        echo $query;
//        echo '------';
//        echo 'col ' . $orderCol;
//        echo ' dir ' . $orderDirn;

		return $query;
	}

    protected function populateState($ordering = 'user_id', $direction = 'DESC')
    {

        parent::populateState($ordering, $direction);
    }

}
