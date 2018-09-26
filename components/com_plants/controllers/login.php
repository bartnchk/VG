<?php

defined( '_JEXEC' ) or die;

class PlantsControllerLogin extends JControllerLegacy
{
    protected $model;
    protected $app;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel( 'Login', 'PlantsModel' );
        $this->app   = JFactory::getApplication();
    }

    //auth by login form
    public function signin()
    {
        $username = $this->app->input->get('username');
        $password = $this->app->input->get('password', '', 'RAW');

        $this->app->login( array(
            'username' => $username,
            'password' => $password
        ), array( 'silent' => true ) );
    }

/*    //auth by social networks
    public function login()
    {
        $url = JUri::getInstance();

        $parts = explode( '/', $url );
        $action = substr($parts[4],0,6);

        if($action == 'fblogi')
            $this->fblogin();
        elseif($action == 'glogin')
            $this->glogin();
    }*/

    //facebook auth
    public function fblogin()
    {
        echo 1;
        exit;
        $redirect_url = JUri::base() . 'login/fblogin';
        $params = JComponentHelper::getParams( 'com_plants' );
        $input = $this->input;
        $code = $input->get( 'code', '', 'RAW' );

        try
        {
            $response = file_get_contents( 'https://graph.facebook.com/v2.11/oauth/access_token?client_id=' . $params->get( 'id' ) . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get( 'secretkey' ) . '&code=' . $code );
            $token = json_decode( $response, true );
            $formData = json_decode( file_get_contents( 'https://graph.facebook.com/v2.11/me?client_id=' . $params->get( 'id' ) . '&redirect_uri=' . $redirect_url . '&client_secret=' . $params->get( 'secretkey' ) . '&code=' . $code . '&access_token=' . $token['access_token'] . '&fields=name,email,gender' ), true );
            print_r($formData);
            exit;
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

        JFactory::getApplication()->redirect(JUri::base() . 'settings');
    }

    //google auth
    public function glogin()
    {
        $g_url = JUri::getInstance() . '/glogin';
        $parts = explode('/', $g_url);
        $redirect_url =  $parts[0] . '//' . $parts[2] . '/glogin';

        $params = JComponentHelper::getParams('com_plants');
        $input = $this->app->input;
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
    }
}
