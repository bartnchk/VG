<?php

defined('_JEXEC') or die;

class PlantsViewMain extends JViewLegacy
{
    protected $catalog;
    protected $dayPlant;
    protected $lastPlants;
    protected $randomPlant;
    protected $popularPlants;
    protected $user;
    protected $posts;
    protected $title;
    protected $config;

    public function display( $tpl = null )
    {
		$this->catalog       = $this->get('Catalog');
		$this->dayPlant      = $this->get('DayPlant');
		$this->lastPlants    = $this->get('LastPlants');
        $this->randomPlant   = $this->get('RandomPlant');
        $this->popularPlants = $this->get('PopularPlants');
        $this->posts         = $this->get('Posts');
        $this->user          = JFactory::getUser();
        $this->config        = JComponentHelper::getParams('com_plants');


        $config = JFactory::getConfig();
        $doc = JFactory::getDocument();
        $this->title = $config->get('sitename');
        $doc->setTitle($this->title);

        //JLog::add("Hello", JLog::INFO, 'com_plants');


        $doc->setMetaData( 'og:url', JUri::getInstance() );
        $doc->setMetaData( 'og:type', 'website' );
        $doc->setMetaData( 'og:title', $doc->getTitle() );
        //$doc->setMetaData( 'og:description', 'tagcontent' );
        $doc->setMetaData( 'og:image', JUri::base() . 'templates/vg/img/main-page.jpg' );

        return parent::display( $tpl );
	}
}