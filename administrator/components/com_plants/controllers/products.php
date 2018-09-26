<?php

defined('_JEXEC') or exit();

class ShopControllerProducts extends JControllerAdmin {

	public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('savelist', 'saveList');
        $this->registerTask('saveord', 'saveord');
    }

    public function saveList()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $ordering    = $this->input->get('ordering', array(), 'array');

        // Get the model.
        $model = $this->getModel();

        // Change the state of the records.
        if (!$model->savelist($ordering))
        {
            JError::raiseWarning(500, $model->getError());
        }

        $this->setRedirect('index.php?option=com_shop&view=products');
    }

	public function getModel($name = 'Product', $prefix = 'ShopModel', $config = array()) {
		return parent::getModel($name, $prefix, $config);
	}

    public function saveord()
    {

        $arr = $this->input->get('ord', array(), 'array');

        $db = JFactory::getDBO();

        foreach($arr as $key => $value) {
            $db->setQuery("update #__z_shop_products set ordering = " . $value . " where id = " . $key);
            $db->execute();
        }

        $this->setRedirect('index.php?option=com_shop&view=products');

    }
}