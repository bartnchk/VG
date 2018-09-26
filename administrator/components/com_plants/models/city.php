<?php

defined('_JEXEC') or exit();

class PlantsModelCity extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true) {

		$form = $this->loadForm(
			'com_plants.city',
			'city',
			array(
				'control' => 'jform',
				'load_data' => $loadData
				)
			);

		if (empty($form))
			return false;

        return $form;
	}

	public function getTable($type = 'City', $prefix = 'PlantsTable', $config = array())
    {
		return JTable::getInstance($type, $prefix, $config);
	}

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);
        $country = $this->getCountryByRegion($item->region_id);

        $item->set('region_id', $country);

        return $item;
    }

    private function getCountryByRegion($region_id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('sc.id');
        $query->from('#__sxgeo_country sc');
        $query->leftJoin('sxgeo_regions sr ON sr.id = ' . (int)$region_id);
        $query->where('sc.iso = sr.country');

        return $this->_db->setQuery($query)->loadResult();
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }

    public function delete(&$pks)
    {
        parent::delete($pks);
    }

    public function prepareTable($table)
    {
        $table->region_id = $this->getRegionId($table->region_id);
    }

    public function getRegionId($id)
    {
//        $query = $this->_db->getQuery(true);
//        $query->select('sr.id');
//        $query->from('#__sxgeo_regions sr');
//        $query->innerJoin('#__sxgeo_countries sc ON sc.iso = sr.country');
//        $query->where('sr.id = ' . (int)$id);


        $query = $this->_db->getQuery(true);
        $query->select('sr.id');
        $query->from('#__sxgeo_regions sr');
        $query->leftJoin('sxgeo_country sc ON sc.id = ' . (int)$id);
        $query->where('sc.iso = sr.country');

        return $this->_db->setQuery($query)->loadResult();
    }
}