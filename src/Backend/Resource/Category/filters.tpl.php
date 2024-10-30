<?php /** @var App\Shared\Tpl\Tpl $this */ ?>

<?= $this->render('/src/Backend/System/Resource/Filter/filter.tpl.php', [
    'type' => 'text',
    'name' => 'id:match',
]) ?>
