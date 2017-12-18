<?php

/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/17/2017
 * Time: 10:11 PM
 * Class worked with data from DB
 */
class data
{
    //TODO: create functions for couter, setter, getter
//    public static function getDataFromDB($table, $select, $state){
//        $where = ($state) ? 'WHERE ' . $state : '';
//        $sql = "SELECT `{$select}` FROM `{$table}` {$where} LIMIT 1";
//        $res = DB::me()->prepare($sql);
//        return $res->execute(Array());
//    }
    public static function getDataWithLimit($table, $limit){
        $sql = "SELECT * FROM `{$table}` LIMIT {$limit}";
        $res = DB::me()->prepare($sql);
        $res->execute();
        return $res;
    }
    public static function getDataWithRelAndLimit($table, $relName, $rel, $limit){
        $sql = "SELECT * FROM `{$table}` WHERE `{$relName}` = {$rel} LIMIT {$limit}";
        $res = DB::me()->prepare($sql);
        $res->execute();
        return $res;
    }
    public static function getCountOfRows($table){
        $sql = "SELECT COUNT(*) FROM `{$table}`";
        $res = DB::me()->prepare($sql);
        $res->execute();
        return $res->rowCount();
    }
    public static function getRowById($table, $id){
        $sql = "SELECT * FROM `{$table}` WHERE `id` = ?";
        $res = DB::me()->prepare($sql);
        $res->execute(Array($id));
        return $res->fetch();
    }
    public static function createBlogsCategory($name, $description){
        $sql = "INSERT INTO `blogs_categories` (`name`, `description`) VALUES (?, ?)";
        $res = DB::me()->prepare($sql);
        return $res->execute(Array($name, $description));
    }
}