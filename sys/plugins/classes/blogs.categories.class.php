<?php

/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/17/2017
 * Time: 10:17 PM
 */
class categories
{
//    protected $id;
//
//    function __construct($id)
//    {
//        $this->id = $id;
//    }
    public static function getCategory($id){
        $sql = "SELECT * FROM `blogs_categories` WHERE `id` = ? LIMIT 1";
        $res = DB::me()->prepare($sql);
        $res->execute(Array($id));
        return $res;
    }
    public static function getAllCategories($limit){
        $sql = "SELECT * FROM `blogs_categories` LIMIT {$limit}";
        $res = DB::me()->prepare($sql);
        $res->execute();
        return $res;
    }
}