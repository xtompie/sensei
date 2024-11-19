<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\BackendUser\Pilot::class) ?>

<?php if (in_array($action, ['list', 'detail'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        'name' => 'id',
        'more' => true,
        'sort' => true,
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        'type' => 'Text',
        'name' => 'email',
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['create', 'update', 'detail'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        'type' => 'Select',
        'name' => 'role',
        'options' => $pilot->roles(),
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>
