<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?= $this->render('/src/Backend/System/Resource/Title/title.tpl.php', [
    'action' => $action,
    'more' => $more,
    'title' => $title,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/begin.tpl.php', [
]) ?>

<?= $this->render($fields, [
    'action' => $action,
    'value' => $value,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/end.tpl.php', [
    'action' => $action,
    'resource' => $resource,
]) ?>
