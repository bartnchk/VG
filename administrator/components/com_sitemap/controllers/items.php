<?php

defined('_JEXEC') or exit();

class SitemapControllerItems extends JControllerAdmin 
{
	public function getModel($name = 'Item', $prefix = 'SitemapModel', $config = array())
    {
		return parent::getModel($name, $prefix, $config);
	}

	public function generate()
    {
		$model = $this->getModel('Items');
		$model->generateMap();
	}
}
