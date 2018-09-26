<?php

defined('_JEXEC') or die;

class PlantsModelType extends JModelAdmin
{

	public function getForm( $data = array(), $loadData = true ) {
		$form = $this->loadForm( $this->option . 'type', 'type', [ 'control'   => 'jform',
		                                                                   'load_data' => $loadData
		] );
		if ( empty( $form ) )
		{
			return false;
		}
		return $form;
	}

	public function getTable($type = 'Type', $prefix = 'PlantsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{

		$data = JFactory::getApplication()->getUserState('com_plants.edit.type.data', array());

		if(empty($data)){
			$data = $this->getItem();
		}
		return $data;
	}

	public function save( $data )
	{
		if(trim($data['alias'] == '')) {
			$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
		}
		if(parent::save($data)){
			return true;
		}
		return false;
	}

	public function delete( &$pks )
	{
		return parent::delete( $pks );
	}
}