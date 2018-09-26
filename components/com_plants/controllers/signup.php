<?php

defined('_JEXEC') or die;

class PlantsControllerSignup extends JControllerLegacy
{
    protected $model;
    protected $app;

    public function __construct()
    {
        parent::__construct();

        $this->model = $this->getModel('Signup', 'PlantsModel');
        $this->app = JFactory::getApplication();
    }

    public function register()
    {
        $formData = $this->input->get('jform', array(), 'array');
        $this->checkPassword($formData['password']);

        $captcha = $this->input->get('g-recaptcha-response', '', 'RAW');
        $this->model->register(array_merge($formData, ['g-recaptcha-response' => $captcha]));
        JFactory::getApplication()->enqueueMessage(JText::_('SUCCESS_SIGNUP_MESSAGE_CHECK_EMAIL'), 'message');
        $this->app->redirect('/');
    }

    private function checkPassword($pass)
    {
        $pass = trim($pass);
        if( preg_match('/[0-9]/', $pass) && preg_match('/[a-z]/', $pass) )
        {
            if(strlen($pass) < 8)
            {
                $this->app->enqueueMessage( JText::_('PASSWORD_IS_TOO_SHORT'), 'warning' );
                $this->app->redirect('/signup');
            }
        }
        else
        {
            $this->app->enqueueMessage( JText::_('PASSWORD_CONTAIN'), 'warning' );
            $this->app->redirect('/signup');
        }


    }

    /**
     * Facebook authorisation
     */
    public function fbSignup()
    {
        $redirect_url = JUri::base() . 'signup/fbSignup';
        $params = JComponentHelper::getParams('com_plants');
        $input = JFactory::getApplication()->input;
        $code = $input->get('code','','RAW');

        try
        {
            $response = file_get_contents('https://graph.facebook.com/v2.11/oauth/access_token?client_id=' . $params->get('id') . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get('secretkey') . '&code=' . $code . '&scope=email');
            $token = json_decode($response, true);
            $formData = json_decode(file_get_contents('https://graph.facebook.com/v2.11/me?client_id=' . $params->get('id') . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get('secretkey') . '&code=' . $code . '&access_token=' . $token['access_token'] . '&fields=name,email,gender'), true);

            $formData['photo'] = 'https://graph.facebook.com/v2.11/'.$formData['id'].'/picture?width=600';
            $formData['auth_type'] = 'facebook';

            if( !isset($formData['email']) )
            {
                $this->app->enqueueMessage( JText::_('EMAIL_REQUIRED'), 'warning' );
                $this->app->redirect('/signup');
            }

            $this->model->register($formData, false);
            JFactory::getApplication()->enqueueMessage(JText::_('SUCCESS_SIGNUP_MESSAGE'), 'message');

            $this->auth($formData['id'], 'fb');
            $this->app->redirect('/');
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }


    /**
     * Gmail authorisation
     */
    public function gSignup()
    {
        $redirect_url = JUri::base() . 'signup/gSignup';
        $params = JComponentHelper::getParams('com_plants');
        $input = JFactory::getApplication()->input;
        $code = $input->get('code','','RAW');
        $url_token = 'https://accounts.google.com/o/oauth2/token';

        $params = [
            'client_id'     => $params->get('google_id'),
            'client_secret' => $params->get('google_auth_secretkey'),
            'redirect_uri'  => $redirect_url,
            'grant_type'    => 'authorization_code',
            'code'          => $code
        ];

        try
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url_token);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $tokenInfo = json_decode($result, true);

            if(isset($tokenInfo['access_token'])){
                $params['access_token'] = $tokenInfo['access_token'];

                $formData = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
                $formData['photo'] = $formData['picture'];
                $formData['auth_type'] = 'gmail';
                $this->model->register($formData, false);
                JFactory::getApplication()->enqueueMessage(JText::_('SUCCESS_SIGNUP_MESSAGE'), 'message');

                $this->auth($formData['id'], 'google');
                JFactory::getApplication()->redirect('/settings');
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    private function auth($id, $type)
    {
        if ($type == 'fb')
            $data = array('fb_id' => $id, 'username' => '', 'password' => '');
        elseif ($type = 'google')
            $data = array('google_id' => $id, 'username' => '', 'password' => '');

        $this->app->login($data , array( 'silent' => true ) );
    }
}