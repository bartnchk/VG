<?php

defined('_JEXEC') or exit();

class PlantsControllerSubscribers extends JControllerAdmin
{
	public function getModel($name = 'Subscriber', $prefix = 'PlantsModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}

}