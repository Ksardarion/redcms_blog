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

    $post = $listing->post();
    $post->title = __($blog['title']);
    $post->content = text::toOutput(blogs::getPreview($blog['id']));
    $post->url = '/blogs/blog.php?id=' . $blog['id'];

}
$listing->display(__('Пока никто не создавал блоги'));