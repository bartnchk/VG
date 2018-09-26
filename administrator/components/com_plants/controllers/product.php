<?php

defined('_JEXEC') or exit();

class ShopControllerProduct extends JControllerForm {

	protected $view_list = 'products';

	public function __construct($config = array())
    {
        parent::__construct($config);
        $model = $this->getModel();
    }

    public function deletepic()
    {

        $arr = $this->input->getArray(array());

        $db = JFactory::getDBO();


        $path = JPATH_SITE . '/images/products/';
        jimport('joomla.filesystem.file');
        $db->setQuery("select `file_name` from #__z_shop_images where id =" . $arr['picid']);
        $image_file_name = $db->loadResult();
        if (JFile::exists($path . $image_file_name)) JFile::delete($path . $image_file_name);

        $db->setQuery("delete from #__z_shop_images where id=".$arr['picid']);
        $db->execute();

        $app = JFactory::getApplication();
        $app->redirect('index.php?option=com_shop&view=product&task=product.edit&id='.$arr['jform']['id']);

    }
}