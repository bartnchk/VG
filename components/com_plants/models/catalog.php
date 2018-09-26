<?php

defined('_JEXEC') or die;

class PlantsModelCatalog extends JModelList
{
    protected $segment;
    protected $filters;
    protected $search_query;
    protected $search_type;

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $input = JFactory::getApplication()->input;
        $this->segment = $input->get('segment');
        $this->filters = $this->getFilters();
        $this->search_type = JFactory::getApplication()->input->getInt('strong');

        $this->search_query = $input->get('search_query', '', 'string');
    }

    public function getCategories()
    {
        $query = $this->_db->getQuery(true);
        $query->select('title, alias, cover');
        $query->from('#__z_plants_plant_category');
        $query->order('id DESC');
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

    public function getPlants($counter = NULL, $segment = NULL, $all = FALSE)
    {
        $query = $this->_db->getQuery(true);
        $query->select('count(p.id) as counter, p.id, p.sort_name, p.germinability, p.yield, p.easy_care, p.author_recomends, p.manufactured, p.description,  
        pp.src as photo, u.photo as user_photo, u.first_name as user_first_name, u.last_name as user_last_name, u.juser_id as user_id');
        $query->from('#__z_plants_plants p ');
        $query->leftJoin('#__z_plants_plant_photos pp ON p.id = pp.plant_id');
        $query->leftJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->leftJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->where('p.published = 1');
        $query->group('p.id');

        if($this->search_query)
        {
            $query->where('p.sort_name LIKE ' . $this->_db->quote('%' . $this->search_query . '%'));
            $query->orWhere('p.barcode LIKE ' . $this->_db->quote('%' . $this->search_query . '%'));
            $query->orWhere('p.manufactured LIKE ' . $this->_db->quote('%' . $this->search_query . '%'));
            $query->orWhere('p.description LIKE ' . $this->_db->quote('%' . $this->search_query . '%'));
        }

        //sort by
        if($this->filters['sortby'] == 'priceup')
            $query->order('p.price ASC');

        elseif($this->filters['sortby'] == 'pricedown')
            $query->order('p.price DESC');

        elseif($this->filters['sortby'] == 'nameup')
            $query->order('p.sort_name ASC');

        elseif($this->filters['sortby'] == 'namedown')
            $query->order('p.sort_name DESC');

        else
            $query->order('p.created_at DESC');

        //filter by category
        if($this->segment)
        {
            $cat_id = $this->getCategoryId($this->segment);
            $query->where('p.plant_category_id = ' . $cat_id);
        }
        elseif($segment)
        {
            $cat_id = $this->getCategoryId($segment);
            $query->where('p.plant_category_id = ' . $cat_id);
        }

        //filter by stars, city and price
        if( !empty($this->filters) )
        {
            $sign = '=';

            if($this->filters['germinability'])
            {
                if( !ctype_digit($this->filters['germinability'][0]) )
                {
                    $sign = $this->filters['germinability'][0] . '=';
                    $this->filters['germinability'] = $this->filters['germinability'][1];
                }

                $query->where('p.germinability ' . $sign . ' ' . $this->_db->quote($this->filters['germinability']));
                $query->where('p.germinability >= 1');
                $sign = '=';
            }

            if($this->filters['yield'])
            {
                if( !ctype_digit($this->filters['yield'][0]) )
                {
                    $sign = $this->filters['yield'][0] . '=';
                    $this->filters['yield'] = $this->filters['yield'][1];
                }

                $query->where('p.yield ' . $sign . ' ' . $this->_db->quote($this->filters['yield']));
                $query->where('p.yield >= 1');
                $sign = '=';
            }

            if($this->filters['easy_care'])
            {
                if( !ctype_digit($this->filters['easy_care'][0]) )
                {
                    $sign = $this->filters['easy_care'][0] . '=';
                    $this->filters['easy_care'] = $this->filters['easy_care'][1];
                }

                $query->where('p.easy_care ' . $sign . ' ' . $this->_db->quote($this->filters['easy_care']));
                $query->where('p.easy_care >= 1');
                $sign = '=';
            }

            if($this->filters['author_recomends'])
            {
                if( !ctype_digit($this->filters['author_recomends'][0]) )
                {
                    $sign = $this->filters['author_recomends'][0] . '=';
                    $this->filters['author_recomends'] = $this->filters['author_recomends'][1];
                }

                $query->where('p.author_recomends ' . $sign . ' ' . $this->_db->quote($this->filters['author_recomends']));
                $query->where('p.author_recomends >= 1');
            }

            if( $this->filters['price'][0] || isset($this->filters['price'][1]) )
                $query->where('p.price BETWEEN ' . $this->_db->quote((int)$this->filters['price'][0]) . ' AND ' . (int)$this->filters['price'][1]);

            if($this->filters['city_id'])
                $query->where('p.city_id = ' . $this->_db->quote($this->filters['city_id']));

            if($this->filters['type'])
                $query->where('p.plant_type_id = ' . $this->_db->quote($this->filters['type']));
        }

        //limit
        if( !$counter )
        {
            if( !$all )
            {
                $query->setLimit(12);
            }

            $this->_db->setQuery($query);

            return $this->_db->loadObjectList();
        }
        else
        {
            $this->_db->setQuery($query, $counter, $counter + 6);
            $result = $this->_db->loadObjectList();

            if( !empty($result) )
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            else
                echo 0;
        }
    }

    private function getCategoryId($alias)
    {
        $query = $this->_db->getQuery(true);
        $query->select('id');
        $query->from('#__z_plants_plant_category');
        $query->where('alias = ' . $this->_db->quote($alias));

        $result = $this->_db->setQuery($query)->loadResult();

        if($result)
            return $result;
        else
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
    }

    public function getSegment()
    {
        return $this->segment;
    }

    public function getFilters()
    {
        $input = JFactory::getApplication()->input;

        $germinability     = $input->get('germinability', '', 'RAW');
        $yield             = $input->get('yield', '', 'RAW');
        $easy_care         = $input->get('easy_care', '', 'RAW');
        $author_recommends = $input->get('author_recommends', '', 'RAW');
        $price_str         = $input->get('price');
        $city_id           = $input->get('city_id', false, 'int');
        $sortby            = $input->get('sortby', false, 'word');
        $type              = $input->get('type', false, 'int');

        $price = explode('-', $price_str);

        return [
            'germinability'     => $germinability,
            'yield'             => $yield,
            'easy_care'         => $easy_care,
            'author_recomends'  => $author_recommends,
            'price'             => $price,
            'city_id'           => $city_id,
            'city_name'         => $this->getCityName($city_id),
            'sortby'            => $sortby,
            'type'              => $type,
        ];
    }



    /**
     * @param $id
     * @return city name by id
     */
    public function getCityName($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('sc.name_en as city, _sc.name_en as country');
        $query->from('#__sxgeo_cities sc');
        $query->leftJoin('sxgeo_regions sr ON sr.id = sc.region_id');
        $query->leftJoin('sxgeo_country _sc on _sc.iso = sr.country');
        $query->where('sc.id = ' . (int)$id);
        $this->_db->setQuery($query);

        $result = $this->_db->loadObject();

        if($result)
            return $result->city . ', ' . $result->country;
        else
            return '';
    }

    public function getCounter()
    {
        $results = $this->getPlants('','',TRUE);
        return count($results);
    }

    public function getFilterValues()
    {
        return $this->filters;
    }

    public function getMaxPlantPrice()
    {
        $query = $this->_db->getQuery(true);
        $query->select('MAX(price)');
        $query->from('#__z_plants_plants');
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }



    /**
     * return random plants
     */
    public function getRandomPlants()
    {
        $query = $this->_db->getQuery(true);
        $query->select('p.id, p.sort_name, p.germinability, p.yield, p.easy_care, p.author_recomends, p.manufactured, p.description,  
        pp.src as photo, u.photo as user_photo, u.first_name as user_first_name, u.last_name as user_last_name, u.juser_id as user_id');
        $query->from('#__z_plants_plants p ');
        $query->leftJoin('#__z_plants_plant_photos pp ON p.id = pp.plant_id');
        $query->leftJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->leftJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->order('RAND()');
        $query->where('p.published = 1');
        $query->group('p.id');
        $query->setLimit(12);

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }



    /**
     * @return array|mixed
     * @throws Exception
     * return plant types for select field
     */
    public function getTypes()
    {
        if($this->segment)
        {
            $category_id = $this->getCategoryId($this->segment);

            $query = $this->_db->getQuery(true);
            $query->select('*');
            $query->from('#__z_plants_plant_types');
            $query->where('category_id = ' . (int)$category_id);
            $query->order('title ASC');
            $this->_db->setQuery($query);

            return $this->_db->loadObjectList();
        }

        return [];
    }
}