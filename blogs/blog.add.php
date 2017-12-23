<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/18/2017
 * Time: 3:06 AM
 */

require_once '../sys/inc/start.php';

$doc = new document(1);

$doc->title = __('Создание блога');

$doc->ret(__('К категориям'), '/blogs/');

$categoryId = $_GET['id_category'];

$title = '';
$text = '';
$preview = '';

if (isset($_POST['send']) && isset($_POST['title']) && isset($_POST['text'])) {
    $categoryId = (int) $_GET['id_category'];
    $title = text::for_name($_POST['title']);
    $text = text::input_text($_POST['text']);
    $preview = text::input_text($_POST['preview']);

    if (!$title){
        $doc->err(__('Заполните "Название блога"'));
    }elseif (!$text) {
        $doc->err(__('Заполните "Содержание блога"'));
    }else {
        $res = blogs::create($title, $text, $preview, TIME, $user->id, $categoryId);
        if($res){
            $doc->msg(__('Блог успешно создан'));
            exit;
        }
    }
}

if(isset($_POST['blog_preview']) && isset($_POST['title']) && isset($_POST['text'])){

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
}

$form = new form('?id_category='. $categoryId . '&' . passgen());
$form->text('title', __('Название блога'), $title);
$form->textarea('preview', __('Предпросмотр'), $preview);
$form->textarea('text', __('Содержание блога'), $text);
$form->button(__('Создать'), 'send', false);
$form->button(__('Предпросмотр'), 'blog_preview', false);
$form->display();
