<?php

defined('_JEXEC') or die;

class PlantsViewProfile extends JViewLegacy
{
    protected $user;
    protected $categories;
    protected $best_plant;
    protected $counter;
    protected $plants;
    protected $currentUserId;
    protected $config;

    public function display( $tpl = null )
    {
        $this->user = $this->get('User');
		$this->categories = $this->get('Categories');
		$this->best_plant = $this->get('BestPlant');
		$this->counter = $this->get('PlantsCounter');
		$this->plants = $this->get('Plants');
		$this->currentUserId = $this->get('CurrentUser')->id;
        $this->config = JComponentHelper::getParams('com_plants');
		$conf = JFactory::getConfig();

		$title = $this->user->first_name . ' ' . $this->user->last_name . ' - ' . $conf->get('sitename');
		$doc = JFactory::getDocument();
        $doc->setTitle($title);

		return parent::display( $tpl );
	}
}