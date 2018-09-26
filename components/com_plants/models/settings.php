 <?php

defined('_JEXEC') or die;

JLoader::register('OptimizeHelper', JPATH_ADMINISTRATOR . '/components/com_plants/helpers/optimize.php');

class PlantsModelSettings extends JModelForm
{
    protected $data;
    protected $app;
    protected $city_id;

    /**
     * PlantsModelSettings constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config = array())
    {
        $this->app = JFactory::getApplication();
        parent::__construct($config);
        $this->data = $this->getData();
    }



    /**
     * @param array $data
     * @param bool $loadData
     * @return bool|JForm
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_plants.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }

        if($this->data->first_name)
            $form->setValue('first_name','', $this->data->first_name);

        if($this->data->last_name)
            $form->setValue('last_name','', $this->data->last_name);

        if($this->data->username)
            $form->setValue('username','', $this->data->username);

        if($this->data->email)
            $form->setValue('email', '', $this->data->email);

        if($this->data->gender)
            $form->setValue('gender', '', $this->data->gender);

        if($this->data->experience)
            $form->setValue('experience', '', $this->data->experience);

        if($this->data->about_me)
            $form->setValue('about_me', '', $this->data->about_me);

        if($this->data->city_id)
            $this->city_id = $this->data->city_id;

        if($this->data->secret_question)
            $form->setValue('secret_question', '', $this->data->secret_question);

        if($this->data->secret_answer)
            $form->setValue('secret_answer', '', $this->data->secret_answer);

        return $form;
    }



    /**
     * @return mixed
     */
    public function getData()
    {
        $user_id = JFactory::getUser()->id;

        if($user_id)
        {
            $data = $this->_db->setQuery("SELECT * FROM #__z_users WHERE juser_id = $user_id")->loadObject();
            return $data;
        }
        else
        {
            $this->app->redirect( '/login' );
        }
    }



    /**
     * @param $formData
     */
    public function update_user_profile($formData)
    {
        JSession::checkToken() or die( 'Invalid Token' );

        $query = $this->_db->getQuery(true);
        $query->update('#__z_users');

        if($formData['photo']['size'])
        {
            $filename = $this->savePhoto($formData['photo']);
            $this->deleteOldPhoto();
        }

        if( $formData['first_name'] || $formData['last_name'] )
        {
            if( isset($formData['first_name']) )
                $query->set('first_name = ' . $this->_db->quote($formData['first_name']) );

            if( isset($formData['last_name']) )
                $query->set('last_name = ' . $this->_db->quote($formData['last_name']) );
        }

        if( !$formData['email'] || trim($formData['email']) == '')
        {
            $this->app->enqueueMessage(JText::_('EMAIL_IS_EMPTY'), 'warning');
            $this->app->redirect('/settings');
        }
        else
        {
            if( JFactory::getUser()->email != $formData['email'] && $this->checkEmail($formData['email']) )
            {
                if( !$this->updateEmail($formData['email']) )
                    $this->app->enqueueMessage(JText::_('ERROR'), 'warning');
            }
        }

        if( isset($formData['experience']) && $formData['experience'])
            $query->set('experience = ' . $this->_db->quote($formData['experience']) );

        if( isset($formData['gender']) )
            $query->set('gender = ' . $this->_db->quote($formData['gender']) );

        if( isset($formData['secret_question']) )
            $query->set('secret_question = ' . $this->_db->quote($formData['secret_question']) );

        if( isset($formData['secret_answer']) )
            $query->set('secret_answer = ' . $this->_db->quote($formData['secret_answer']) );

        if( isset($formData['gender']) )
            $query->set('gender = ' . $this->_db->quote($formData['gender']) );

        if( isset($formData['about_me']) && $formData['about_me'])
            $query->set('about_me = ' . $this->_db->quote($formData['about_me']) );

        if($formData['photo']['size'])
            $query->set('photo = ' . $this->_db->quote($filename) );

        if( isset($formData['city_id']) )
            $query->set('city_id = ' . $this->_db->quote($formData['city_id']) );

        if( isset($formData['privacy_policy']) && ($formData['privacy_policy'] == 'on') )
            $query->set('submit_rules = 1');
        else
            $query->set('submit_rules = 0');

        if($formData['day'] && $formData['month'] && $formData['year'])
        {
            $date = $this->prepareDate($formData['day'], $formData['month'], $formData['year']);
            $query->set('birthday = ' . $this->_db->quote($date) );
        }

        $query->set('updated_at = NOW()');

        $query->where('juser_id = ' . $formData['juser_id']);

        $this->_db->setQuery($query)->execute();

        $this->changePassword($formData['password1'], $formData['password2'], $formData['juser_id']);
    }



