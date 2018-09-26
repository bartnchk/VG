<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgUserUserDelete extends JPlugin
{
    public function onUserAfterDelete($user)
    {
        JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_plants/models', 'PlantsModel');
        $model = JModelLegacy::getInstance('User', 'PlantsModel');

        $arr = array($user['id']);

        $model->delete($arr, false);
    }
}