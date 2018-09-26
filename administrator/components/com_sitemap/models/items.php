<?php

defined('_JEXEC') or exit();

class SitemapModelItems extends JModelList {

	protected function getListQuery() {

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__sitemap');

		return $query;
	}

	public function generateMap(){
		
	    $results = [];

		$query = $this->_db->getQuery(true);

		$query->select('path')
		      ->from('#__menu')
		      ->where('menutype = ' . $this->_db->quote('mainmenu') )
		      ->andWhere('published = 1')
			  ->andWhere('type = ' . $this->_db->quote('component'));

		$results['static'] = $this->_db->setQuery($query)->loadObjectList();


		//plants
		$query->clear();
		$query->select('id')
			  ->from('#__z_plants_plants')
			  ->where('published = 1');
		$results['plant'] = $this->_db->setQuery($query)->loadObjectList();


		//users
		$query->clear();
		$query->select('juser_id')
		      ->from('#__z_users');
		$results['profile'] = $this->_db->setQuery($query)->loadObjectList();


		//plant categories
        $query->clear();
        $query->select('alias')
            ->from('#__z_plants_plant_category');
        $results['categories'] = $this->_db->setQuery($query)->loadObjectList();


		$map  = "<?xml version='1.0' encoding='UTF-8'?>". "\n";
		$map .= "<urlset xmlns='https://www.sitemaps.org/schemas/sitemap/0.9'>" . "\n";
		$map .=	"<url>" . "\n";

		$map .= "<loc>" .JUri::root() . "</loc>";
		$map .= "<changefreq>daily</changefreq>" . "\n";
		$map .= "<lastmod>" . date( 'Y-m-d' ) . "</lastmod>" . "\n";
		$map .= "<priority>1</priority>" . "\n";

		foreach ($results as $key => $items)
		{
			foreach ( $items as $item )
			{
				if($key == 'plant')
				{
					$map .= "<loc>" . JUri::root() . $key .'?id='. $item->id . "</loc>" . "\n";
				}
                elseif($key == 'profile')
                {
                    $map .= "<loc>" . JUri::root() . 'profile?id='. $item->juser_id . "</loc>" . "\n";
                }
                elseif($key == 'categories')
                {
                    $map .= "<loc>" . JUri::root() . 'catalog/'. $item->alias . "</loc>" . "\n";
                }
				else
                {
					$map .= "<loc>" . JUri::root() . $item->path  . "</loc>" . "\n";
				}

                $map .= "<changefreq>daily</changefreq>" . "\n";
                $map .= "<lastmod>" . date( 'Y-m-d' ) . "</lastmod>" . "\n";
                $map .= "<priority>0.5</priority>" . "\n";
			}
		}

		$map .= "</url>". "\n";
		$map .=	"</urlset>". "\n";

		file_put_contents(JPATH_ROOT. '/sitemap.xml' , $map);

		$xml = simplexml_load_file(JPATH_ROOT. '/sitemap.xml');

		$count = count($xml->url->loc);


		$this->_db->setQuery('DELETE FROM #__sitemap')->execute();


		$query->clear();
		for($i=0; $i<$count; $i++)
		{
			$query->insert( '#__sitemap' )
			      ->set( 'loc = ' . $this->_db->quote($xml->url->loc[$i]) )
			      ->set( 'changefreq = ' . $this->_db->quote($xml->url->changefreq[$i]) )
			      ->set( 'lastmode = NOW()')
			      ->set( 'priority = ' . $this->_db->quote($xml->url->priority[$i]) );
			$this->_db->setQuery($query)->execute();
			$query->clear();
		}

		JFactory::getApplication()->enqueueMessage('Sitemap has been generated');
		JFactory::getApplication()->redirect('/administrator/index.php?option=com_sitemap&view=items' );
	}
}
