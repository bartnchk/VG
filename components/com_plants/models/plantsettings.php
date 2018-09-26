<?php

defined('_JEXEC') or die;

class PlantsModelPlantsettings extends JModelForm
{
    private $app;
    private $plant_id;

    /**
     * PlantsModelPlantsettings constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->app = JFactory::getApplication();
        $this->plant_id = $this->app->input->get('id', false, 'int');

        if($this->plant_id)
            $this->checkAccessToPlant($this->plant_id);

        if($this->getUser()->guest && $this->plant_id)
            JFactory::getApplication()->redirect('/login');
    }



    /**
     * @param $plant_id
     *
     */
    private function checkAccessToPlant($plant_id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('COUNT(plant_id)');
        $query->from('#__z_plants_user_plants');
        $query->where( 'plant_id = ' . (int)$plant_id );
        $query->where( 'user_id = ' . JFactory::getUser()->id );

        $this->_db->setQuery($query);

        if( !$this->_db->loadResult() )
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
    }



    /**
     * @param array $data
     * @param bool $loadData
     * @return bool|JForm
     * @throws Exception
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_plants.plantsettings',
            'plantsettings',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        $plant_id = $this->getPlantId();

        //load form data
        if($plant_id)
        {
            $plant_data = $this->getPlantData($plant_id);

            foreach ($plant_data as $k => $data)
            {
                if($data)
                    $form->setValue($k,'', $data);
            }
        }

        if (empty($form))
            return false;
        else
        {
            $this->addCustomFields($form);
            return $form;
        }
    }



    /**
     * @return JUser
     */
    private function getUser()
    {
        return JFactory::getUser();
    }



    /**
     * @param $formData
     * @return mixed
     * save plant data
     */
    public function savePlantData($formData)
    {
        $custom_fields = false;

        if( $formData['seeds_photo']['size'] )
            $formData['seeds_photo'] = $this->getPhotoName($formData['seeds_photo'],'seeds');
        else
            unset($formData['seeds_photo']);

        if ($formData['barcode_photo']['size'] )
            $formData['barcode_photo'] = $this->getPhotoName($formData['barcode_photo'], 'barcode');
        else
            unset($formData['barcode_photo']);

        $formData['planting_date'] = $this->dateFormat($formData['planting_date']);
        $formData['transplantation_date'] = $this->dateFormat($formData['transplantation_date']);

        $query = $this->_db->getQuery(true);

        if(!$formData['id'])
        {
            $query->insert('#__z_plants_plants');
        }
        else
        {
            $query->update('#__z_plants_plants');
            $query->where('id = ' . $this->_db->quote($formData['id']));
        }


        if( isset($formData['custom_fields']) && !empty($formData['custom_fields']) )
            $custom_fields = $formData['custom_fields'];

        unset($formData['custom_fields']);

        foreach ($formData as $k => $value)
        {
            $query->set($this->_db->quoteName($k) . ' = ' . $this->_db->quote($value));
        }

        $this->_db->setQuery($query);
        $this->_db->execute();

        if ($formData['id'] )
            $plant_id = $formData['id'];
        else
            $plant_id = $this->_db->insertid();

        $this->saveCustomFieldsData( $custom_fields,  $plant_id);

        return $plant_id;
    }



    /**
     * @param $data - custom fields array
     * @param $plant_id - plant id
     * save custom fields data
     *
     * @since version
     */
    private function saveCustomFieldsData($data, $plant_id)
    {
        $query = $this->_db->getQuery(true);

        //delete old data
        $query->delete('#__z_plants_field_values');
        $query->where('plant_id = ' . $plant_id);
        $this->_db->setQuery($query);
        $this->_db->execute();

        //save new data
        $query->clear();

        if($data)
        {
            foreach ($data as $k => $item)
            {
                $query->insert('#__z_plants_field_values');
                $query->set('field_id = ' . $k);
                $query->set('value = ' . $this->_db->quote($item));
                $query->set('plant_id = ' . $plant_id);
                $this->_db->setQuery($query);
                $this->_db->execute();
                $query->clear();
            }
        }
    }



