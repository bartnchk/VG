<?php

defined( '_JEXEC' ) or die;

class PlantsControllerCatalog extends JControllerLegacy
{
    private $model;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->model = $this->getModel('Catalog', 'PlantsModel');
    }

    public function loadPlants()
    {
        $counter = $this->input->get('counter');
        $segment = $this->input->get('segment');
        $this->model->getPlants($counter, $segment);
        exit;
    }


}