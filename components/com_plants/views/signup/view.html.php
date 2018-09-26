<?php

defined('_JEXEC') or die;

class PlantsViewSignup extends JViewLegacy
{
    protected $params;
    protected $form;

    public function display( $tpl = null )
    {
        $this->params = JComponentHelper::getParams('com_plants');
        $this->form = $this->get('Form');
        $config = JFactory::getConfig();

		$title = 'Signup - ' . $config->get('sitename');
        JFactory::getDocument()->setTitle($title);

        return parent::display( $tpl );

	}
}