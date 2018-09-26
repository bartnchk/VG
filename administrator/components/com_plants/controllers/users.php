<?php

defined('_JEXEC') or die;

class PlantsControllerUsers extends JControllerAdmin
{
	public function getModel( $name = 'User', $prefix = 'PlantsModel', $config = array() )
	{
		return parent::getModel( $name, $prefix, $config );
	}

	public function block()
	{

		$ids = $this->input->get('cid', array(), 'array');
		$model = $this->getModel();
		$model->block($ids);

		if($model)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_PLANTS_USERS_ACTIVATE'));
			$this->setRedirect('index.php?option=com_plants&view=users');
		}
	}

	public function unblock()
	{
		$ids = $this->input->get('cid', array(), 'array');
		$model = $this->getModel();
		$model->unblock($ids);

		if($model)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_PLANTS_USERS_DEACTIVATE'));
			$this->setRedirect('index.php?option=com_plants&view=users');
		}
	}

}