    /**
     * delete old photo if user change avatar
     */
    private function deleteOldPhoto()
    {
        $query = $this->_db->getQuery(true);
        $query->select('photo');
        $query->from('#__z_users');
        $query->where('juser_id = ' . JFactory::getUser()->id);
        $this->_db->setQuery($query);
        $filename = $this->_db->loadResult();

        jimport('joomla.filesystem.file');
        JFile::delete(JPATH_ROOT.'/images/user_photos/' . $filename);
    }



    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->data;
    }



    /**
     * @return mixed
     */
    public function getCities()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_cities');
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }



    /**
     * @param $day
     * @param $month
     * @param $year
     * @return string
     */
    public function prepareDate($day, $month, $year)
    {
        if($day < 10)
            $day = '0' . $day;

        if($month < 10)
            $month = '0' . $month;

        return $year . '-' . $month . '-' . $day;
    }



    /**
     * @param $src
     * @return string
     */
    public function savePhoto($photo)
    {
        $filename = uniqid() . $photo['name'];
        $tmp = $photo['tmp_name'];
        $folder = 'images/user_photos/';

        OptimizeHelper::optimizePhoto($tmp, 500, 500, $folder, $filename);

        return $filename;
    }



    /**
     * @param $user_id
     * @param $mess
     */
    public function send_delete_request($user_id, $mess)
    {
        $params = JComponentHelper::getParams('com_plants');
        $email = $params->get('site_email');

        $user = $this->_db->setQuery('SELECT first_name, last_name FROM #__z_users WHERE juser_id = ' . $user_id)->loadObject();

        $message  = 'User name: ' . $user->first_name . ' ' . $user->last_name . '<br>';
        $message .= 'ID: ' . $user_id . '<br>';
        $message .= 'Send delete profile request';

        if($mess)
            $message .= ' with message: ' . $mess;

        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();

        $mailer->setFrom($email);
        $mailer->addRecipient( $config->get('mailfrom') );
        $mailer->setSubject('Delete profile request');
        $mailer->isHtml(true);

        $mailer->setBody($message);
        $mailer->Send();

        return true;
    }



    /**
     * @param $user_id
     * @param $message
     */
    public function saveUserDeleteRequest($user_id, $message)
    {
        //delete old data
        $query = $this->_db->getQuery(true);
        $query->delete('#__z_user_requests');
        $query->where( 'user_id = ' . $user_id );
        $this->_db->setQuery($query);
        $this->_db->execute();

        //save new request data
        $query->clear();
        $query->insert('#__z_user_requests');
        $query->set( 'user_id = ' . $user_id );
        $query->set( 'message = ' . $this->_db->quote($message) );
        $this->_db->setQuery($query);
        $this->_db->execute();

        return true;
    }



    /**
     * @param $email
     * update user email and send confirmation mail
     */
    public function updateEmail($email)
    {
        $hash = JApplicationHelper::getHash(JUserHelper::genRandomPassword());

        $query = $this->_db->getQuery(true);
        $query->update('#__users');
        $query->set('email = ' . $this->_db->quote($email));
        $query->where('id = ' . JFactory::getUser()->id);

        if($this->_db->setQuery($query)->execute())
        {
            $query->clear();
            $query->update('#__z_users');
            $query->set( 'email = ' . $this->_db->quote($email) );
            $query->set( 'email_hash = ' . $this->_db->quote($hash) );
            $query->set( 'email_status = 0' );
            $query->where('juser_id = ' . JFactory::getUser()->id);
        }

        if($this->_db->setQuery($query)->execute() && $this->sendEmailConfirmation($hash, $email))
        {
            return true;
        }

        return false;
    }



    /**
     * @param $hash
     * @param $email
     * @return bool|JException
     */
    public function sendEmailConfirmation($hash, $email)
    {
        $body  = 'Email confirmation link: <br> ';
        $body .= JUri::base() . 'index.php?option=com_plants&task=settings.activateEmail&email=' . $email . '&token=' . $hash;
        $mailer = JFactory::getMailer();
        $sender = array(
            'noreply@' . JUri::base(true),
            'Vegetables'
        );
        $mailer->isHtml(true);
        $mailer->setSender($sender);
        $mailer->addRecipient($email);
        $mailer->setSubject('Email confirmation');
        $mailer->setBody($body);

        return $mailer->Send();
    }



    /**
     * @return bool
     * check email
     */
    public function checkEmail($email)
    {
        //is email free
        $query = $this->_db->getQuery(true);
        $query->select('email')
              ->from('#__users')
              ->where('email = ' . $this->_db->quote($email));

        $this->_db->setQuery($query);

        if($this->_db->loadResult())
        {
            $this->app->enqueueMessage(JText::_('EMAIL_NOT_AVAILABLE'), 'warning');
            $this->app->redirect('/settings');
        }

        return true;
    }



    /**
     * @param $email
     * @param $token
     * activation link handler
     */
    public function activateEmail($email, $token)
    {
        $query = $this->_db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('#__z_users');
        $query->where('email = ' . $this->_db->quote($email));
        $query->andWhere('email_hash = ' . $this->_db->quote($token));

        if($this->_db->setQuery($query)->loadResult() == 1)
        {
            $query->clear();
            $query->update('#__z_users');
            $query->set('email_status = 1');
            $query->set('email_hash = ' . $this->_db->quote(''));
            $query->where('email = ' . $this->_db->quote($email));
            $query->andWhere('email_hash = ' . $this->_db->quote($token));
            $this->_db->setQuery($query)->execute();

            $this->app->enqueueMessage(JText::_('EMAIL_CONFIRMED'), 'message');
        }
        else
            $this->app->enqueueMessage(JText::_('INCORRECT_CONFIRMATION_EMAIL_LINK'), 'warning');

        $this->app->redirect('/');
    }



    /**
     * @param $img
     * @param $id
     * delete user photo by ajax
     */
    public function deleteUserPhoto($img, $id)
    {
        if(trim($img) != '' && trim($id) != '')
        {
            $query = $this->_db->getQuery(true);

            $query->update('#__z_users');
            $query->set('photo = ' . $this->_db->quote(''));
            $query->where('juser_id = ' . $this->_db->quote($id));

            if($this->_db->setQuery($query)->execute())
            {
                jimport('joomla.filesystem.file');
                JFile::delete(JPATH_ROOT.'/images/user_photos/' . $img);
            }
        }
    }



    /**
     * @return selected city
     */
    public function getCity()
    {
        if($this->city_id) {
            $query = $this->_db->getQuery(true);
            $query->select('sc.id, concat(sc.name_en, ", ", _sc.name_en) as name_en');
            $query->from('#__sxgeo_cities sc');
            $query->leftJoin('#__sxgeo_regions sr ON sr.id = sc.region_id');
            $query->leftJoin('#__sxgeo_country _sc on _sc.iso = sr.country');
            $query->where('sc.id = ' . $this->city_id);
            $this->_db->setQuery($query);

            return $this->_db->loadObject();

        } else {

            return false;
        }
    }



    /**
     * @param $pass1
     * @param $pass2
     * @param $user_id
     * Change user password
     */
    private function changePassword($pass1, $pass2, $user_id)
    {
        if(trim($pass1) !== '' && trim($pass2) !== '' && $pass1 && $pass2)
        {
            if($pass1 !== $pass2)
            {
                $this->app->enqueueMessage( JText::_('PASSWORD_DO_NOT_MATCH'), 'warning' );
                $this->app->redirect('/settings');
            }

            if( strlen($pass1) < 8 )
            {
                $this->app->enqueueMessage( JText::_('PASSWORD_IS_TOO_SHORT'), 'warning' );
                $this->app->redirect('/settings');
            }

            //check if password contains letters and numbers
            if ( preg_match('/[A-Za-z]/', $pass1) && preg_match('/[0-9]/', $pass1) )
            {
                jimport('joomla.user.helper');
                $pass = JUserHelper::hashPassword($pass1);

                $query = $this->_db->getQuery(true);
                $query->update($this->_db->quoteName('#__users'))
                    ->set($this->_db->quoteName('password') . ' = ' . $this->_db->quote($pass))
                    ->where($this->_db->quoteName('id') . ' = ' . (int)$user_id);

                $this->_db->setQuery($query)->execute();

            }
            else
            {
                $this->app->enqueueMessage( JText::_('PASSWORD_CONTAIN'), 'warning' );
                $this->app->redirect('/settings');
            }
        }

        return;
    }
}