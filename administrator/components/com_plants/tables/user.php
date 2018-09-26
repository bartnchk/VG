<?php

defined('_JEXEC') or exit();

class PlantsTableUser extends JTable
{
	public function __construct( $db )
	{
		parent::__construct( '#__z_users', 'id', $db );
	}
}