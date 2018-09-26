<?php

defined('_JEXEC') or exit();

class PlantsTableComment extends JTable
{

	public function __construct( $db ) {
		parent::__construct( '#__z_plants_comments', 'id', $db );
	}

	public function publish( $pks = null, $state = 1, $userId = 0 )
	{
		if(empty($pks))
		{
			throw new RuntimeException('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED');
		}
		foreach ($pks as $pk)
		{
			if(!$this->load($pk))
			{
				throw new RuntimeException('COM_POSTS_TABLE_ERROR_TYPE');
			}
			$this->state = $state;

			if(!$this->store())
			{
				throw new RuntimeException('COM_POSTS_TABLE_ERROR_TYPE_STORE');
			}
		}
		return true;
	}
}