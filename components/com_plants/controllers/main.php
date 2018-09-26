<?php

defined( '_JEXEC' ) or die;


class PlantsControllerMain extends JControllerLegacy
{
    public function subscribe()
    {
        $input = JFactory::getApplication()->input;
        $email = $input->get('email', '', 'String');
        $model = $this->getModel('main');
        $model->subscribe($email);
        exit;
    }
}