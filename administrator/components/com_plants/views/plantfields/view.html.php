<?php

defined('_JEXEC') or exit();

class PlantsViewPlantfields extends JViewLegacy
{
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        $this->addToolbar();

        $this->loadHelper( 'plants' );
        plantsHelper::addSubmenu( 'plantfields' );
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Plantfields');
        JToolBarHelper::addNew('plantfield.add');
        JToolBarHelper::editList('plantfield.edit');
        JToolBarHelper::deleteList('', 'plantfields.delete');
    }
}