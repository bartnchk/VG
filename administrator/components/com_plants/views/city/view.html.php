<?php

defined('_JEXEC') or die;

class PlantsViewCity extends JViewLegacy {

	protected $form;
	protected $item;

	public function display($tpl = null) {

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolBarHelper::title(JText::_('Edit city') );
		JToolBarHelper::apply('city.apply');
		JToolBarHelper::save('city.save');
		JToolBarHelper::cancel('city.cancel');
	}
}