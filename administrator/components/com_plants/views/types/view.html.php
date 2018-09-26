<?php

defined('_JEXEC') or die;

class PlantsViewTypes extends JViewLegacy
{
	protected $items;
	protected $pagination;

	protected $state;
	protected $sortDirection;
	protected $sortColumn;
	protected $saveOrder;

	public function display($tpl = null)
	{

		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->addToolbar();

		$this->loadHelper( 'plants' );
		plantsHelper::addSubmenu( 'types' );
		$this->sidebar = JHtmlSidebar::render();

		$this->state = $this->get('State');

		$this->sortDirection = $this->state->get('list.direction');
		$this->sortColumn = $this->state->get('list.ordering');

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title( JText::_('Plant types') );
		JToolBarHelper::addNew('type.add');
		JToolBarHelper::publish('types.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('types.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::editList('type.edit');
		JToolBarHelper::deleteList('', 'types.delete');
	}


}