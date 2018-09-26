<?php

    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);

    $curDir	= dirname(__FILE__);

    define('JPATH_BASE', $curDir);

    require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
    require_once ("vendor/autoload.php");

    JLoader::import('joomla.filesystem.file');


    class optimizePhoto
    {
        protected $db;

        /**
         * optimizePhoto constructor.
         */
        public function __construct()
        {
            $this->db = JFactory::getDbo();
        }



        /**
         * @return ObjectList of non optimized photos
         */
        public function getPhotos()
        {
            $this->db = JFactory::getDbo();
            $query = $this->db->getQuery(true);
            $query->select('plant_id, src');
            $query->from('#__z_plants_plant_photos');
            $query->where($this->db->quoteName('optimize') . ' = ' . 0);
            $this->db->setQuery($query);

            return $this->db->loadObjectList();
        }



        /**
         * @param $photos
         * @param $folder
         * @return array of optimized photos ids
         */
        public function optimize($photos, $folder)
        {
            //optimized photos ids
            $optimizedPhotos = [];

            $config = JComponentHelper::getParams('com_plants');
            $api_key = $config->get('tinypng_key');

            \Tinify\setKey($api_key);

            foreach ($photos as $photo)
            {

                if($photo->src)
                {
                    $path = JPATH_SITE . $folder . $photo->src;
                    if( !$this->issetFile($path) )
                        continue;

                    $source = \Tinify\fromFile($path);
                    $source->toFile($path);

                    $path = JPATH_SITE . $folder . 'wide_' . $photo->src;
                    if( !$this->issetFile($path) )
                        continue;

                    $source = \Tinify\fromFile($path);
                    $source->toFile($path);

                    $optimizedPhotos[] = $photo->plant_id;
                }
            }

            if( !empty($optimizedPhotos) )
                return $optimizedPhotos;
            else
                return NULL;
        }



        /**
         * @param $path
         * @return bool
         */
        public function issetFile($path)
        {
            if( JFile::exists($path) )
                return true;
            else
                return false;
        }



        /**
         * @param $ids
         */
        public function updatePhotosStatus($ids)
        {
            if($ids)
            {
                $ids = implode(',', $ids);

                $query = $this->db->getQuery(true);
                $query->update('#__z_plants_plant_photos');
                $query->set($this->db->quoteName('optimize') . ' = ' . 1);
                $query->where("plant_id IN ($ids)");

                $this->db->setQuery($query);
                $this->db->execute();
            }
        }
    }

    $opt = new optimizePhoto();
    $photos = $opt->getPhotos();

    $folder = '/images/plants/';
    $optimizedPhotos = $opt->optimize($photos, $folder);

    $opt->updatePhotosStatus($optimizedPhotos);