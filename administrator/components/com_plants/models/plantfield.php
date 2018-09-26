<?php

defined('_JEXEC') or exit();

class PlantsModelPlantfield extends JModelAdmin {

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_plants.plantfield',
            'plantfield',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form))
            return false;
        else
            return $form;
    }

    public function getTable($type = 'Plantfield', $prefix = 'PlantsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function delete (&$pks)
    {
        parent::delete($pks);
    }

    protected function loadFormData()
    {
        $data = $this->getItem();
        return $data;
    }
}