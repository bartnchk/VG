<?php

defined('_JEXEC') or die;

class PlantsViewUsers extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;
	protected $sortDirection;
	protected $sortColumn;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->addToolbar();

		$this->loadHelper( 'plants' );
		plantsHelper::addSubmenu( 'users' );

		$this->sidebar = JHtmlSidebar::render();
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

        $state = $this->get('State');
        $this->sortDirection = $state->get('list.direction');
        $this->sortColumn = $state->get('list.ordering');

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title('Users');
		JToolbarHelper::publish('users.block');
		JToolbarHelper::unpublish('users.unblock');
		JToolBarHelper::editList('user.edit');
		JToolBarHelper::deleteList('Are you sure?', 'users.delete');
	}
}