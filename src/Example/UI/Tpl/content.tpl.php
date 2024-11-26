<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $this->wrap('/src/Example/UI/Tpl/layout.tpl.php', ['title' => $title]) ?>
<h1><?= $this->e($title) ?></h1>
<?= $this->render('/src/Example/UI/Tpl/navbar.tpl.php') ?>

<?php  dump(get_defined_vars()) ?>

<?= $this->e($this->service(\App\Example\UI\Presenter\ExamplePresenter::class)->foo()) ?>
