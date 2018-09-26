<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Extended Utility class for the Users component.
 *
 * @since  2.5
 */
class JHtmlPlants
{
	public static function blockStates()
	{
		$states = array(
			1 => array(
				'task'           => 'block',
				'text'           => '',
				'active_title'   => 'Activate',
				'inactive_title' => '',
				'tip'            => true,
				'active_class'   => 'unpublish',
				'inactive_class' => 'unpublish',
			),
			0 => array(
				'task'           => 'unblock',
				'text'           => '',
				'active_title'   => '',
				'inactive_title' => 'COM_USERS_ACTIVATED',
				'tip'            => true,
				'active_class'   => 'publish',
				'inactive_class' => 'publish',
			)
		);

		return $states;
	}
}
