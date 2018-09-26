<?php

defined('_JEXEC') or exit();

class PlantsViewPlant extends JViewLegacy {

    protected $form;
    protected $item;
    protected $photos;

    public function display($tpl = null)
    {
        $this->form   = $this->get('Form');
        $this->item   = $this->get('Item');

        if($this->item->id)
            $this->photos = $this->get('PlantPhotos');

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Plants');
        JToolBarHelper::apply('plant.apply');
        JToolBarHelper::save('plant.save');
        JToolBarHelper::cancel('plant.cancel');
    }
}