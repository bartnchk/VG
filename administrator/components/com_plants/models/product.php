<?php

use Joomla\Registry\Registry;

defined('_JEXEC') or exit();

class ShopModelProduct extends JModelAdmin {

	public function getForm($data = array(), $loadData = true) {

		$form = $this->loadForm(
			'com_shop.product',
			'product',
			array(
				'control' => 'jform',
				'load_data' => $loadData
				)
			);

		if (empty($form))
			return false;
		else
			return $form;
	}

	public function getTable($type = 'Product', $prefix = 'ShopTable', $config = array())
    {
		return JTable::getInstance($type, $prefix, $config);
	}

    protected function prepareTable($table) {

	    $table->check();

        $table->store();

        if (empty( $table->alias )) {

            $table->alias = JApplicationHelper::stringURLSafe($table->title);

        } else {

            $table->alias = JApplicationHelper::stringURLSafe($table->alias);

        }

        $input = JFactory::getApplication()->input;

        $selected_options = $input->get('options', array(), 'ARRAY');
        $table->options = json_encode($selected_options);

        $images = $input->files->get('pics');

        if ($images) {

            $count = count($images);

            for ($i = 0; $i < $count; $i++) {

                if (isset($images[$i]) && !empty($images[$i]['name'])) {

                    jimport('joomla.filesystem.file');

                    $filename = $images[$i]['name'];
                    $ext = JFile::getExt($filename);
                    //$name = JFile::stripExt($filename);
                    $newfilename = $table->id . "_" . uniqid() . "." . $ext;

                    $src = $images[$i]['tmp_name'];
                    $path = JPATH_SITE . '/images/products/';
                    $dest = $path . $newfilename;

                    JFile::upload($src, $dest);

                    $this->_db->setQuery("insert into #__z_shop_images (file_name, id_product) values ('$newfilename', '$table->id')");
                    $this->_db->execute();

                }

            }

        }

    }

	protected function loadFormData() {

		$data = $this->getItem();

		return $data;
	}

	public function getItem($pk = null) {

		if ($item = parent::getItem($pk)) {

		    if($item->id !== null) {
                $this->_db->setQuery("select * from #__z_shop_images where id_product = $item->id");
                $item->pics=$this->_db->loadObjectList();
            }

            return $item;

		}

		return false;

	}

    public function delete (&$pks) {

	    jimport('joomla.filesystem.file');

        for($i=0; $i < count($pks); $i++){

            $this->_db->setQuery("select * from #__z_shop_images where id_product =" . $pks[$i]);
            $this->_db->execute();
            $images = $this->_db->loadObjectList();

            if ($images) {

                for ($j=0; $j < count($images); $j++) {

                    $path = JPATH_SITE . '/images/products/';

                    if (JFile::exists($path . $images[$j]->file_name)) JFile::delete($path . $images[$j]->file_name);

                }

                $this->_db->setQuery("delete from #__z_shop_images where id_product =" . $pks[$i]);
                $this->_db->execute();

            }
        }

        parent::delete($pks);

    }

    public function savelist(&$ordering)
    {
    $table         = $this->getTable();
    $pks           = (array) $pks;

    JPluginHelper::importPlugin($this->events_map['save']);

    // Access checks.
    foreach ($ordering as $i => $val)
    {
      if ($table->load($i))
      {

        // Skip changing of same state
        if ($table->ordering == $val)
        {
          unset($ordering[$i]);
          continue;
        }

        $table->ordering = (int) $val;

        // Allow an exception to be thrown.
        try
        {
          if (!$table->check())
          {
            $this->setError($table->getError());

            return false;
          }


          // Store the table.
          if (!$table->store())
          {
            $this->setError($table->getError());

            return false;
          }

        }
        catch (Exception $e)
        {
          $this->setError($e->getMessage());

          return false;
        }
      }
    }

    return true;
    }
}