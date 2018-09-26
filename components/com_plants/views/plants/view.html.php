<?php

defined('_JEXEC') or die;

class PlantsViewPlants extends JViewLegacy
{
	protected $items;
	protected $userData;
	protected $currentUser;
	protected $categories;
	protected $currentCategory;
	protected $config;

	public function display( $tpl = null )
    {
        $this->items = $this->get('Items');
        $this->userData = $this->get('UserData');
        $this->currentUser = $this->get('CurrentUser');
        $this->categories = $this->get('Categories');
        $this->currentCategory = $this->get('Category');
        $this->config = JComponentHelper::getParams('com_plants');

        $config = JFactory::getConfig();
        $title = '';

        if($this->userData->juser_id == $this->currentUser->id)
            $title .= 'My plants - ';
        else
            $title .= $this->userData->first_name . "'s plants - ";

        $title .= $config->get('sitename');
        JFactory::getDocument()->setTitle($title);

		return parent::display( $tpl );
	}
}