<?php

defined('_JEXEC') or exit();

JLoader::register('OptimizeHelper', JPATH_ADMINISTRATOR . '/components/com_plants/helpers/optimize.php');


class PlantsModelPlant extends JModelAdmin {

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_plants.plant',
            'plant',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        $form = $this->addCustomFields($form);
        $this->loadNotification($form);

        if (empty($form))
            return false;
        else
            return $form;
    }

    private function loadNotification($form)
    {
        $plant_id = $form->getValue('id');

        if($plant_id)
        {
            $query = $this->_db->getQuery(true);
            $query->select('message');
            $query->from('#__z_plants_notifications');
            $query->where( 'plant_id = ' .  (int)$plant_id);
            $this->_db->setQuery($query);
            $message = $this->_db->loadResult();

            if($message)
                $form->setValue('message', '', $message);
        }
    }

    public function prepareTable($table)
    {
        $input = JFactory::getApplication()->input;
        $jform = $input->get('jform', array(), 'array');
        $message = trim($jform['message']);

        //if notification don't sends
        if($table->published !== 2 && $this->checkNotification($table->id, $message) )
        {
            $this->createNotification($message, $table->id);
            $this->sendNotification($table->id, $table->published);
//            $this->changeNotificationStatus($table->id);
        }

        $table->city_id = $input->get('city_id');

        $table->check();
        $table->store();

        if (!$table->alias)
            $table->alias = JFilterOutput::stringURLSafe($table->sort_name);

        if(strstr($table->seeds_photo, 'images/'))
            OptimizeHelper::optimizePhoto($table->seeds_photo, 500, 300);
        if(strstr($table->barcode_photo, 'images/'))
            OptimizeHelper::optimizePhoto($table->barcode_photo, 500, 300);

        $this->saveCustomFieldsData($table->id);

        if($table->top_plant)
        {
            $this->_db->setQuery('UPDATE #__z_plants_plants SET top_plant = 0')->execute();
        }
    }



    /**
     * @param $id
     * set notification status [ is_read = 1 ]
     */
    private function changeNotificationStatus($id)
    {
        $query = $this->_db->getQuery(true);
        $query->update('#__z_plants_notifications');
        $query->set('is_read = 1');
        $query->where('plant_id = ' . $id);
        $this->_db->setQuery($query);
        $this->_db->execute();
    }



    /**
     * @param $plant_id
     * @param $published
     * Send email notification to user
     */
    private function sendNotification($plant_id, $published)
    {
        if($published == 0 || $published == 1)
        {
            $params = JComponentHelper::getParams('com_plants');
            $email = $params->get('site_email');

            $query = $this->_db->getQuery(true);
            $query->select('u.email, n.message');
            $query->from('#__z_plants_notifications n');
            $query->leftJoin('#__z_plants_user_plants up ON up.plant_id = ' . (int)$plant_id);
            $query->leftJoin('#__users u ON u.id = up.user_id');
            $query->where('n.plant_id = ' . (int)$plant_id);
            $query->where('n.is_read = ' . 0);
            $this->_db->setQuery($query);
            $data = $this->_db->loadObject();

            if ($published == 1)
                $status = 'approved';
            elseif ($published == 0)
                $status = 'rejected';

            $message = 'The publication of your plant has been ' . $status . '.<br><br>';

            if($data->message)
                $message .= nl2br($data->message);

            $mailer = JFactory::getMailer();
            $mailer->setFrom($email);
            $mailer->addRecipient( $data->email );
            $mailer->setSubject('Your plant');
            $mailer->isHtml(true);

            $mailer->setBody($message);
            $mailer->Send();
        }
    }



    /**
     * @param $message
     * @param $plant_id
     * Create user notification about plant publish
     */
    public function createNotification($message, $plant_id)
    {
        $query = $this->_db->getQuery(true);

        $query->delete('#__z_plants_notifications');
        $query->where('plant_id = ' . (int)$plant_id);
        $this->_db->setQuery($query);
        $this->_db->execute();

        $query->clear();
        $query->insert('#__z_plants_notifications');
        $query->set( 'plant_id = ' . (int)$plant_id );
        if($message)
            $query->set( 'message = ' . $this->_db->quote($message) );
        $this->_db->setQuery($query);
        $this->_db->execute();

        return true;
    }



    /**
     * @param $message
     * @param $plant_id
     * @return notification status
     * if new notification or notification edited - return false
     */
    public function checkNotification($plant_id, $message)
    {
        $query = $this->_db->getQuery(true);
        $query->select('COUNT(id)');
        $query->from('#__z_plants_notifications');
        $query->where('plant_id = ' . (int)$plant_id);
        $query->where('message = ' . $this->_db->quote($message));

        $this->_db->setQuery($query);

        if( $this->_db->loadResult() )
            return false;
        else
            return true;
    }

    public function getTable($type = 'Plant', $prefix = 'PlantsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function delete (&$pks)
    {
        foreach ($pks as $id)
            $this->deletePlant($id);

        parent::delete($pks);
    }


    public function deletePlant($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('pl.seeds_photo, pl.barcode_photo, ph.src');
        $query->from('#__z_plants_plants as pl');
        $query->leftJoin('#__z_plants_plant_photos as ph on pl.id = ph.plant_id');
        $query->where('id = ' . $id);

        $this->_db->setQuery($query);
        $files = $this->_db->loadObjectList();

        if($files)
        {
            jimport('joomla.filesystem.file');

            foreach ($files as $photo)
            {
                if($photo->seeds_photo)
                {
                    JFile::delete(JPATH_ROOT. '/images/seeds_photo/'.$photo->seeds_photo);
                }
                if($photo->barcode_photo)
                {
                    JFile::delete(JPATH_ROOT. '/images/barcode_photo/'.$photo->barcode_photo);
                }
                if($photo->src)
                {
                    JFile::delete(JPATH_ROOT. '/images/plants/'.$photo->src);
                }
            }
        }

        $query->clear();
        $query->delete('#__z_plants_plants');
        $query->where('id =' .$id);
        $this->_db->setQuery($query);

        if($this->_db->execute())
        {
            $query->clear();

            $query->delete('#__z_plants_plant_photos');
            $query->where('plant_id =' .$id);

            $this->_db->setQuery($query);
            $this->_db->execute();
            $query->clear();

            $query->delete('#__z_plants_comments');
            $query->where('plant_id = ' . $id);

            $this->_db->setQuery($query);
            $this->_db->execute();
            $query->clear();

            $query->delete('#__z_plants_field_values');
            $query->where('plant_id = ' . $id);

            $this->_db->setQuery($query);
            $this->_db->execute();
        }

    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $city = $this->getCityName($item->city_id);
        $item->set('city', $city);

        return $item;
    }

    public function getCityName($id)
    {
        return $this->_db->setQuery('SELECT name_en FROM #__sxgeo_cities WHERE id = ' . (int)$id)->loadResult();
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }

    protected function saveCustomFieldsData($plant_id)
    {
        $input = JFactory::getApplication()->input;
        $formData = new JInput($input->get('jform', '', 'array'));
        $custom_fields = $formData->get('custom_fields', null, 'RAW');

        if($custom_fields)
        {
            $query = $this->_db->getQuery(true);

            //clear old data
            $query->delete('#__z_plants_field_values');
            $query->where('plant_id = ' . $plant_id);
            $this->_db->setQuery($query)->execute();
            $query->clear();

            //insert data
            foreach($custom_fields as $k => $val)
            {
                $query->insert('#__z_plants_field_values');
                $query->set('plant_id = ' . $plant_id . ', value = ' . $this->_db->quote($val) . ', field_id = ' . $k);
                $this->_db->setQuery($query)->execute();
                $query->clear();
            }
        }
    }

    protected function addCustomFields($form)
    {
        $plant_id = $form->getValue('id');

        if(!$plant_id)
            return $form;
        else
        {
            $query = $this->_db->getQuery(true);

            $query->select('v.value, f.name, f.id');
            $query->from('#__z_plants_field_values AS v');
            $query->rightJoin('#__z_plants_fields f ON f.id = v.field_id');
            $query->where('v.plant_id = ' . $plant_id);

            $this->_db->setQuery($query);
            $fields = $this->_db->loadObjectList();

            if ($fields)
            {
                foreach ($fields as $item)
                {
                    $xml = '
                    <fieldset name="custom">
                        <field
                            name="custom_fields][' . $item->id. '"
                            type="text"
                            class="inputbox"
                            label="' . $item->name . '" />
                    </fieldset>';

                    $element = new SimpleXMLElement($xml);
                    $form->setField($element);
                    $form->setValue('custom_fields][' . $item->id, '', $item->value);
                }
            }

            return $form;
        }
    }

    public function getPlantPhotos()
    {
        $input = JFactory::getApplication()->input;
        $id = $input->get('id');

        $query = $this->_db->getQuery(true);
        $query->select('src');
        $query->from('#__z_plants_plant_photos');
        $query->where('plant_id = ' . $id);

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function deletePhoto($src)
    {
        $query = $this->_db->getQuery(true);

        $query->delete();
        $query->from('#__z_plants_plant_photos');
        $query->where('src = ' . $this->_db->quote($src));

        $this->_db->setQuery($query);

        if($this->_db->execute())
        {
            jimport('joomla.filesystem.file');
            JFile::delete(JPATH_ROOT . '/images/plants/' . $src);

            return true;
        }

        return false;
    }
}