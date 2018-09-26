<?php

defined('_JEXEC') or exit();

class PlantsViewPlantfield extends JViewLegacy {

    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Plantfields');
        JToolBarHelper::apply('plantfield.apply');
        JToolBarHelper::save('plantfield.save');
        JToolBarHelper::cancel('plantfield.cancel');
    }
}