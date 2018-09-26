<?php

defined('_JEXEC') or exit();

class SidebarTableItem extends JTable 
{
	public function __construct(&$db) 
	{
		parent::__construct('#__sitemap', 'id', $db);
	}
}
