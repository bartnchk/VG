<?php

defined('_JEXEC') or die;

class PlantsViewComments extends JViewLegacy
{
	protected $items;
	protected $pagination;

	protected $state;
	protected $sortDirection;
	protected $sortColumn;
	protected $saveOrder;

	public function display( $tpl = null )
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
//        $this->state = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		$this->addToolbar();

		$this->loadHelper( 'plants' );
		plantsHelper::addSubmenu( 'comments' );
		$this->sidebar = JHtmlSidebar::render();


//		print_r($this->state);

//		$this->sortDirection = $this->state->get('list.direction');
//		$this->sortColumn =    $this->state->get('list.ordering');

		return parent::display( $tpl );
	}

	protected function addToolbar()
	{
		JToolBarHelper::title( JText::_('Comments') );
		JToolBarHelper::publish('comments.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('comments.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::deleteList('', 'comments.delete');
		JToolBarHelper::preferences('com_plants');
	}
}