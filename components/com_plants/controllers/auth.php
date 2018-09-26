<?php

defined( '_JEXEC' ) or die;

class PlantsControllerAuth extends JControllerLegacy
{
    protected $app;

    public function __construct()
    {
        parent::__construct();
        $this->app = JFactory::getApplication();
    }

    //facebook auth
    public function fbAuth()
    {
        $redirect_url = JUri::base() . 'auth/fbAuth';
        $params = JComponentHelper::getParams( 'com_plants' );
        $input = $this->input;
        $code = $input->get( 'code', '', 'RAW' );

        try
        {
            $response = file_get_contents( 'https://graph.facebook.com/v2.11/oauth/access_token?client_id=' . $params->get( 'id' ) . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get( 'secretkey' ) . '&code=' . $code );
            $token = json_decode( $response, true );
            $formData = json_decode( file_get_contents( 'https://graph.facebook.com/v2.11/me?client_id=' . $params->get( 'id' ) . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get( 'secretkey' ) . '&code=' . $code . '&access_token=' . $token['access_token'] . '&fields=name,email,gender' ), true );

            $this->app->login( array(
                'fb_id'    => $formData['id'],
                'username' => '',
                'password' => ''
            ), array( 'silent' => true ) );
        }
        catch ( Exception $e )
        {
            echo $e->getMessage();
        }

        if(JFactory::getUser()->guest)
            $this->app->enqueueMessage(JText::_('USER_NOT_FOUND'), 'warning');

        $this->app->redirect('/');
    }

    //google auth
    public function gAuth()
    {
        $redirect_url = JUri::base() . 'auth/gAuth';

        $par = JComponentHelper::getParams('com_plants');
        $input = $this->app->input;
        $code = $input->get('code','','RAW');

        $url_token = 'https://accounts.google.com/o/oauth2/token';

        $params = [
            'client_id'     => $par->get('google_id'),
            'client_secret' => $par->get('google_auth_secretkey'),
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

            if(isset($tokenInfo['access_token']))
            {
                $params['access_token'] = $tokenInfo['access_token'];
                $formData = json_decode( file_get_contents( 'https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode( http_build_query( $params ) ) ), true );

                $this->app->login( array(
                    'google_id'    => $formData['id'],
                    'username' => '',
                    'password' => ''
                ), array( 'silent' => true ) );
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }

        if(JFactory::getUser()->guest)
            $this->app->enqueueMessage(JText::_('USER_NOT_FOUND'), 'warning');

        $this->app->redirect('/login');
    }
}
