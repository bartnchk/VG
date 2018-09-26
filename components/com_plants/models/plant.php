<?php

defined('_JEXEC') or die;
jimport( 'joomla.application.component.modelform' );

class PlantsModelPlant extends JModelAdmin {

    private $item_id;
    private $app;

    public function __construct( array $config = array() )
    {
        parent::__construct( $config );

        $this->app = JFactory::getApplication()->input;
        $this->item_id = $this->app->get( 'id', '', 'int' );

        if(!$this->item_id)
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);

        $this->viewCounter();
    }

    public function getPlant()
    {
        $query = $this->_db->getQuery(true);

        $query->select('p.*, c.alias as category_alias, c.title as category, up.user_id as user_id, u.first_name, pt.title as type, concat(sc.name_en, ", ", _sc.name_en) as city');
        $query->from('#__z_plants_plants p');
        $query->innerJoin('#__z_plants_plant_category c ON p.plant_category_id = c.id');
        $query->leftJoin('#__z_plants_user_plants up ON p.id = up.plant_id');
        $query->leftJoin('#__sxgeo_cities sc ON sc.id = p.city_id');
        $query->leftJoin('#__sxgeo_regions sr ON sr.id = sc.region_id');
        $query->leftJoin('#__sxgeo_country _sc on _sc.iso = sr.country');
        $query->leftJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->leftJoin('#__z_plants_plant_types pt ON pt.id = p.plant_type_id');
        $query->where('p.id = '. $this->item_id);
        $query->where('(p.published = 1 OR u.juser_id = ' . $this->getUser()->id . ')');

        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();

        if( empty($result) )
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);

        $query->clear();
        $query->select('pf.name, pfv.value');
        $query->from('#__z_plants_field_values pfv');
        $query->innerJoin('#__z_plants_fields pf ON pf.id = pfv.field_id');
        $query->where('pfv.plant_id = ' . (int)$result->id);

        $this->_db->setQuery($query);
        $result->custom_fields = $this->_db->loadObjectList();

        if($result)
            return $result;
        else
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
    }

    public function getPhotos()
    {
        $query = $this->_db->getQuery(true);

        $query->select('src');
        $query->from('#__z_plants_plant_photos');
        $query->where('plant_id = '. $this->item_id);

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

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

        if ( empty($form) )
        {
            return false;
        }

        return $form;
    }

    public function saveComment($comment)
    {
        if (!$this->getUser()->guest)
        {
            if (!empty($comment))
            {
                $query = $this->_db->getQuery(true);

                $query->insert('#__z_plants_comments');
                $query->set('user_id = ' . $this->getUser()->id);
                $query->set('plant_id = ' . $this->item_id);
                $query->set($this->_db->quoteName('comment') . ' = ' . $this->_db->quote($comment));
                $query->set('created_at = now()');

                $this->_db->setQuery($query)->execute();
                return $this->item_id;
            }
        }
    }

    public function getComments()
    {
        $query = $this->_db->getQuery(true);
        $query->select('c.*, u.first_name, u.last_name, u.photo as user_photo');
        $query->from('#__z_plants_comments c');
        $query->innerJoin('#__z_users u ON u.juser_id = c.user_id');
        $query->order('c.created_at DESC');
        $query->where("plant_id = $this->item_id");
        $query->andWhere('state = 1');

        if($this->getUser()->guest)
            $query->setLimit('1');

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getCurrentUser()
    {
        $user = $this->getUser();

        if( !$user->guest )
        {
            $query = $this->_db->getQuery(true);
            $query->select('juser_id, photo');
            $query->from('#__z_users');
            $query->where('juser_id = ' . $user->id);

            $this->_db->setQuery($query);
            $result = $this->_db->loadObject();
            $result->guest = 0;
            return $result;
        }

        $user->juser_id = 0;

        return $user;
    }

    public function deletePlant($id)
    {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

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
    }

    public function checkPlant($id)
    {
        $query = $this->_db->getQuery( true );

        $query->select( '*' );
        $query->from( '#__z_plants_user_plants' );
        $query->where( 'plant_id = ' . $id );
        $query->andWhere( 'user_id = ' . $this->getUser()->id );

        $this->_db->setQuery( $query );

        if($this->_db->loadResult())
            return true;
        else
            $this->savePlant($id);
    }

    private function getUser()
    {
        return JFactory::getUser();
    }

    public function viewCounter()
    {
        $query = $this->_db->getQuery(true);
        $query->update('#__z_plants_plants');
        $query->set('hits = hits + 1');
        $query->where("id = $this->item_id");

        $this->_db->setQuery($query);
        $this->_db->execute();
    }
}