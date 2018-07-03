<?php
use yii\bootstrap\Html;
?>
Доброго времени суток, <?=$model->username?><br>
Вы заргеистрировались на сайте <b><?=getenv("HTP_HOST")?><br>
    Ваш пароль: <b><?=$password?></b><br>
    Чтобы активировать Вашу учетную записаь необходимо перейти по ссылке:<br>
    <a href="http://<?=getenv("HTTP_HOST")?>/user/confirm/<?=$model->username?>/<?=$model->actionKey?>">
        http://<?=getenv("HTTP_HOST")?>/user/confirm/<?=$model->username?>/<?=$model->actionKey?>
        </a>
    <br>
    Если Вы не регистрировались на сайте, просто удалите это сообщение.
