<?php

defined('_JEXEC') or die;
jimport( 'joomla.application.component.modelform' );

class PlantsModelProfile extends JModelList
{
    private $user_id;

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->app = JFactory::getApplication();

        $this->user_id = $this->app->input->get('id', '', 'int');

        if(!$this->user_id)
        {
            $this->user_id = $this->getCurrentUser()->id;
        }

        if(!$this->user_id)
        {
            $this->app->redirect('/login');
        }
    }

    public function getUser()
    {
        $query = $this->_db->getQuery(true);

        $query->select('u.*, concat(sc.name_en, ", ", _sc.name_en) as city');
        $query->from('#__z_users u');
        $query->leftJoin('#__sxgeo_cities sc ON sc.id = u.city_id');
        $query->leftJoin('sxgeo_regions sr ON sr.id = sc.region_id');
        $query->leftJoin('sxgeo_country _sc on _sc.iso = sr.country');
        $query->where('juser_id = ' . $this->user_id);

        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();

        if($result)
            return $result;
        else
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
    }

    public function getCategories()
    {
        $query = $this->_db->getQuery(true);

        $query->select('DISTINCT c.title, c.alias, c.photo');
        $query->from('#__z_plants_plant_category c');
        $query->innerJoin('#__z_plants_user_plants up ON up.user_id = ' . $this->user_id);
        $query->innerJoin('#__z_plants_plants p ON p.id = up.plant_id');
        $query->where('p.published = 1');
        $query->where('c.id = p.plant_category_id');

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getBestPlant()
    {
        $query = $this->_db->getQuery(true);

        $query->select('p.id, p.sort_name, p.manufactured, p.germinability, p.yield, p.easy_care, p.author_recomends, pp.src as photo');
        $query->from('#__z_plants_plants p');
        $query->innerJoin('#__z_plants_user_plants up ON up.user_id = ' . $this->user_id);
        $query->innerJoin('#__z_plants_plant_photos pp ON pp.plant_id = up.plant_id');
        $query->where('p.id = up.plant_id');
        $query->where('p.published = 1');


        $this->_db->setQuery($query);
        $plants = $this->_db->loadObjectList();

        if(!empty($plants)) {
            if( count($plants) > 1 )
            {
                $best_plant_rate = 0;
                $best_plant_key = 0;

                foreach ($plants as $k => $plant)
                {
                    $rate = $plant->germinability + $plant->yield + $plant->easy_care + $plant->author_recomends;
                    if($rate > $best_plant_rate)
                    {
                        $best_plant_rate = $rate;
                        $best_plant_key = $k;
                    }
                }

                return $plants[$best_plant_key];
            }
            else
            {
                return $plants[0];
            }
        }
    }

    public function getPlantsCounter()
    {
        $query = $this->_db->getQuery(true);
        $query->select( 'count(up.plant_id)' );
        $query->from('#__z_plants_user_plants up');
        $query->innerJoin('#__z_plants_plants p ON p.id = up.plant_id');
        $query->where("up.user_id = $this->user_id");

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    public function getPlants()
    {
        $query = $this->_db->getQuery(true);
        $query->select('p.id, pp.src');
        $query->from('#__z_plants_plant_photos pp');
        $query->innerJoin('#__z_plants_user_plants up ON up.user_id = ' . $this->user_id);
        $query->innerJoin('#__z_plants_plants p ON p.id = up.plant_id');
        $query->where('pp.plant_id = p.id');
        $query->where('p.published = 1');
        $query->order('RAND()');
        $query->group('id');
        $query->setLimit(9);

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getCurrentUser()
    {
        return JFactory::getUser();
    }
}