<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', [
    'layout_clean' => true,
    'title' => $this->t('backend.Login'),
]) ?>

<?= $this->render('/src/Backend/System/Form/Begin.tpl.php', get_defined_vars()) ?>

<?= $this->render('/src/Backend/System/Form/Field.tpl.php', [
    'errors' => $errors,
    'value' => $value,
    'type' => 'Text',
    'name' => 'email',
]) ?>


<?= $this->render('/src/Backend/System/Form/Submit.tpl.php', [
    'label' => 'backend.Reset password',
]) ?>

<?= $this->render('/src/Backend/System/Form/End.tpl.php', get_defined_vars()) ?>