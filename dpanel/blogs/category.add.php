<?php
include_once '../../sys/inc/start.php';
dpanel::check_access();
$doc = new document(2);
if(!$user->access('news_add')) $doc->access_denied(__('У Вас нет доступа!'));
$doc->title = __('Создание категории');
$doc->ret(__('Админка'), '../');
$doc->ret(__('К блогам'), '/blogs/');

if (isset($_POST['send']) && isset($_POST['title']) && isset($_POST['text'])) {
    $title = text::for_name($_POST['title']);
    $text = text::input_text($_POST['text']);
  
    if (!$title){
        $doc->err(__('Заполните "Название категории"'));
    }elseif (!$text) {
        $doc->err(__('Заполните "Полное описание категории"'));
    }else {
        $res = data::createBlogsCategory($title, $text);
        if($res){
            $doc->msg(__('Категория успешно создана'));
            $id = $db->lastInsertId();
            $dcms->log('Блоги', 'Создание категории '.$title);
            //header('Refresh: 1; /news/comments.php?id='.$id);
            exit;
        }
    }
} 
$form = new form('?' . passgen());
$form->text('title', __('Название категории'));
$form->textarea('text', __('Описание категории'));
$form->button(__('Создать'), 'send', false);
$form->display();
