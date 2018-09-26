<?php

defined('_JEXEC') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldCountry extends JFormFieldList
{
	protected function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, name_en');
		$query->from('#__sxgeo_country');

		$db->setQuery($query);

		try
		{
			$rows = $db->loadObjectList();
		}
		catch (RuntimeException $e)
        {
			JError::raiseWarning(500 , $e->getMessage());
		}

		$options[] = (object) ['value' => '0', 'text' => 'Select country'];

		foreach ($rows as $category)
		{
			$value = (string)$category->id;
			$text = (string)$category->name_en;

			$options[] = ['value' => $value, 'text' => $text];
		}

		return $options;
	}
}