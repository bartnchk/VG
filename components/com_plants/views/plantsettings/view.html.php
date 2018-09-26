<?php

require_once (JPATH_ROOT."/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php");
defined('_JEXEC') or die;

class PlantsViewPlantsettings extends JViewLegacy
{
    protected $form;
    protected $lastAddedPlants;
    protected $plantData;
    protected $customFields;
    protected $user;
    protected $photos;
    protected $session;
    protected $categories;
    protected $types;
    protected $detect;

    public function display($tpl = null)
    {
        JFactory::getDocument()->addScript('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.js');
        JFactory::getDocument()->addScript('/components/com_plants/assets/js/plantsettings-page.js');
        JFactory::getDocument()->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.default.min.css');

        $this->detect = new Mobile_Detect;

        $this->form = $this->get('Form');
        $this->lastAddedPlants = $this->get('LastAddedPlants');
        $this->plantData = $this->get('PlantData');
        $this->customFields = $this->get('CustomFields');
        $this->photos = $this->get('PlantPhotos');
        $this->user = JFactory::getUser();
        $this->session = JFactory::getSession();
        $this->categories = $this->get('Categories');
        $this->types = $this->get('types');

        return parent::display($tpl);
    }
}