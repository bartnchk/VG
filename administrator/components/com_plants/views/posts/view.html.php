<?php

defined('_JEXEC') or exit();

class PlantsViewPosts extends JViewLegacy
{
    protected $items;
    protected $pagination;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->addToolbar();

        $this->loadHelper( 'plants' );
        plantsHelper::addSubmenu( 'posts' );
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Top');
        JToolBarHelper::addNew('post.add');
        JToolBarHelper::editList('post.edit');
    }
}