<?php

defined( '_JEXEC' ) or die;

class PlantsHelper
{
	static function addSubmenu( $vName )
	{
        JHtmlSidebar::addEntry(
            'Top',
            'index.php?option=com_plants&view=posts',
            $vName == 'posts' );

	    JHtmlSidebar::addEntry(
			'Categories',
			'index.php?option=com_plants&view=categories',
			$vName == 'categories' );

		JHtmlSidebar::addEntry(
			'Types',
			'index.php?option=com_plants&view=types',
			$vName == 'types' );

		JHtmlSidebar::addEntry(
			'Plants',
			'index.php?option=com_plants&view=plants',
			$vName == 'plants' );

        JHtmlSidebar::addEntry(
            'Custom fields',
            'index.php?option=com_plants&view=plantfields',
            $vName == 'plantfields' );

		JHtmlSidebar::addEntry(
			'Users',
			'index.php?option=com_plants&view=users',
			$vName == 'users' );

		JHtmlSidebar::addEntry(
			'Comments',
			'index.php?option=com_plants&view=comments',
			$vName == 'comments' );

        JHtmlSidebar::addEntry(
            'Cities',
            'index.php?option=com_plants&view=cities',
            $vName == 'cities' );

		JHtmlSidebar::addEntry(
			'Subscribers',
			'index.php?option=com_plants&view=subscribers',
			$vName == 'subscribers' );
	}
}