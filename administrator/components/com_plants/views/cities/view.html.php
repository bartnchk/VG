<?php

defined('_JEXEC') or die;

class PlantsViewCities extends JViewLegacy {

	protected $items;
	protected $pagination;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

        $state = $this->get('State');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');

		$this->addToolbar();
		$this->loadHelper( 'plants' );
		plantsHelper::addSubmenu( 'Cities' );
		$this->sidebar = JHtmlSidebar::render();
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		parent::display($tpl);
	}

	protected function addToolbar()
	{
        JToolBarHelper::title( 'Cities' );
        JToolBarHelper::addNew('city.add');
        JToolBarHelper::editList('city.edit');
        JToolBarHelper::deleteList('', 'cities.delete');
        JToolBarHelper::preferences('com_plants');
    }
}