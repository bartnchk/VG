<?php

defined('_JEXEC') or die;

class PlantsControllerProfile extends JControllerLegacy
{
    protected $model;
    protected $app;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->app = JFactory::getApplication();
        $this->model = $this->getModel('plant');
    }

    public function sendComment()
    {
        $comment  = $this->input->get('comment', '', 'text');
        $item_id = $this->model->saveComment($comment);
        $this->app->redirect(JUri::base() . 'plant?id=' . $item_id);
    }

    public function deletePlant()
    {
        $plant_id = $this->input->get('id');
        $this->model->deletePlant($plant_id);
        $this->app->redirect(JUri::base() . 'plants');
    }
}
