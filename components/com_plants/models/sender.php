<?php

defined('_JEXEC') or die;
jimport( 'joomla.application.component.modelform' );

class PlantsModelSender extends JModelList
{
    public function sendMessage($email, $message)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();

        $mailer->setFrom($email);
        $mailer->addRecipient( $config->get('mailfrom') );
        $mailer->setSubject('Feedback');
        $mailer->isHtml(true);

        $mailer->setBody($message);

        return $mailer->Send() ? true : false;
    }
}