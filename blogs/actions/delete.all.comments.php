<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/24/2017
 * Time: 1:16 AM
 */
require_once '../../sys/inc/start.php';
require_once '../../sys/plugins/classes/blogs.class.php';
require_once '../../sys/plugins/classes/blogs.comments.class.php';

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
if(!$user->access('blogs_delete_comments') && $blog['author'] !== $user->id) {
    if (isset($_GET ['return']))
        $doc->ret(__('Вернуться'), text::toValue($_GET ['return']));
    else
        $doc->ret(__('Вернуться'), '../');
    $doc->access_denied(__('У Вас нет доступа!'));
}

blogComments::deleteAllComments($blog['id']);
$doc->msg(__('Комментарии успешно очищены'));

$doc->ret(__('Блог ' . $blog['title']), '../blog.php?id=' . $blog['id']);