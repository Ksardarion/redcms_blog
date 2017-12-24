<?php
/**
 * Created by PhpStorm.
 * User: coolk_000
 * Date: 12/24/2017
 * Time: 11:00 AM
 */
if(isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['text'])){
    $categoryId = (int) $_GET['id_category'];
    $title = text::for_name($_POST['title']);
    $text = text::input_text($_POST['text']);
    $preview = text::input_text($_POST['preview']);

    if (!$title){
        $doc->err(__('Заполните "Название блога"'));
    }elseif (!$text) {
        $doc->err(__('Заполните "Содержание блога"'));
    }else {
        $res = blogs::edit($title, $text, $preview);
        if($res){
            $doc->msg(__('Блог успешно отредактирован'));
            exit;
        }
    }
}