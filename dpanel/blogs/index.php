<?php
include_once '../../sys/inc/start.php';
$doc = new document(2);
$doc->title = __('Управление блогами');
$doc->ret(__('Админка'), '../');
if(!$user->access('news_dopusk')) $doc->access_denied(__('У Вас нет доступа!'));
$menu = new menu_ini('blogs');
$menu->display();