<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\Admin\Pilot::class) ?>

<?= $this->render($filter, [
    'type' => 'text',
    'name' => 'email:match',
]) ?>

<?= $this->render($filter, [
    'type' => 'select',
    'name' => 'role',
    'options' => $pilot->roles(),
]) ?>
