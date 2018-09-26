<?php

defined('_JEXEC') or die;

class PlantsControllerPlant extends JControllerLegacy
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
        $this->app->enqueueMessage(JText::_('COMMENT_ADDED'), 'message');
        $this->app->redirect('/plant?id=' . $item_id);
    }

    public function deletePlant()
    {
        $plant_id = $this->input->get('id');
        $this->model->deletePlant($plant_id);
        $this->app->enqueueMessage(JText::_('PLANT_DELETED'), 'message');
        $this->app->redirect('/plants');
    }
}
