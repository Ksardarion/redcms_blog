<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/18/2017
 * Time: 5:12 PM
 */

require_once '../sys/inc/start.php';
require_once '../sys/plugins/classes/blogs.categories.class.php';

$doc = new document();

$id = $_GET['id'];
$blog = data::getRowById('blogs', $id);

if(empty($blog)){
    $doc->ret(__('В категорию'), '/blogs/category.php?id=' . $blog['category']);
    $doc->err(__('Блог не существует'));
    exit();
}

$doc->title = __($blog['title']);

$doc->ret(__('В категорию'), '/blogs/category.php?id=' . $blog['category']);

$listing = new listing();
$post = $listing->post();
$ank = new user((int) $blog['author']);
$post->image = $ank->ava();
$post->content = text::toOutput($blog['content']);
$post->title = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a> <b>' . text::toValue($blog['title']) . '</b>';
$post->time = misc::when($blog['time']);
//$post->bottom = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a>';
$listing->display();