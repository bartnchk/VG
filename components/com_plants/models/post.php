<?php

defined('_JEXEC') or die;

class PlantsModelPost extends JModelList
{
    private $id;

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $input = JFactory::getApplication()->input;
        $this->id = $input->get('id');
    }

    public function getPost()
    {
        if($this->id)
        {
            $query = $this->_db->getQuery(true);
            $query->select('*');
            $query->from('#__z_plants_posts');
            $query->where("id = $this->id");

            $this->_db->setQuery($query);
            $result = $this->_db->loadObject();

            if (!empty($result))
                return $result;
        }

        throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
    }
}