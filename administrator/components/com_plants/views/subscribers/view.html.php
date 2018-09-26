<?php defined('_JEXEC') or die;

class PlantsViewSubscribers extends JViewLegacy {

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->addToolbar();

		$this->loadHelper( 'plants' );
		plantsHelper::addSubmenu( 'subscribers' );

		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title('Subscribers');
        JToolBarHelper::deleteList('', 'subscribers.delete');
        JToolBarHelper::preferences('com_plants');
	}
}