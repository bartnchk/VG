<?php

defined('_JEXEC') or die;
jimport('joomla.application.component.view');
require_once (JPATH_ROOT."/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php");

class PlantsViewCatalog extends JViewLegacy
{
    protected $categories;
    protected $plants;
    protected $segment;
    protected $cities;
    protected $counter;
    protected $filter_values;
    protected $maxPlantPrice;
    protected $user;
    protected $randomPlants;
    protected $types;
    protected $config;
    protected $detect;

	public function display( $tpl = null )
    {
        JFactory::getDocument()->addScript('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.js');
        JFactory::getDocument()->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.default.min.css');
        JFactory::getDocument()->addScript('/components/com_plants/assets/js/catalog-page.js');


        $this->categories = $this->get('Categories');
        $this->plants = $this->get('Plants');
        $this->segment = $this->get('Segment');
        $this->cities = $this->get('Cities');
        $this->counter = $this->get('Counter');
        $this->filter_values = $this->get('FilterValues');
        $this->maxPlantPrice = $this->get('MaxPlantPrice');
        $this->types = $this->get('Types');
        $this->config = JComponentHelper::getParams('com_plants');
        $this->detect = new Mobile_Detect;

        if( !count($this->plants) )
            $this->randomPlants = $this->get('RandomPlants');

        $this->user = JFactory::getUser();

        $config = JFactory::getConfig();
        $doc = JFactory::getDocument();

        if($this->segment)
            $title = ucfirst($this->segment) . ' сatalog - ' . $config->get('sitename');
        else
            $title = 'Catalog - ' . $config->get('sitename');

        $doc->setTitle($title);

        //meta-data for facebook
        $doc->setMetaData( 'og:url', JUri::getInstance() );
        $doc->setMetaData( 'og:type', 'website' );
        $doc->setMetaData( 'og:title', $doc->getTitle() );
        //$doc->setMetaData( 'og:description', 'tagcontent' );
        $doc->setMetaData( 'og:image', JUri::base() . 'templates/vg/img/catalog-page.jpg' );


		return parent::display( $tpl );
	}
}