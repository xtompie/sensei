<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $this->wrap('/src/Example/UI/Tpl/head.tpl.php', ['title' => $title]) ?>
<div class="container">
    <?= $this->render('/src/Example/UI/Tpl/navbar.tpl.php') ?>
    <?= $this->content() ?>
</div>
