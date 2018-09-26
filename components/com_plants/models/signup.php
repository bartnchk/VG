<?php

require_once (JPATH_ROOT.'/vendor/google/recaptcha/src/autoload.php');

defined('_JEXEC') or die;

class PlantsModelSignup extends JModelForm
{

    /**
     * @param array $data
     * @param bool $loadData
     * @return bool|JForm
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_plants.signup', 'signup', array('control' => 'jform', 'load_data' => $loadData));

        if ( empty($form) )
            return false;

        return $form;
    }


    /**
     * @param $formData
     * @param bool $captcha
     * @param bool $scen
     * @return bool|int
     * @throws Exception
     */
    public function register($formData, $captcha = true)
    {
        if( !isset($formData['id']) )
        {
            $formData['id'] = 0;
        }

        if( $this->check($formData['email']) ) {
            JFactory::getApplication()->enqueueMessage(JText::_('USER_ALREADY_EXISTS'), 'warning');
            JFactory::getApplication()->redirect( '/signup' );
        }

        if( $captcha && !$this->isValidCaptcha($formData) ) {
            JFactory::getApplication()->enqueueMessage(JText::_('INVALID_CAPTCHA'), 'warning');
            JFactory::getApplication()->redirect( '/signup' );
        }

        $juser_id = $this->addJoomlaUser($formData);
        $formData['juser_id'] = $juser_id;
        $this->saveUser($formData);

        return $juser_id;
    }



    /**
     * @param $formData array
     * @return boolean
    */
    public function isValidCaptcha($formData)
    {
        $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
        $params = new JRegistry($plugin->params);
        $secret_key = $params->get('private_key','');

        $recaptcha = new \ReCaptcha\ReCaptcha($secret_key);
        $resp = $recaptcha->verify($formData['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        return isset($formData['g-recaptcha-response']) &&
                trim($formData['g-recaptcha-response']) !== "" &&
                $resp->isSuccess();
    }



    /**
     * @param $formData
     * @return bool|int
     */
    public function addJoomlaUser($formData)
    {
        $params = JComponentHelper::getParams('com_users');

        $user = new JUser;

        if( isset($formData['name']) )
            $data['name'] = $formData['name'];
        else
        {
            $str = strpos($formData['email'], "@");
            $data['name'] = substr($formData['email'], 0, $str);
        }

        $data['username'] = $formData['email'];

        $data['email'] = JStringPunycode::emailToPunycode($formData['email']);

        $data['block'] = 1;

        if( !empty($formData['password']) )
        {
            $data['password'] = $formData['password'];
            $data['activation'] = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
        }
        else
        {
            $data['password'] = '';
            $data['block'] = 0;
        }

        $data['groups'] = array( $params->get('new_usertype', 2) );

        // Bind the data.
        if (!$user->bind($data))
        {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));

            return false;
        }

        /**
         * Load the users plugin group.
         */
        JPluginHelper::importPlugin('user');

        // Store the data.
        if (!$user->save())
        {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));

            return false;
        }

        $config = JFactory::getConfig();

        // Compile the notification mail values.
        $data = $user->getProperties();
        $data['fromname'] = $config->get('fromname');
        $data['mailfrom'] = $config->get('mailfrom');
        $data['sitename'] = $config->get('sitename');
        $data['siteurl'] = JUri::root();

        //signup by site
        if(isset($formData['password']))
            $this->sendUserMail($data, false);
        //signup by social network
        else
            $this->sendUserMail($data, true);

        return $user->id;
    }



    /**
     * @param $formData
     */
    public function saveUser($formData)
    {
        $query = $this->_db->getQuery(true);
        $query->insert('#__z_users');

        $query->set('email = ' . $this->_db->quote($formData['email']) );
        $query->set('username = ' . $this->_db->quote($formData['email']) );
        $query->set('juser_id = ' . $this->_db->quote($formData['juser_id']) );
        $query->set('created_at = NOW()');
        $query->set('updated_at = NOW()');

        /**
         * signup by social networks
         */

        if( isset($formData['photo']) )
        {
            $src = $this->getPhotoSrc($formData['photo']);

            $fullname = explode(' ', $formData['name']);

            if(isset($fullname[0]) && trim($fullname[0]) != '')
            {
                $query->set('first_name = ' . $this->_db->quote($fullname[0]) );
            }
            if(isset($fullname[1]) && trim($fullname[1]) != '')
            {
                $query->set('last_name = ' . $this->_db->quote($fullname[1]) );
            }

            $query->set('photo = ' . $this->_db->quote($src) );
            if(!empty($formData['gender']))
            {
                $query->set( 'gender = ' . $this->_db->quote( $formData['gender'] ) );
            }
            if($formData['auth_type'] == 'facebook')
            {
                $query->set('facebook_id = ' . $this->_db->quote($formData['id']) );
            }
            elseif ($formData['auth_type'] == 'gmail')
            {
                $query->set('gmail_id = ' . $this->_db->quote($formData['id']) );
            }
        }

        /**
         * signup by custom registration
         */

        else
        {
            //$query->set('password = ' . $this->_db->quote($formData['password']) );

            $str = strpos($formData['email'], "@");
            $first_name = substr($formData['email'], 0, $str);
            $query->set('first_name = ' . $this->_db->quote($first_name));
        }

        $this->_db->setQuery($query);

        try
        {
            $this->_db->execute();
        }
        catch (Exception $error)
        {
            echo $error->getMessage();
        }
    }



    /**
     * @param $data
     * @param $social
     */
    public function sendUserMail($data, $social)
    {
        // Set the link to activate the user account.
        $uri = JUri::getInstance();
        $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
        $data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation'], false);

        $emailSubject = "Registration";

        if(!$social)
            $emailBody = "Hello, thank you for registering. Your account is created and must be activated before you can use it. To activate the account select the following link or copy-paste it in your browser: " . $data['activate'] . " After activation you may login to site using the following username and the password you entered during registration";
        else
            $emailBody = "Hello, thank you for registering";

        JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
    }



    /**
     * @param $par
     * @return bool
     */
    private function check($par)
    {
        $this->_db->setQuery( 'SELECT * FROM #__users WHERE email = ' . $this->_db->quote($par) );
        $result = $this->_db->loadResult();

        if($result)
            return true;
        else
            return false;
    }



    /**
     * @param $photo
     * @return string
     */
    private function getPhotoSrc($photo)
    {
        $filename = 'user_avatar' . uniqid() . '.jpg';
        $savePath = JPATH_BASE . '/images/user_photos/' . $filename;

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        set_error_handler(
            function($severity, $message, $file, $line) {
                throw new ErrorException($message, $severity, $severity, $file, $line);
            }
        );

        $responseData = file_get_contents($photo, false, stream_context_create($arrContextOptions));

        $file = fopen($savePath, "w+");
        fputs($file, $responseData);
        fclose($file);

        $folder = 'images/user_photos/';

        JLoader::register('OptimizeHelper', JPATH_ADMINISTRATOR . '/components/com_plants/helpers/optimize.php');

        OptimizeHelper::optimizePhoto($savePath, 500, 500, $folder, $filename);

        return $filename;
    }
}