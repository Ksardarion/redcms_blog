<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/28/2017
 * Time: 5:46 PM
 */
require_once '../../sys/inc/start.php';
require_once '../../sys/plugins/classes/blogs.class.php';
require_once '../../sys/plugins/classes/blogs.comments.class.php';

$doc = new document(1);
if (!isset($_GET ['id']) || !is_numeric($_GET ['id'])) {
    $doc->toReturn('./');
    $doc->err(__('Ошибка выбора комментария'));
    exit();
}
$comment = data::getRowById('blogs_comments', $_GET['id']);

if (!$comment['id']) {
    $doc->toReturn('./');
    $doc->err(__('Комментарий не найден'));
    exit();
}
if(!$user->access('blogs_delete_comments') && $comment['userId'] !== $user->id) {
    if (isset($_GET ['return']))
        $doc->ret(__('Вернуться'), text::toValue($_GET['return']));
    else
        $doc->ret(__('Вернуться'), '../');
    $doc->access_denied(__('У Вас нет доступа!'));
}

data::deleteRowById('blogs_comments',$comment['id']);
$doc->msg(__('Комментарий успешно удален'));