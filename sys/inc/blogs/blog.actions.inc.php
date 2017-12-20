<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/20/2017
 * Time: 9:35 PM
 * Code to rework in future
 */
if(isset($_POST)){
    $blogId = 1;
    $action = 'delete';
    switch ($action){
        case 'delete':
            blogs::deleteBlog($blogId);
            break;
        case 'edit':
            break;
    }
}