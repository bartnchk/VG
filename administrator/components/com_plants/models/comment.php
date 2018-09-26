<?php

defined('_JEXEC') or die;

class PlantsModelComment extends JModelAdmin
{
	public function getForm( $data = array(), $loadData = true ) {
		// TODO: Implement getForm() method.
	}

	public function getTable($type = 'Comment', $prefix = 'PlantsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function delete( &$pks )
	{
		return parent::delete( $pks );
	}



}