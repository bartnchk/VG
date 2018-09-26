<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

$curDir	= dirname(__FILE__);
/*$curDir	= substr($curDir, 0, strrpos($curDir, DS));
$curDir	= substr($curDir, 0, strrpos($curDir, DS));
$curDir	= substr($curDir, 0, strrpos($curDir, DS));*/

define('JPATH_BASE', $curDir);

require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );


$mainframe = JFactory::getApplication('administrator');
$user = JFactory::getUser();

$input = JFactory::getApplication()->input;
$user_id = $input->get('id', '', 'INT');

if(getUserId($user) == $user_id || isAdmin($user)) {

    deleteFile($user_id);

    $db = JFactory::getDbo();
    $db->setQuery("DELETE FROM #__user_profiles WHERE user_id = $user_id AND profile_key = 'profile5.photo'");
    $db->execute();

    echo 'Success';

} else {

    echo 'Failed';

}

function isAdmin($user)
{
    $user_groups = $user->groups;

    foreach ($user_groups as $group)
    {
        if($group == 8)
            return true;
    }

    return false;
}

function getUserId($user)
{
    return $user->id;
}

function deleteFile($user_id)
{
    jimport('joomla.filesystem.file');

    $file_name = JFactory::getDbo()->setQuery("SELECT profile_value FROM #__user_profiles WHERE user_id = $user_id AND profile_key = 'profile5.photo'")->loadObject();
    $src = str_replace("\"", "", $file_name->profile_value);

    JFile::delete(JPATH_BASE . '/images/user_photos/' . $src);
}