<?php

defined('_JEXEC') or exit();

class PlantsModelSubscriber extends JModelAdmin {

    public function getForm($data = array(), $loadData = true)
    {
        // TODO: Implement getForm() method.
    }

    public function getTable($type = 'Subscriber', $prefix = 'PlantsTable', $config = array())
    {
		return JTable::getInstance($type, $prefix, $config);
	}

    public function delete(&$pks)
    {
        parent::delete($pks);
    }
}