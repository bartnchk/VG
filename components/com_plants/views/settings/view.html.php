<?php

defined('_JEXEC') or die;

class PlantsViewSettings extends JViewLegacy
{
    protected $item;
    protected $form;
    protected $user_data;
    protected $cities;
    protected $user;
    protected $city;

    public function display($tpl = null)
    {
        JFactory::getDocument()->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.default.min.css');

        $this->form = $this->get('Form');
        $this->user_data = $this->get('Data');
        $this->item = $this->get('Item');
        $this->cities = $this->get('Cities');
        $this->user = JFactory::getUser();
        $this->city = $this->get('city');

        return parent::display($tpl);
    }
}