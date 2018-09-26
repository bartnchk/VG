<?php

defined('_JEXEC') or die;

class PlantsViewType extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) {

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolBarHelper::title(JText::_('Edit type') );
		JToolBarHelper::apply('type.apply');
		JToolBarHelper::save('type.save');
		JToolBarHelper::cancel('type.cancel');
	}

}