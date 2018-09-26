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
    $query->select('id, name');
    $query->from('#__z_plants_fields');
    $query->where('plant_type_id = ' . $id);
    $db->setQuery($query);

    $result = $db->loadAssocList();

    if($result)
        echo json_encode( $result, JSON_UNESCAPED_UNICODE );
?>