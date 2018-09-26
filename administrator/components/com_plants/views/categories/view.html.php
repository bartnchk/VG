<?php

defined('_JEXEC') or die;

class PlantsViewCategories extends JViewLegacy
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
		plantsHelper::addSubmenu( 'categories' );
		$this->sidebar = JHtmlSidebar::render();

		$this->state = $this->get('State');

        $this->sortDirection = $this->state->get('list.direction');
        $this->sortColumn = $this->state->get('list.ordering');

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title('Categories');
//		JToolBarHelper::addNew('category.add');
		JToolBarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::editList('category.edit');
		JToolBarHelper::deleteList('', 'categories.delete');
		JToolBarHelper::preferences('com_plants');

	}


}