<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/18/2017
 * Time: 5:12 PM
 */

require_once '../sys/inc/start.php';
require_once '../sys/plugins/classes/blogs.categories.class.php';
require_once '../sys/plugins/classes/blogs.comments.class.php';
require_once '../sys/plugins/classes/blogs.class.php';

if (AJAX)
    $doc = new document_json();
else
    $doc = new document();

if (!isset($_GET ['id']) || !is_numeric($_GET ['id'])) {
    $doc->toReturn('./');
    $doc->err(__('Ошибка выбора блога'));
    exit();
}

$id = $_GET['id'];
$blog = data::getRowById('blogs', $id);

if(empty($blog)){
    $doc->ret(__('В категорию'), '/blogs/category.php?id=' . $blog['category']);
    $doc->err(__('Блог не существует'));
    exit();
}

if(!blogs::checkViewer($user->id, $blog['id']) && $user->id != 0){
    blogs::addViewer($blog['id'], $user->id, TIME);
}
$listing = new listing();
$post = $listing->post();
$ank = new user((int) $blog['author']);
$post->image = $ank->ava();
$post->content = text::toOutput($blog['content']);
$post->title = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a> <b>' . text::toValue($blog['title']) . '</b>';
$post->time = misc::when($blog['time']);
if ($user->access('blogs_edit_blog') || $user->id == $blog['author'])
    $post->action('edit', 'blog.edit.php?id='.$blog['id'].'' );
if($user->access('blogs_delete_blog') || $user->id == $blog['author'])
    $post->action('delete', 'actions/delete.blog.php?id='.$blog['id'].'' );
//$post->bottom = '<a href="/profile.view.php?id=' . $blog['author'] . '">' . $ank->nick() . '</a>';
$listing->display();

$pages = new pages(data::getCountOfRows('blogs_comments', '`blogId` = ' . $blog['id']));
$can_write = true;
if (!$user->is_writeable) {
    $doc->msg(__('Писать запрещено'), 'write_denied');
    $can_write = false;
}
if ($can_write && $pages->this_page == 1) {
    if (isset($_POST['send']) && isset($_POST['message']) && isset($_POST['token']) && $user->group) {
        $message = (string)$_POST['message'];
        $users_in_message = text::nickSearch($message);
        $message = text::input_text($message);
        if (!antiflood::useToken($_POST['token'], 'blogs_comments' . $blog['id'])) {
// нет токена (обычно, повторная отправка формы)
        } elseif ($dcms->censure && $mat = is_valid::mat($message)) {
            $doc->err(__('Обнаружен мат: %s', $mat));
        } elseif ($message) {
            $user->balls += $dcms->add_balls_chat ;
            blogComments::addComment($user->id, TIME, $message, $blog['id']);
            header('Refresh: 1; url=?id=' . $blog['id'] . '&' . passgen() . '&' . SID);
            $doc->ret(__('Вернуться'), '?id=' . $blog['id'] . '&' . passgen());
            $doc->msg(__('Сообщение успешно отправлено'));
            if ($doc instanceof document_json) {
                $doc->form_value('message', '');
                $doc->form_value('token', antiflood::getToken('blogs_comments' . $blog['id']));
            }
            exit;
        } else {
            $doc->err(__('Сообщение пусто'));
        }
        if ($doc instanceof document_json)
            $doc->form_value('token', antiflood::getToken('blogs_comments' . $blog['id']));
    }
    if ($user->group) {
        $message_form = '';
        if (isset($_GET ['message']) && is_numeric($_GET ['message'])) {
            $id_message = (int)$_GET ['message'];

            $q = data::getRowById('blogs_comments', $id_message);
            if ($message = $q->fetch()) {
                $ank = new user($message['userId']);
                if (isset($_GET['reply'])) {
                    $message_form = '@' . $ank->login . ',';
                } elseif (isset($_GET['quote'])) {
                    $message_form = "[quote id_user=\"{$ank->id}\" time=\"{$message['time']}\"]{$message['message']}[/quote]";
                }
            }
        }
        if (!AJAX) {
            $form = new form('?id=' . $blog['id'] . '&' . passgen());
            $form->refresh_url('?id=' . $blog['id'] . passgen());
            $form->setAjaxUrl('?id=' . $blog['id']);
            $form->hidden('token', antiflood::getToken('blogs_comments' . $blog['id']));
            $form->textarea('message', __('Сообщение'), $message_form, true);
            $form->button(__('Отправить'), 'send', false);
            $form->display();
        }
    }
}
$doc->title = __($blog['title']);

$doc->ret(__('В категорию'), '/blogs/category.php?id=' . $blog['category']);



$listing = new listing();

if (!empty($form))
    $listing->setForm($form);
$q = data::getDataWithRelAndLimit('blogs_comments', 'blogId', $blog['id'], $pages->limit);
$after_id = false;
if ($arr = $q->fetchAll()) {
    foreach ($arr AS $message) {
        $ank = new user($message['userId']);
        $post = $listing->post();
        $post->id = 'blogs_comments_' . $blog['id'] . '_mess_' . $message['id'];
        if($user->id)$post->url = '?message=' .$message['id'] . '&amp;reply';
        $post->time = misc::when($message['time']);
        $post->title = $ank->nick();
        $post->image = $ank->ava();
        $post->post = text::toOutput($message['message']);

        if($user->id) {
            $post->action('quote', '?message=' . $message['id'] . '&amp;quote');
            if($user->access('blogs_delete_comments'))
                $post->action('delete', 'actions/delete.blog.comment.php?id='.$message['id'].'' );
            $post->action('user.1', '/profile.view.php?id='.$ank->id);
        }

        if (!$doc->last_modified)
            $doc->last_modified = $message['time'];
        if ($doc instanceof document_json)
            $doc->add_post($post, $after_id);
        $after_id = $post->id;
    }
}
if ($doc instanceof document_json && !$arr){
    $post = new listing_post(__('Сообщения отсутствуют'));
    $post->icon('empty');
    $doc->add_post($post);
}
$listing->setAjaxUrl('?id=' . $blog['id'] . '&page=' . $pages->this_page);
$listing->display(__('Сообщения отсутствуют'));
$pages->display('?id=' . $blog['id']); // вывод страниц
if ($doc instanceof document_json)
    $doc->set_pages($pages);
if($user->access('blogs_delete_comments') || $blog['author'] == $user->id)
    $doc->act(__('Очистить комментарии'), 'actions/delete.all.comments.php?id=' . $blog['id']);
