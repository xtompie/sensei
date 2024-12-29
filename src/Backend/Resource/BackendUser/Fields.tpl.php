<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\BackendUser\Pilot::class) ?>

<?php if (in_array($action, ['list', 'detail'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        ...get_defined_vars(),
        'name' => 'id',
        'more' => true,
        'sort' => true,
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        ...get_defined_vars(),
        'type' => 'Text',
        'name' => 'email',
        'more' => true,
        'class' => 'w-[480px]',
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'create', 'update', 'detail'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        ...get_defined_vars(),
        'type' => 'Select',
        'name' => 'role',
        'options' => $pilot->roles(),
    ]) ?>
<?php endif ?>
