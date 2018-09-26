<?php

defined('_JEXEC') or die;
require_once ("vendor/autoload.php");
use \DrewM\MailChimp\MailChimp;

class PlantsModelMain extends JModelList
{
	public function subscribe($email)
    {
        //save user to mailchimp lists
        $api_key = 'd6e6ece15586da080aab279410a94099-us18';
        $MailChimp = new MailChimp($api_key);
        $lists = $MailChimp->get('lists');
        $list_id = $lists['lists'][0]['id'];

        $MailChimp->post("lists/$list_id/members", [
                'email_address' => $email,
                'status'        => 'subscribed',
            ]
        );

        //save user to database
        $query = $this->_db->getQuery(true);
        $query->insert('#__z_plants_subscribers');
        $query->set('email = ' . $this->_db->quote($email));
        $query->set('status = 1');
        $this->_db->setQuery($query)->execute();

        echo 'success';
        exit;
    }

    public function getCatalog()
    {
        $query = $this->_db->getQuery(true);

        $query->select('photo, alias');
        $query->from('#__z_plants_plant_category');
        $query->where('state = 1');

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getDayPlant()
    {
        $query = $this->_db->getQuery(true);
        $query->select('p.id, p.sort_name, pp.src as photo, u.juser_id as user_id, u.first_name as user_name, u.last_name as user_last_name, u.photo as user_photo, c.name_en as city');
        $query->from('#__z_plants_plants p');
        $query->leftJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
        $query->leftJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->leftJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->leftJoin('#__sxgeo_cities c ON c.id = u.city_id');
        $query->where('p.top_plant = 1');
        $query->andWhere('published = 1');

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }



    /**
     * @return last added plants on site
     */
    public function getLastPlants()
    {
        $query = $this->_db->getQuery(true);
        $query->select('pp.src as photo, p.id');
        $query->from('#__z_plants_plants p');
        $query->leftJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
        $query->order('p.created_at DESC');
        $query->setLimit(12);
        $query->group('id');
        $query->where('published = 1');

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }



    /**
     * @return mixed
     */
    public function getRandomPlant()
    {
        $query = $this->_db->getQuery(true);
        $query->select('p.*, u.first_name as user_first_name, u.last_name as user_last_name, pp.src photo, c.name_en as city, u.photo as user_photo, u.juser_id as user_id');
        $query->from('#__z_plants_plants p');
        $query->innerJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
        $query->innerJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->innerJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->leftJoin('#__sxgeo_cities c ON c.id = p.city_id');
        $query->order('RAND()');
        $query->where('published = 1');
        $query->setLimit(1);

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }



    /**
     * @return popular plants
     * by views
     */
    public function getPopularPlants()
    {
        $query = $this->_db->getQuery(true);
        $query->select('p.*, pp.src, c.name_en as city, u.photo as user_photo, u.first_name as user_first_name, u.last_name as user_last_name, u.juser_id as user_id');
        $query->from('#__z_plants_plants p');
        $query->leftJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
        $query->leftJoin('#__sxgeo_cities c ON c.id = p.city_id');
        $query->innerJoin('#__z_plants_user_plants up ON up.plant_id = p.id');
        $query->innerJoin('#__z_users u ON u.juser_id = up.user_id');
        $query->order('p.hits DESC');
        $query->where('published = 1');
        $query->group('id');
        $query->setLimit(8);

        $this->_db->setQuery($query);
//        print_r( $this->_db->loadObjectList() );
//        exit;
        return $this->_db->loadObjectList();
    }



    /**
     * @return mixed
     */
    public function getPosts()
    {
        $query = $this->_db->getQuery(true);
        $query->select('pp.*, pc.alias as category');
        $query->from('#__z_plants_posts pp');
        $query->leftJoin('#__z_plants_plant_category pc ON pc.id = pp.category_id');

        $this->_db->setQuery($query);
        return$this->_db->loadObjectList();
    }
}