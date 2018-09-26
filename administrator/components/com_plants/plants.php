<?php

defined('_JEXEC') or exit();

require_once JPATH_COMPONENT.'/helpers/plants.php';
require_once JPATH_COMPONENT.'/helpers/html/users.php';

$controller = JControllerLegacy::getInstance('Plants');

$input = JFactory::getApplication()->input;

$task = $input->get('task', 'display');

$controller->execute($task);

$controller->redirect();