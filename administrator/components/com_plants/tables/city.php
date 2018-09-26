<?php

defined('_JEXEC') or exit();

class PlantsTableCity extends JTable
{
	public function __construct( $db )
	{
		parent::__construct( '#__sxgeo_cities', 'id', $db );
	}
}