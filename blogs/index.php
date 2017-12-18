<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/15/2017
 * Time: 3:39 PM
 */
require_once '../sys/inc/start.php';
require_once '../sys/plugins/classes/blogs.categories.class.php';
$doc = new document();
$doc->title = __('Blogs categories');

$pages = new pages;

$pages->posts = data::getCountOfRows('blogs_categories');
$q = data::getDataWithLimit('blogs_categories', $pages->limit);

$listing = new listing();
if ($arr = $q->fetchAll()) {
    foreach ($arr AS $cat) {
        $post = $listing->post();
        //$ank = new user((int) $cat['id_user']);
        $post->icon('news');
        $post->content = text::toOutput($cat['description']);
        $post->title = text::toValue($cat['name']);
        $post->url = 'category.php?id=' . $cat['id'];
        //$post->time = misc::when($cat['time']);
        //$post->bottom = '<a href="/profile.view.php?id=' . $cat['id_user'] . '">' . $ank->nick() . '</a>';
    }
}
$listing->display(__('Категории отсутствуют'));
$pages->display('?'); // вывод страниц
