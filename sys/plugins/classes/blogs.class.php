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
    public static function addViewer($blogId, $userId, $time){
        $sql = "INSERT INTO `blogs_views` (`id_blog`, `userId`, `time`) VALUES (?, ?, ?)";
        $res = DB::me()->prepare($sql);
        return $res->execute(Array($blogId, $userId, $time));
    }
    public static function checkViewer($userId){
        return (data::getDataWithRelAndLimit('blogs_views', 'userId', $userId, 1));
    }
    public static function getPreview($blogId){
        $blog = data::getRowById('blogs', $blogId);
        return ($blog['preview']) ? $blog['preview'] : mb_strimwidth($blog['content'], 0, 500);
    }
}