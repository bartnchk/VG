<?php

defined('_JEXEC') or exit();

class PlantsViewPlants extends JViewLegacy
{
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        $state = $this->get('State');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');

        $this->addToolbar();

        $this->loadHelper( 'plants' );
        plantsHelper::addSubmenu( 'plants' );
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Plants');
        JToolBarHelper::editList('plant.edit');
        JToolBarHelper::deleteList('', 'plants.delete');
    }
}