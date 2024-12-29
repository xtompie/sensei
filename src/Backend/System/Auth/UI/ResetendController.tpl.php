<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', [
    'layout_clean' => true,
    'title' => $this->t('backend.Reset password'),
]) ?>

<?= $this->render('/src/Backend/System/Layout/Title.tpl.php', [
    'text' => $this->t('backend.Reset password'),
]) ?>

<?= $this->render('/src/Backend/System/Form/Begin.tpl.php', get_defined_vars()) ?>

<?= $this->render('/src/Backend/System/Form/Field.tpl.php', [
    'errors' => $errors,
    'value' => $value,
    'type' => 'Password',
    'name' => 'password',
]) ?>

<?= $this->render('/src/Backend/System/Form/Field.tpl.php', [
    'errors' => $errors,
    'value' => $value,
    'type' => 'Password',
    'name' => 'password_confirm',
]) ?>

<?= $this->render('/src/Backend/System/Form/Submit.tpl.php', [
    'label' => 'backend.Reset password',
]) ?>

<?= $this->render('/src/Backend/System/Form/End.tpl.php', get_defined_vars()) ?>