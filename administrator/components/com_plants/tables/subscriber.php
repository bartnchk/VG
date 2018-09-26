<?php

defined('_JEXEC') or exit();

class PlantsTableSubscriber extends JTable
{
	public function __construct( $db )
	{
		parent::__construct( '#__z_plants_subscribers', 'id', $db );
	}
}