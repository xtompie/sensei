<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\Admin\Pilot::class) ?>

<?php if (in_array($action, ['list', 'detail'])) : ?>
    <?= $this->render($field, [
        'name' => 'id',
        'more' => true,
        'sort' => true,
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render($field, [
        'type' => 'text',
        'name' => 'email',
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['create', 'update', 'detail'])) : ?>
    <?= $this->render($field, [
        'type' => 'select',
        'name' => 'role',
        'options' => $pilot->roles(),
    ]) ?>
<?php endif ?>
