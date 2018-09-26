<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldPlanttype extends JFormFieldList
{
    protected $type = 'planttype';

    protected function getOptions()
    {
        $plant_category_id = $this->form->getData()->get('plant_category_id');

        $fieldname = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);
        $options = array();

        $db = JFactory::getDbo();

        if($plant_category_id)
            $db->setQuery("SELECT * FROM #__z_plants_plant_types WHERE category_id = $plant_category_id");
        else
            $db->setQuery("SELECT * FROM #__z_plants_plant_types");

        $types = $db->loadObjectList();
        $options[] = (object) ['value' => '', 'text' => 'Select type'];

        foreach ($types as $type)
        {
            $value = (string) $type->id;
            $text = (string) $type->title;

            $tmp = array(
                'value'    => $value,
                'text'     => JText::alt($text, $fieldname)
            );

            $options[] = (object) $tmp;
        }

        reset($options);

        return $options;
    }
}
