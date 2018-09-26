<?php

defined('_JEXEC') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldCategoryParent extends JFormFieldList
{
	protected function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, state');
		$query->from('#__z_plants_plant_category');
		$query->where('state'. ' = ' .$db->quote(1) );

		$db->setQuery($query);

		try
		{
			$row = $db->loadObjectList();
		}
		catch (RuntimeException $e){
			JError::raiseWarning(500 , $e->getMessage());
		}

		$options[] = (object) ['value' => '0', 'text' => 'Select category'];

		foreach ($row as $category)
		{
			$value = (string)$category->id;
			$text = (string)$category->title;

			$options[] = ['value' => $value, 'text' => $text];

		}

		return $options;
	}
}