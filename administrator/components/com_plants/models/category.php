<?php

defined('_JEXEC') or die;

class PlantsModelCategory extends JModelAdmin
{

	public function getForm( $data = array(), $loadData = true ) {
		$form = $this->loadForm( $this->option . 'category', 'category', [ 'control'   => 'jform',
		                                                                   'load_data' => $loadData
		] );
		if ( empty( $form ) )
		{
			return false;
		}
		return $form;
	}

	public function getTable($type = 'Category', $prefix = 'PlantsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_plants.edit.category.data', array());

		if(empty($data)){
			$data = $this->getItem();
		}
		return $data;
	}

	public function save( $data )
	{
		if(trim($data['alias'] == '')) {
			$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
		}
		if(parent::save($data)){
			return true;
		}
		return false;
	}

	public function delete( &$pks )
	{
		return parent::delete( $pks );
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  boolean|JException  Boolean true on success, false on failure, or JException if no items are selected
	 *
	 * @since   1.6
	 */
	public function saveorder($pks = array(), $order = null)
	{
		$table = $this->getTable();
		$tableClassName = get_class($table);
		$contentType = new JUcmType;
		$type = $contentType->getTypeByTable($tableClassName);
		$tagsObserver = $table->getObserverOfClass('JTableObserverTags');
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		$orderingField = $table->getColumnAlias('ordering');

		// Update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			if (!$this->canEditState($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);
				JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			}
			elseif ($table->$orderingField != $order[$i])
			{
				$table->$orderingField = $order[$i];

				if ($type)
				{
					$this->createTagsHelper($tagsObserver, $type, $pk, $type->type_alias, $table);
				}

				if (!$table->store())
				{
					$this->setError($table->getError());

					return false;
				}

				// Remember to reorder within position and client_id
				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
}