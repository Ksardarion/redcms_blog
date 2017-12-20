<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/18/2017
 * Time: 1:41 AM
 */

require_once '../sys/inc/start.php';
require_once '../sys/plugins/classes/blogs.categories.class.php';

$doc = new document();

$id = $_GET['id'];
$category = data::getRowById('blogs_categories', $id);

if(empty($category)){
    $doc->ret(__('К категориям'), '/blogs/');
    $doc->err(__('Категория не существует'));
    exit();
}

$doc->title = __($category['name']);
$doc->ret(__('Блоги'), '/blogs/');

$listing = new listing();

$post = $listing->post();
$post->title = __('Category <b>' . $category['name'] . '</b>');
$post->icon('info');
$post->content = text::toOutput($category['description']);

$listing->display();

$pages = new pages;

$pages->posts = data::getCountOfRows('blogs');
$q = data::getDataWithRelAndLimit('blogs', 'category', $category['id'], $pages->limit);

$listing = new listing();
if ($arr = $q->fetchAll()) {
    foreach ($arr AS $blog) {
        $preview = ($blog['preview']) ? $blog['preview'] : mb_strimwidth($blog['content'], 0, 1000);
        $post = $listing->post();
        $ank = new user((int) $blog['author']);
        $post->icon('code');
        $post->content = text::toOutput(blogs::getPreview($blog['id']));
        $post->title = text::toValue($blog['title']);
        $post->url = 'blog.php?id=' . $blog['id'];
        $post->time = misc::when($blog['time']);
        $post->bottom = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a>';
    }
}
$listing->display(__('Блоги отсутствуют'));
$pages->display('?');

$doc->act(__('Создать блог'), 'blog.add.php?id_category=' . $category['id'] . "&amp;return=" . URL);