    /**
     * crop photo and add watermark
     * return filename
     *
     * @since version
     */
    public function getPhotoName($photo, $type = FALSE)
    {
        JLoader::register('OptimizeHelper', JPATH_ADMINISTRATOR . '/components/com_plants/helpers/optimize.php');

        $info = new \SplFileInfo($photo['name']);
        $hash = uniqid(time(), true);

        $filename = "{$hash}." . $info->getExtension();
        $filename2 = NULL;

        $width  = 1024;
        $height = 768;

        $tmp = $photo['tmp_name'];

        $wtm_src = JPATH_SITE . '/images/watermarks/orig45.png';
        $watermark = array(
            'src'          => $wtm_src,
            'transparency' => 50,
            'bottomMargin' => 20,
            'rightMargin'  => 20,
        );

        if($type == 'seeds')
        {
            $folder = 'images/seeds_photo/';
        }
        elseif($type == 'barcode')
        {
            $folder = 'images/barcodes_photo/';
        }
        else
        {
            //$watermark['src'] = JPATH_SITE . '/images/watermarks/watermark01.gif';
            $folder = 'images/plants/';
            $filename2 = 'wide_' . $filename;
            OptimizeHelper::optimizePhoto( $tmp, $width, $height, $folder, $filename2, $watermark );
            $width  = 260;
            $height = 260;
        }

        OptimizeHelper::optimizePhoto( $tmp, $width, $height, $folder, $filename, $watermark );

        return $filename;
    }



    /**
     * @return plant_id
     * @throws Exception
     */
    public function getPlantId()
    {
        $input = $this->app->input;

        $plant_id = $input->get('id', '', 'int');

        if($plant_id)
            return $plant_id;

        return false;
    }



