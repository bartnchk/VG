<?php

defined('_JEXEC') or die;


class PlantsModelSubscribers extends JModelAdmin {

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm(
			'com_plants.subscribers',
			'subscribers',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);
		return $form;
	}

	public function subscribe($formdata){

		if($formdata['email']){

			$query = $this->_db->getQuery(true);

			$query->insert('#__z_plants_subscribers');
			$query->set('email = ' .$this->_db->quote($formdata['email']));

			$this->_db->setQuery($query)->execute();

		}
		return false;
	}


}