<?php

defined( '_JEXEC' ) or die;

class PlantsControllerSender extends JControllerLegacy
{
    public function sendMessage()
    {
        $app = JFactory::getApplication();
        $model = $this->getModel('Sender', 'PlantsModel');

        $email = $this->input->get('email', '', 'STRING');
        $message = $this->input->get('message', '', 'MESSAGE');

        $resp = $model->sendMessage($email, $message);

        if($resp)
            $app->enqueueMessage( JText::_('FEEDBACK_SENT'), 'warning' );
        else
            $app->enqueueMessage( JText::_('FEEDBACK_SENT_FAILED') );

        $app->redirect('/impressum-page');

    }
}
