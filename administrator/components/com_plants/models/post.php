<?php

defined('_JEXEC') or die;

class PlantsModelPost extends JModelAdmin
{

	public function getForm( $data = array(), $loadData = true )
    {
		$form = $this->loadForm( $this->option . 'post', 'post', ['control' => 'jform', 'load_data' => $loadData] );
		if ( empty( $form ) )
		{
			return false;
		}
		return $form;
	}

	public function getTable($type = 'Post', $prefix = 'PlantsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

    protected function loadFormData()
    {
        $data = $this->getItem();
        return $data;
    }

    public function prepareTable($table)
    {
        if($table->top_plant)
            $this->_db->setQuery('UPDATE #__z_pants_plants SET top_plant = 0')->execute();
    }

    public function delete( &$pks )
	{
		return parent::delete( $pks );
	}
}