<?php

defined( '_JEXEC' ) or die;
JLoader::register('JFolder', JPATH_LIBRARIES . '/joomla/filesystem/folder.php');

require_once ("vendor/autoload.php");
use Intervention\Image\ImageManager;

/*
Params:
  file      - default joomla file name (example: images/bird.jpg)
  width     - image width (int)
  height    - image height (int)
  folder    - save to folder (example: images/gallery/)
  filename  - save image as (example: file001.jpg)

  watermark - array(
    'src'          => '/var/www/html/site.com/images/example.jpg',
    'transparency' => 100,
    'bottomMargin' => 0,
    'rightMargin'  => 0,
  )
*/

class OptimizeHelper
{
    static function optimizePhoto($file, $width, $height, $folder = false, $filename = false, $watermark = false)
    {
        $manager = new ImageManager(array('driver' => 'imagick'));

        if($folder)
            $image = $manager->make($file);
        else
            $image = $manager->make(JPATH_SITE . '/' . $file);

        $exif = array();

        try{
            $exif = exif_read_data($file);
        } catch (Exception $e)
        {
            //...
        }

        if (!empty($exif['Orientation']))
        {
            $rotate = 0;

            switch ($exif['Orientation'])
            {
                case 8:
                    $rotate = 90;
                    break;
                case 3:
                    $rotate = -180;
                    break;
                case 6:
                    $rotate = -90;
                    break;
            }

            $image->rotate($rotate);
        }

        //create thumbnail (260 x 260 px)
        if($width === 260 && $height === 260)
        {
            if($image->width() > $image->height())
            {
                $image->resize(null, 260, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            else
            {
                $image->resize(260, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->crop(260, 260);
        }
        //resize image
        else
        {
            if($image->width() > $image->height()) {
                if ($image->width() > $width) {
                    $image->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
            else
            {
                if($image->height() > $height)
                {
                    $image->resize(null, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        }

        if($folder)
        {
            $path = JPATH_SITE . '/' . $folder;

            if(!JFolder::exists($path))
            {
                JFolder::create($path);
            }

            $path .= $filename;
        }
        else
        {
            $path = JPATH_SITE . '/' . $file;
        }

        if($watermark)
        {
            $image->insert($watermark['src'],'bottom-right', 20, 20);
        }

        $image->save($path);

        return $filename;
    }
}