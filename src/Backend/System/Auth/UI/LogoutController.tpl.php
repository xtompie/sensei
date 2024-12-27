<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', [
    'layout_clean' => true,
    'title' => $this->t('backend.Logout'),
]) ?>

<?= $this->render('/src/Backend/System/Layout/H2.tpl.php', [
    'text' => $this->t('backend.Logout'),
]) ?>

<?= $this->render('/src/Backend/System/Form/Begin.tpl.php', get_defined_vars()) ?>

<?= $this->render('/src/Backend/System/Form/Submit.tpl.php', [
    'label' => 'backend.Logout',
]) ?>

<?= $this->render('/src/Backend/System/Form/End.tpl.php', get_defined_vars()) ?>