<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/20/2017
 * Time: 10:03 PM
 */
require_once '../../sys/inc/start.php';
require_once '../../sys/plugins/classes/blogs.class.php';

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

if(!$user->access('blogs_delete_blog') && $blog['author'] !== $user->id) {
    if (isset($_GET ['return']))
        $doc->ret(__('Вернуться'), text::toValue($_GET ['return']));
    else
        $doc->ret(__('Вернуться'), '../');
    $doc->access_denied(__('У Вас нет доступа!'));
}

blogs::deleteBlog($blog['id']);
$doc->msg(__('Блог успешно удален'));

if (isset($_GET ['return']))
    $doc->ret(__('Вернуться'), text::toValue($_GET ['return']));
else
    $doc->ret(__('Вернуться'), './');