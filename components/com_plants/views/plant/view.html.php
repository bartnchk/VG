<?php

defined('_JEXEC') or die;

class PlantsViewPlant extends JViewLegacy
{
	protected $plant;
	protected $form;
	protected $comments;
	protected $photos;
	protected $currentUser;

	public function display( $tpl = null )
    {
        $title = '';
        $this->plant = $this->get('Plant');
        $this->comments = $this->get('Comments');
		$this->form = $this->get('Form');
		$this->photos = $this->get('Photos');
		$this->currentUser = $this->get('CurrentUser');
        $config = JFactory::getConfig();

		if($this->plant->first_name)
		    $title .= $this->plant->first_name . "'s plant - ";

        $title .= $this->plant->sort_name;
        $title .= ' - ' . $config->get('sitename');

        JFactory::getDocument()->setTitle($title);
		return parent::display( $tpl );
	}
}
