<?php

    defined('_JEXEC') or die();

    header('X-XSS-Protection: 1');

    $controller = JControllerLegacy::getInstance('Plants');

    $input = JFactory::getApplication()->input;

    $task = $input->getCmd('task');

    $controller->execute($task);

    $controller->redirect();