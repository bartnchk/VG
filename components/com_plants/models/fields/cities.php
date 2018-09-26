<?php

defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldCities extends JFormFieldList
{
    protected $type = 'cities';

    protected function getOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__z_plants_cities');
        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $options[] = (object) ['value' => '0', 'text' => 'Select city'];

        foreach ($rows as $row)
        {
            $value = (string)$row->id;
            $text = (string)$row->city;
            $options[] = ['value' => $value, 'text' => $text];
        }

        return $options;
    }
}