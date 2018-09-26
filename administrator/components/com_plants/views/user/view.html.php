<?php

defined('_JEXEC') or die;

class PlantsViewUser extends JViewLegacy {

	protected $form;
	protected $item;
	protected $message;

	public function display($tpl = null) {

		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->message = $this->get('Message');

		if($this->message)
    		JFactory::getApplication()->enqueueMessage($this->message, 'Delete Request Message: ');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolBarHelper::title(JText::_('Edit user profile') );
		JToolBarHelper::apply('user.apply');
		JToolBarHelper::save('user.save');
		JToolBarHelper::cancel('user.cancel');
	}
}