    /**
     * @return plant data by id
     */
    public function getPlantData()
    {
        if($this->plant_id)
        {
            $query = $this->_db->getQuery(true);

            $query->select('p.*, concat(sc.name_en, ", ", _sc.name_en) as city');
            $query->from('#__z_plants_plants p');
            $query->leftJoin('#__sxgeo_cities sc ON sc.id = p.city_id');
            $query->leftJoin('#__sxgeo_regions sr ON sr.id = sc.region_id');
            $query->leftJoin('#__sxgeo_country _sc on _sc.iso = sr.country');
            $query->where("p.id = $this->plant_id");

            $this->_db->setQuery($query);
            $result = $this->_db->loadAssoc();

            if( empty($result) )
                throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);

            return $result;
        }
    }



    public function getPlantPhotos()
    {
        if($this->plant_id)
        {
            $query = $this->_db->getQuery(true);

            $query->select('*')
                ->from('#__z_plants_plant_photos')
                ->where("plant_id = $this->plant_id");

            $this->_db->setQuery($query);
            return $this->_db->loadObjectList();
        }
    }



    /**
     * @param $plant_id
     * check association
     */
    public function checkPlant($plant_id)
    {
        $query = $this->_db->getQuery( true );

        $query->select( '*' );
        $query->from( '#__z_plants_user_plants' );
        $query->where( 'plant_id = ' . $plant_id );
        $query->andWhere( 'user_id = ' . $this->getUser()->id );

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }



    /**
     * @param $id
     * associate plant with user
     */
    public function savePlant($id)
    {
        if($id && !$this->getUser()->guest)
        {
            $query = $this->_db->getQuery( true );
            $query->insert( '#__z_plants_user_plants' );
            $query->set( 'user_id = ' . $this->getUser()->id );
            $query->set( 'plant_id = ' . $id );

            $this->_db->setQuery( $query );
            $this->_db->execute();
        }
    }



    /**
     * @param $photos
     * @param $plant_id
     */
    public function savePlantPhotos($photos, $plant_id)
    {
        foreach ($photos as $photo)
        {
            $filename = $this->getPhotoName($photo);

            $query = $this->_db->getQuery(true);
            $query->insert('#__z_plants_plant_photos');
            $query->set('src = ' . $this->_db->quote($filename));
            $query->set("plant_id = $plant_id");

            $this->_db->setQuery($query)->execute();
        }
    }



    /**
     * @param $filename
     * @param $type (photo, barcode, seeds)
     */
    public function deletePlantsPhotos($filename, $type)
    {
        if(!$this->checkAccessToPhoto($filename, $type))
            return;

        if($filename)
        {
            $query = $this->_db->getQuery(true);

            if($type == 'photo')
            {
                $query->delete('#__z_plants_plant_photos');
                $query->where('src = ' .$this->_db->quote($filename));
            }
            else if($type == 'seeds')
            {
                $query->update('#__z_plants_plants');
                $query->set('seeds_photo = NULL');
                $query->where('seeds_photo = ' . $this->_db->quote($filename));
            }
            else if($type == 'barcode')
            {
                $query->update('#__z_plants_plants');
                $query->set('barcode_photo = NULL');
                $query->where('barcode_photo = ' . $this->_db->quote($filename));
            }

            $this->_db->setQuery($query);

            //delete file
            if($this->_db->execute())
            {
                jimport('joomla.filesystem.file');

                if($type == 'photo')
                {
                    JFile::delete(JPATH_ROOT. '/images/plants/' . $filename);
                    JFile::delete(JPATH_ROOT. '/images/plants/wide_' . $filename);
                }
                else if($type == 'seeds')
                    JFile::delete(JPATH_ROOT. '/images/seeds_photo/' . $filename);
                else if($type == 'barcode')
                    JFile::delete(JPATH_ROOT. '/images/barcodes_photo/' . $filename);
            }
        }
    }



    /**
     * @return bool
     * is user have access to photo
     */
    private function checkAccessToPhoto($filename, $type)
    {
        $user_id = $this->getUser()->id;

        if ($user_id && $type)
        {
            $query = $this->_db->getQuery(true);
            $query->select('p.*');

            if($type == 'photo')
            {
                $query->from('#__z_plants_plant_photos p');
                $query->innerJoin('#__z_plants_user_plants up ON up.plant_id = p.plant_id');
                $query->where( 'p.src = ' . $this->_db->quote($filename) );
            }
            else if($type == 'seeds')
            {

                $query->from('#__z_plants_plants p');
                $query->innerJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
                $query->where( 'p.seeds_photo = ' . $this->_db->quote($filename) );
            }
            else if($type == 'barcode')
            {

                $query->from('#__z_plants_plants p');
                $query->innerJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
                $query->where( 'p.barcode_photo = ' . $this->_db->quote($filename) );
            }

            $this->_db->setQuery($query);

            if($this->_db->loadResult())
                return true;

            return false;
        }
    }



    /**
     * @param $id
     * @throws Exception
     */
    public function deletePlant($id)
    {
        if(!$this->getUser()->guest && $this->checkPlant($id))
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
                $query->where('plant_id =' .$id);

                $this->_db->setQuery($query);
                $this->_db->execute();
                $query->clear();

                $query->delete('#__z_plants_field_values');
                $query->where('plant_id =' .$id);

                $this->_db->setQuery($query);
                if($this->_db->execute())
                {
                    //TODO add constant to lang lib
                    $this->app->enqueueMessage('COM_PLANTS_SITE_DELETE_ITEM');
                }
                else
                {
                    //TODO add constant to lang lib
                    $this->app->enqueueMessage('COM_PLANTS_SITE_DELETE_ITEM_FAILED', 'error');
                }
            }
        }
        else{
            //TODO add constant to lang lib
            $this->app->enqueueMessage('COM_PLANTS_SITE_DELETE_ITEM_EMPTY' , 'error');
        }
    }



    /**
     * Add custom fields to form
     * @since
     */
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



    /**
     * @return mixed
     */
    public function getLastAddedPlants()
    {
        $query = $this->_db->getQuery(true);

        $query->select('pp.src, p.id');
        $query->from('#__z_plants_plants p');
        $query->leftJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
        $query->where('p.published = 1');
        $query->order('p.created_at DESC');
        $query->setLimit(12);
        $query->group('p.id');

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }



    /**
     * @param $user_query
     * @return mixed
     * return cities by ajax request
     */
    public function getCities($user_query)
    {
        if(trim($user_query) !== '')
        {
            $query = $this->_db->getQuery(true);
            $query->select('sc.id as value, concat(sc.name_en, ", ", _sc.name_en) as text');
            $query->from('#__sxgeo_cities sc');
            $query->leftJoin('sxgeo_regions sr ON sr.id = sc.region_id');
            $query->leftJoin('sxgeo_country _sc on _sc.iso = sr.country');
            $query->where('sc.name_en LIKE ' . $this->_db->quote($user_query.'%'));
            $query->setLimit(50);

            $this->_db->setQuery($query);
            return $this->_db->loadObjectList();
        }
    }



    /**
     * @return custom fields and custom fields values
     */
    public function getCustomFields()
    {
        if($this->plant_id)
        {
            $query = $this->_db->getQuery(true);
            $query->select('fv.field_id as id, fv.value, f.name as title');
            $query->from('#__z_plants_field_values fv');
            $query->innerJoin('#__z_plants_fields f ON f.id = fv.field_id');
            $query->where("fv.plant_id = $this->plant_id");

            $this->_db->setQuery($query);
            return $this->_db->loadObjectList();
        }
    }



    /**
     * @param $date
     * @return false|string
     */
    public function dateFormat($date)
    {
        if(!$date)
            return '';
        $timestamp = strtotime($date);
        return date('Y-m-d', $timestamp);
    }



    /**
     * @param $email
     * @return bool
     */
    public function isUserIsset($email)
    {
        $query = $this->_db->getQuery(true);
        $query->select('COUNT(*)');
        $query->from('#__z_users');
        $query->where( 'username = ' . $this->_db->quote($email) );
        $this->_db->setQuery($query);

        if( $this->_db->loadResult() )
            return true;
        else
            return false;
    }



    /**
     * @param $email
     * @param $password
     */
    public function login($email, $password)
    {
        $credentials['username']  = $email;
        $credentials['password']  = $password;

        $options['remember'] = false;
        $options['return']   = '';

        if( $this->app->login($credentials, $options) )
            return true;
        else
            return false;
    }



    /**
     * @param $plant_id
     * @param $user_id
     * assign plant with user
     */
    public function assignPlantToUser($plant_id, $user_id)
    {
        if($plant_id && $user_id)
        {
            $query = $this->_db->getQuery(true);
            $query->insert('#__z_plants_user_plants');
            $query->set( 'user_id = ' . $this->_db->quote($user_id) );
            $query->set( 'plant_id = ' . $this->_db->quote($plant_id) );

            $this->_db->setQuery($query);
            $this->_db->execute();
        }
    }



    /**
     * @param $photos
     * @param $plant_id
     * clear old plant photos when plant data update
     */
    public function clearOldData($photos, $plant_id)
    {
        jimport('joomla.filesystem.file');

        //delete seeds photo
        if( $photos['seeds']['size'] )
        {
            $query = $this->_db->getQuery(true);
            $query->select('seeds_photo')
                  ->from('#__z_plants_plants')
                  ->where("id = $plant_id");

            $src = $this->_db->setQuery($query)->loadResult();
            JFile::delete(JPATH_ROOT. '/images/seeds_photo/' . $src);
        }

        //delete barcode photo
        if( $photos['barcode']['size'] )
        {
            $query = $this->_db->getQuery(true);
            $query->select('barcode_photo')
                ->from('#__z_plants_plants')
                ->where("id = $plant_id");

            $src = $this->_db->setQuery($query)->loadResult();

            JFile::delete(JPATH_ROOT. '/images/barcodes_photo/' . $src);
        }
    }


    /**
     * @param $user_id
     * @return bool
     * Send email to administrator about new added plant
     */
    public function sendAdminNotify($user_id)
    {
        if($user_id)
        {
            $params = JComponentHelper::getParams('com_plants');
            $email = $params->get('site_email');

            $user = JFactory::getUser($user_id);

            $message = $user->name . ' add new plant on site';

            $mailer = JFactory::getMailer();
            $config = JFactory::getConfig();

            $mailer->setFrom($email);
            $mailer->addRecipient( $config->get('mailfrom') );
            $mailer->setSubject('New Plant Added');
            $mailer->isHtml(true);

            $mailer->setBody($message);
            $mailer->Send();
        }

        return true;
    }



    /**
     * Send email to user about successfully added plant
     * @param $user
     */
    public function sendUserNotify($user_id)
    {
        if($user_id)
        {
            $params = JComponentHelper::getParams('com_plants');
            $email = $params->get('site_email');

            $mailer = JFactory::getMailer();
            $user = JFactory::getUser($user_id);

            $message = 'Thank\'s for add plant';
            $mailer->setFrom($email);
            $mailer->addRecipient( $user->email );
            $mailer->setSubject('Your Plant');
            $mailer->isHtml(true);

            $mailer->setBody($message);
            $mailer->Send();
        }

        return true;
    }



    /**
     * @param $data
     * Save plant data to SESSION
     */
    public function dataToSession($data)
    {
        $session = JFactory::getSession();
        $session->set('jform', $data);
    }



    /**
     * return plant categories
     */
    public function getCategories()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_plant_category');
        $query->where('state = 1');
        $query->order('id DESC');
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }



    /**
     * return plant types
     */
    public function getTypes()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_plant_types');
        $query->where('state = 1');

        if($this->plant_id)
            $query->where('category_id = ' . (int)$this->getPlantCategory());

        $query->order('title ASC');
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }



    /**
     * return plant category id
     */
    private function getPlantCategory()
    {
        $query = $this->_db->getQuery(true);
        $query->select('plant_category_id');
        $query->from('#__z_plants_plants');
        $query->where('id = ' . (int)$this->plant_id);
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }
}