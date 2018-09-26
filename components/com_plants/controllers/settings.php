<?php

defined('_JEXEC') or die;

JLoader::register('PlantsController', JPATH_COMPONENT . '/controller.php');

class PlantsControllerSettings extends JControllerLegacy
{
    protected $model;
    protected $user_id;
    protected $app;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->model = $this->getModel('Settings', 'PlantsModel');
        $this->user_id = JFactory::getUser()->id;
        $this->app = JFactory::getApplication();
    }

    public function updateProfile()
    {
        $data = $this->input->getArray(array(
            'jform' => array(
                'email'           => 'string',
                'first_name'      => 'string',
                'last_name'       => 'string',
                'day'             => 'int',
                'month'           => 'int',
                'year'            => 'int',
                'gender'          => 'int',
                'secret_question' => 'string',
                'secret_answer'   => 'string',
                'experience'      => 'word',
                'city_id'         => 'string',
                'about_me'        => 'string',
                'password1'       => 'raw',
                'password2'       => 'raw',
                'privacy_policy'  => 'string'
            )
        ));

        $formData = $data['jform'];
        $formData['juser_id'] = $this->user_id;
        $photos = $this->input->files->get('photo', array(), 'array');
        $formData['photo'] = !$photos ? array() : $photos[0];

        $this->model->update_user_profile($formData);
        $this->app->enqueueMessage( JText::_('USER_DATA_SAVED'), 'message' );
        $this->app->redirect('/profile');
    }

    public function delRequest()
    {
        $message = $this->app->input->get('message', false, 'string');

        if(
            $this->user_id && $this->model->send_delete_request($this->user_id, $message) &&
            $this->model->saveUserDeleteRequest($this->user_id, $message)
        )
        {
            $this->app->enqueueMessage(JText::_('DELETE_REQUEST'), 'message');
            $this->app->redirect('/');
        }
    }



    /**
     * activate email
     */
    public function activateEmail()
    {
        $email = $this->app->input->get('email', '', 'STRING');
        $token = $this->app->input->get('token', '', 'STRING');

        $this->model->activateEmail($email, $token);
    }



    /**
     * delete user_photo by ajax
     */
    public function deletePhoto()
    {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $img = $this->input->get('src', false, 'RAW');
        $id  = $this->input->get('id', false, 'int');

        $this->model->deleteUserPhoto($img, $id);
    }
}
