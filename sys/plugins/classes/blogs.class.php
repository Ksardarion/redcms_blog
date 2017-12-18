<?php

/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/17/2017
 * Time: 10:08 PM
 */
class blogs
{
    public static function create($title, $text, $preview, $time, $author, $catId){
        $sql = "INSERT INTO `blogs` (`title`, `content`, `preview`, `time`, `author`, `category`) VALUES (?, ?, ?, ?, ?, ?)";
        $res = DB::me()->prepare($sql);
        return $res->execute(Array($title, $text, $preview, $time, $author, $catId));
    }
}