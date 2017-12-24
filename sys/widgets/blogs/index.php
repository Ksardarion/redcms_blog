<?php
defined('DCMS') or die;
global $user;
$db = DB::me();

$blogs = data::getDataWithLimit('blogs', 3);

$listing = new listing('title_box');
$post = $listing->post('title_box');
$post->title = __('Блоги');
$post->url = '/blogs/';
$post->fa_icon = 'comment';
$listing->display();

$listing = new listing();
while($blog = $blogs->fetch()){
    $category = data::getRowById('blogs_categories', $blog['category']);
    $post = $listing->post();
    $post->title = __($blog['title'] . ', ' . misc::when($blog['time']));
    $author = new user($blog['author']);
//    $post->content = text::toOutput(blogs::getPreview($blog['id']));
    $post->content = 'Автор: ' . $author->nick . '<br />';
    $post->content .= 'Категория: <a href="/blogs/category.php?id=' . $category['id'] . '">' . $category['name'] . '</a><br />';
    $post->url = '/blogs/blog.php?id=' . $blog['id'];
    $post->bottom = __('Просмотров: %s', data::getCountOfRows('blogs_views', 'id_blog=' . $blog['id'])).' | Комментариев: ' . data::getCountOfRows('blogs_comments', 'blogId=' . $blog['id']);
}
$listing->display(__('Пока никто не создавал блоги'));