<?php

defined( '_JEXEC' ) or die;

class PlantsControllerMain extends JControllerLegacy
{
/*    private $model;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->model = $this->getModel('Main', 'PlantsModel');
    }*/

    public function search()
    {
        $input = JFactory::getApplication()->input;
        $search_query = $input->get('search', '', 'String');
        $model = $this->getModel('search');
        echo $search_query;
        exit;
        $model->search($search_query);
    }
}