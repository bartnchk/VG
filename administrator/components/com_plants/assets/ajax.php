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


JSession::checkToken() or jexit('Invalid token');
exit;


$input = JFactory::getApplication()->input;

$img = $input->get('src','','RAW');

$id = $input->get('id');

if($img && $id)
{
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);

	$query->update('#__z_users');
	$query->set('photo = ' . $db->quote('user.svg'));
	$query->where('juser_id = ' . $id);

	if($db->setQuery($query)->execute())
	{
	    jimport('joomla.filesystem.file');
		JFile::delete(JPATH_ROOT.'/images/user_photos/'.$img);
	}
}