<?php

defined('_JEXEC') or die;

JLoader::register('OptimizeHelper', JPATH_ADMINISTRATOR . '/components/com_plants/helpers/optimize.php');


class PlantsModelUser extends JModelAdmin
{
    private $user_id;

	public function getForm( $data = array(), $loadData = true )
    {
		$form = $this->loadForm( $this->option . 'user', 'user', [ 'control'   => 'jform',
		                                                           'load_data' => $loadData
		] );

		if ( empty( $form ) )
		{
			return false;
		}
		return $form;
	}

	public function getTable($type = 'User', $prefix = 'PlantsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function block($pks)
	{
		if(!empty($pks)){

			$ids = implode(',', $pks);

			$query = $this->_db->getQuery(true);
			$query->update('#__users')->set('block = 0')->where('id in ('.$ids.')');
			$this->_db->setQuery($query)->execute();

			return true;
		}
	}

	public function unblock($pks)
	{
		if(!empty($pks)){
			$ids = implode(',', $pks);

			$query = $this->_db->getQuery(true);
			$query->update('#__users')->set('block = 1')->where('id in ('.$ids.')');
			$this->_db->setQuery($query)->execute();

			return true;
		}
	}

	public function prepareTable( $table )
	{
		$query = $this->_db->getQuery(true);

		$query->update('#__users');

		if($table->name) {
			$query->set('name ='.$this->_db->quote($table->name));
			$query->where('id ='.$table->juser_id);
		}

		if($table->username) {
			$query->set('username ='.$this->_db->quote($table->username));
			$query->where('id ='.$table->juser_id);
		}

		if($table->password) {

		    $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword($table->password, $salt);
            $password = $crypt . ':' . $salt;

			$query->set('password = ' . $this->_db->quote($password));
			$query->where('id = ' . $table->juser_id);
		}

		if($table->email) {
			$query->set('email ='.$this->_db->quote($table->email));
			$query->where('id ='.$table->juser_id);
		}

		$this->_db->setQuery($query)->execute();

		$filename = $this->update_profile_photo($table->juser_id);

		/*
		 * Check filename. If filename not empty, we are delete file from directory
		 * and add new file name to arr $table
		*/

		if(!empty($filename))
		{
			jimport('joomla.filesystem.file');
			JFile::delete(JPATH_ROOT.'/images/user_photos/'.$table->photo);

			$table->photo = $filename;
		}
	}


	public function update_profile_photo($id)
	{
		$input = JFactory::getApplication()->input;
		$file = $input->files->get('jform');

		if($file['photo']['size'])
		{
			$filename = uniqid() . $file['photo']['name'];
			$tmp = $file['photo']['tmp_name'];
			$folder = 'images/user_photos/';

			$query = $this->_db->getQuery(true);

			$query->update('#__z_users');

			OptimizeHelper::optimizePhoto($tmp, 500, 500, $folder, $filename);

			$query->set('photo = '.$this->_db->quote($filename));
			$query->where('juser_id = '.$id);

			$this->_db->setQuery($query)->execute();

			return $filename;
		}

        return false;
	}


    /**
     * @param array $pks
     * @param null $joomla_user
     * @return bool|void
     * joomla_user = true [ delete user from #__users table ]
     */
    public function delete (&$pks, $joomla_user = true)
    {
        jimport('joomla.filesystem.file');

        foreach ($pks as $pk)
        {
            $this->deleteUserPhoto($pk);
        }

        $query = $this->_db->getQuery(true);
        $query->delete();
        $query->from('#__z_users');
        $query->where('juser_id IN (' . implode(',',$pks) . ')');
        $this->_db->setQuery($query)->execute();

        $this->deleteUserDep($pks);

        //delete user from joomla table
        if($joomla_user)
            $this->deleteJoomlaUser($pks);
    }

    //delete user dependencies
    private function deleteUserDep($pks)
    {
        $ids = implode(',', $pks);

        $query = $this->_db->getQuery(true);
        $query->select('plant_id');
        $query->from('#__z_plants_user_plants');
        $query->where("user_id in ($ids)");

        $this->_db->setQuery($query);
        $plant_ids = $this->_db->loadObjectList();

        $plant_ids_str = '0';
        foreach ($plant_ids as $item)
            $plant_ids_str .= ',' . $item->plant_id;

        //delete plants from dependence table
        $query->clear();
        $query->delete('#__z_plants_user_plants');
        $query->where("plant_id in ($plant_ids_str)");
        $this->_db->setQuery($query);
        $this->_db->execute();

        //delete plants
        $query->clear();
        $query->delete('#__z_plants_plants');
        $query->where("id in ($plant_ids_str)");
        $this->_db->setQuery($query);
        $this->_db->execute();

        //delete plant photos
        $query->clear();
        $query->delete('#__z_plants_plant_photos');
        $query->where("plant_id in ($plant_ids_str)");
        $this->_db->setQuery($query);
        if($this->_db->execute())
            $this->deletePhotoFiles($plant_ids_str);

        //delete plant fields values
        $query->clear();
        $query->delete('#__z_plants_field_values');
        $query->where("plant_id in ($plant_ids_str)");
        $this->_db->setQuery($query);
        $this->_db->execute();

        //delete plant comments
        $query->clear();
        $query->delete('#__z_plants_comments');
        $query->where("plant_id in ($plant_ids_str)");
        $this->_db->setQuery($query);
        $this->_db->execute();
    }

	protected function loadFormData()
	{
		$user = $this->getItem();
		$this->user_id = $user->juser_id;

		return $user;
	}

	private function deletePhotoFiles($ids)
    {
        $query = $this->_db->getQuery(true);
        $query->select('src');
        $query->from('#__z_plants_plant_photos');
        $query->where("plant_id in ($ids)");
        $this->_db->setQuery($query);
        $photos = $this->_db->loadObjectList();

        foreach ($photos as $photo)
        {
            JFile::delete(JPATH_ROOT . '/images/plants/' . $photo->src);
        }

        $query->clear();
        $query->select('seeds_photo, barcode_photo');
        $query->from('#__z_plants_plants');
        $query->where("id in ($ids)");
        $this->_db->setQuery($query);
        $photos = $this->_db->loadObjectList();

        foreach ($photos as $photo)
        {
            JFile::delete(JPATH_ROOT . '/images/seeds_photo/' . $photo->seeds_photo);
            JFile::delete(JPATH_ROOT . '/images/barcodes_photo/' . $photo->barcode_photo);
        }
    }

    private function deleteUserPhoto($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('photo');
        $query->from('#__z_users');
        $query->where('juser_id = ' . $id);
        $this->_db->setQuery($query);
        $photo = $this->_db->loadResult();

        if($photo)
            JFile::delete(JPATH_ROOT . '/images/user_photos/' . $photo);
    }

    public function getMessage()
    {
        $query = $this->_db->getQuery(true);
        $query->select('message');
        $query->from('#__z_user_requests');
        $query->where('user_id = ' . $this->user_id);
        $this->_db->setQuery($query);
        $message = $this->_db->loadResult();

        return $message;
    }

    private function deleteJoomlaUser($pks)
    {
        JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_users/models', 'UsersModel');
        $model = JModelLegacy::getInstance('User', 'UsersModel');

        return $model->delete($pks);
    }
}