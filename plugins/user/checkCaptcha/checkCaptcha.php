<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
require_once (JPATH_ROOT.'/vendor/google/recaptcha/src/autoload.php');

class plgUserCheckCaptcha extends JPlugin
{
    public function onUserAuthorisation()
    {
        $lang = JFactory::getLanguage();
        $extension = 'com_plants';
        $base_dir = JPATH_SITE;
        $language_tag = $lang->getLocale();
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag[2], $reload);


        $app = JFactory::getApplication();
        $input = $app->input;

        //if user auth with sosial network buttons
        if( !array_key_exists('g-recaptcha-response', $_POST) )
            return;

        $captchaResp = $input->get('g-recaptcha-response');
        $secret_key = $this->getSecretKey();
        $recaptcha = new \ReCaptcha\ReCaptcha($secret_key);
        $resp = $recaptcha->verify($captchaResp, $_SERVER['REMOTE_ADDR']);

        if( isset($captchaResp) && trim($captchaResp) !== "" && $resp->isSuccess() )
        {
            //code...
        }
        else
        {
            $app->enqueueMessage( JText::_('EMPTY_CAPTCHA'), 'warning' );
            $app->redirect('/login');
        }
    }

    private function getSecretKey()
    {
        $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
        $pluginParams = new JRegistry();
        $pluginParams->loadString($plugin->params);

        return $pluginParams->get('private_key');
    }
}