<?php

defined('_JEXEC') or die;

class PlantsModelUsers extends JModelList {

    public function __construct($config = array())
    {
        $config['filter_fields'] = array(
            'id',
            'username',
            'block',
            'email'
        );

        parent::__construct($config);
    }

	public function getListQuery()
    {
		$query = $this->_db->getQuery( true );

		$query->select( 'u.*, ju.block, concat(u.first_name," ",u.last_name) as name, ur.user_id as user_id_request, ur.message' );
		$query->from( '#__z_users u' );
		$query->innerJoin( '#__users ju ON u.juser_id = ju.id' );
		$query->leftJoin('#__z_user_requests ur ON u.juser_id = ur.user_id');

		$query->order($this->_db->escape($this->getState('list.ordering', 'id')).' '.
            $this->_db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

    protected function populateState($ordering = null, $direction = null)
    {
        parent::populateState('id', 'ASC');
    }
}