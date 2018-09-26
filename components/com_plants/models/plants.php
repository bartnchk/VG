<?php

defined( '_JEXEC' ) or die;

class PlantsModelPlants extends JModelList
{
	private $id;
    private $category;
	private $input;
	private $plants_owner;

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->plants_owner = false;

        $app = JFactory::getApplication();
        $this->input = $app->input;
        $this->id = $this->input->get('id', '', 'INT');
        $this->category = $this->input->get('category', '', 'STRING');

        if(!$this->id)
        {
            $this->plants_owner = true;
            $this->id = $this->getCurrentUser()->id;
        }


        if(JFactory::getUser()->guest)
            $app->redirect('/');
    }

    protected function getListQuery()
    {
        $query = $this->_db->getQuery(true);

        $query->select('p.*, pp.src, c.city')
              ->from( '#__z_plants_user_plants up' )
              ->innerJoin( '#__z_plants_plants p ON up.plant_id = p.id' )
              ->leftJoin( '#__z_plants_plant_photos pp ON p.id = pp.plant_id' )
              ->leftJoin( '#__z_plants_cities c ON c.id = p.city_id' )
              ->where("up.user_id = $this->id")
              ->group('p.id')
              ->order('p.created_at DESC');

        if(!$this->plants_owner)
            $query->where('p.published = 1');

        if($this->category)
        {
            $query->innerJoin( '#__z_plants_plant_category pc ON pc.id = p.plant_category_id');
            $query->andWhere( 'pc.alias = ' . $this->_db->quote($this->category) );
        }

        return $query;
    }

    public function getUserData()
    {
        if($this->id)
        {
            $query = $this->_db->getQuery(true);
            $query->select('first_name, juser_id');
            $query->from('#__z_users');
            $query->where("juser_id = $this->id");

            $this->_db->setQuery($query);
            return $this->_db->loadObject();
        }
    }

    public function getCurrentUser()
    {
        return JFactory::getUser();
    }

    public function getCategory()
    {
        return $this->category;
    }



    /**
     * @return plant categories
     */
    public function getCategories()
    {
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_plant_category');
        $query->order('id DESC');
        $this->_db->setQuery($query);


        return $this->_db->loadObjectList();
    }
}
