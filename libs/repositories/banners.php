<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/banner.php');

class Banners
{
    public static function Find($id)
    {
        $query = 'EXEC [getBannerById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No user found.');
        }

        $banner = new Banner(
            $rows[0]['username']
        );
        $banner->setId($rows[0]['id']);

        return $banner;
    }

    public static function All()
    {
        $query = 'EXEC [getBanners]';

        $rows = Database::Execute($query);

        $banners = array();
        foreach ($rows as $row) {

            $banner = new Country(
                $row['name']);
            $banner->setId($row['id']);

            $banners[] = $banner;
        }
        return $banners;
    }
}