<?php

defined('_JEXEC') or exit();

$controller = JControllerLegacy::getInstance('Sitemap');

$input = JFactory::getApplication()->input;

$task = $input->get('task', 'display');

$controller->execute($task);

$controller->redirect();
