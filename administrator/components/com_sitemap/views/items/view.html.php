<?php

defined('_JEXEC') or exit();

class SitemapViewItems extends JViewLegacy 
{
	protected $items;
	protected $pagination;

	public function display($tpl = null)
	{
	    $this->items = $this->get('Items');
	    $this->pagination = $this->get('Pagination');
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title('Sitemap');
		JToolBarHelper::addNew('items.generate', 'Generate');
		//JToolBarHelper::editList('sitemap.edit');
	}
}
