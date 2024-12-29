<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', [
    'layout_clean' => true,
    'title' => $this->t('backend.Login'),
]) ?>

<?= $this->render('/src/Backend/System/Layout/Title.tpl.php', [
    'text' => $this->t('backend.Login'),
]) ?>

<?= $this->render('/src/Backend/System/Form/Begin.tpl.php', get_defined_vars()) ?>

<?= $this->render('/src/Backend/System/Form/Text.tpl.php', [
    'errors' => $errors,
    'value' => $value,
    'name' => 'email',
    'class' => 'w-[400px]',
]) ?>

<?= $this->render('/src/Backend/System/Form/Password.tpl.php', [
    'errors' => $errors,
    'value' => $value,
    'name' => 'password',
    'class' => 'w-[400px]',
    'link' => [
        'uri' => $this->url(\App\Backend\System\Auth\UI\ResetController::class),
        'text' => $this->t('backend.Reset password'),
    ],
]) ?>

<?= $this->render('/src/Backend/System/Form/Submit.tpl.php', [
    'label' => 'backend.Login',
]) ?>

<?= $this->render('/src/Backend/System/Form/End.tpl.php', get_defined_vars()) ?>