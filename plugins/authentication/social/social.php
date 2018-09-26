<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class PlgAuthenticationSocial extends JPlugin
{
	public function onUserAuthenticate($credentials, $options, &$response)
	{
        $response->type = 'Social';

		if (!empty($credentials['fb_id']))
		{
			$db = JFactory::getDbo();
			$user = $db->setQuery( "SELECT * FROM #__z_users WHERE facebook_id = " . $db->quote($credentials['fb_id']) )->loadObject();

		}
		elseif (!empty($credentials['google_id']))
		{
			$db = JFactory::getDbo();
			$user = $db->setQuery( "SELECT * FROM #__z_users WHERE gmail_id = " . $db->quote($credentials['google_id']) )->loadObject();
		}
		else {
			$response->status        = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JERROR_LOGIN_DENIED');
			return;
		}

		if($user)
        {
            $response->email = $user->email;
            $response->status = JAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
            $response->username = $user->email;
        }
	}
}
