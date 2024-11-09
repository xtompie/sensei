<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?= $this->render('/src/Backend/System/Resource/Title/title.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/begin.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render($fields, [
    'mode' => 'form',
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/end.tpl.php', [
    ...get_defined_vars(),
]) ?>
