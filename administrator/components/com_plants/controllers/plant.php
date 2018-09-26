<?php

defined('_JEXEC') or die();

class PlantsControllerPlant extends JControllerForm
{
    protected $view_list = 'plants';

    public function deletePhoto()
    {
        $img = $this->input->post->get('src');

        if($img)
        {
            $model = $this->getModel('Plant', 'PlantsModel');
            $model->deletePhoto($img);

            exit;
        }
    }
}