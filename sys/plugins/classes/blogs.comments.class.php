<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/19/2017
 * Time: 12:15 PM
 */
class blogComments{
    public static function addComment($userId, $time, $message, $blogId){
        $sql = "INSERT INTO `blogs_comments` (`userId`, `time`, `message`, `blogId`) VALUES (?, ?, ?, ?)";
        $res = DB::me()->prepare($sql);
        return $res->execute(Array($userId, $time, $message, $blogId));
    }
}