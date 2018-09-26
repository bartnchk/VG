<?php

defined('_JEXEC') or die;

class PlantsViewPost extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null)
    {
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
    {
		JToolBarHelper::title('Edit Top');
		JToolBarHelper::apply('post.apply');
		JToolBarHelper::save('post.save');
		JToolBarHelper::cancel('post.cancel');
	}
}