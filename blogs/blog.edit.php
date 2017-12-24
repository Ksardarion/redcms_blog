<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/24/2017
 * Time: 10:57 AM
 */

require_once '../sys/inc/start.php';
require_once '../sys/plugins/classes/blogs.class.php';
require_once '../sys/plugins/classes/blogs.comments.class.php';

$doc = new document(1);

if (!isset($_GET ['id']) || !is_numeric($_GET ['id'])) {
    $doc->toReturn('./');
    $doc->err(__('Ошибка выбора блога'));
    exit();
}

$blog = data::getRowById('blogs', $_GET['id']);

if (!$blog['id']) {
    $doc->toReturn('./');
    $doc->err(__('Блог не найден'));
    exit();
}

$doc->title = ('Редактирование блога - ' . $blog['title']);

$title = $blog['title'];
$preview = $blog['preview'];
$text = $blog['content'];

$form = new form('actions/blog.edit.php?id=' . $blog['id']);
$form->text('title', __('Название блога'), $title);
$form->textarea('preview', __('Предпросмотр'), $preview);
$form->textarea('text', __('Содержание блога'), $text);
$form->button(__('Изменить'), 'edit', false);
//$form->button(__('Предпросмотр'), 'blog_preview', false);
$form->display();