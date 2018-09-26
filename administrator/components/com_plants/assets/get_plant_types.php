<?php

    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);

    $curDir	= dirname(__FILE__);
    $curDir	= substr($curDir, 0, strrpos($curDir, DS));
    $curDir	= substr($curDir, 0, strrpos($curDir, DS));
    $curDir	= substr($curDir, 0, strrpos($curDir, DS));

    define('JPATH_BASE', $curDir);

    require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );

    $mainframe = JFactory::getApplication('administrator');
    $mainframe->initialise();

    $input = JFactory::getApplication()->input;

    $id = $input->get('id');

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('id, title');
    $query->from('#__z_plants_plant_types');
    $query->where('category_id = ' . $id);
    $query->order('title ASC');
    $db->setQuery($query);

    echo json_encode( $db->loadAssocList(), JSON_UNESCAPED_UNICODE );
?>