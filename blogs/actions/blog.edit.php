<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/24/2017
 * Time: 11:00 AM
 */
require_once '../../sys/inc/start.php';
require_once '../../sys/plugins/classes/blogs.class.php';
require_once '../../sys/plugins/classes/blogs.comments.class.php';

$doc = new document(1);
if(isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['text'])){

    $title = text::for_name($_POST['title']);
    $text = text::input_text($_POST['text']);
    $preview = text::input_text($_POST['preview']);

    if (!$title){
        $doc->err(__('Заполните "Название блога"'));
    }elseif (!$text) {
        $doc->err(__('Заполните "Содержание блога"'));
    }else {
        $res = blogs::editBlog($_GET['id'], $title, $text, $preview);
        if($res){
            $doc->msg(__('Блог успешно отредактирован'));
            exit;
        }
    }
} elseif (isset($_POST['blog_preview']) && isset($_POST['title']) && isset($_POST['text'])){

    $title = text::for_name($_POST['title']);
    $text = text::input_text($_POST['text']);
    $preview = text::input_text($_POST['preview']);

    $listing = new listing();
    $post = $listing->post();

    $post->image = $user->ava();
    $post->content = text::toOutput($_POST['text']);
    $post->title = '<a href="/profile.view.php?id=' . $user->id . '">' . $user->nick() . '</a> <b>' . text::toValue($_POST['title']) . '</b>';
    $post->time = misc::when(TIME);
    $post->action('delete', 'actions/delete.blog.php?id=' );
//$post->bottom = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a>';
    $listing->display();
} else {
    $doc->err(__('Действие не определено, обратитесь в службу поддержки'));
}