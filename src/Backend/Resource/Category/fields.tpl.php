<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\Admin\Pilot::class) ?>

<?php if (in_array($action, ['list', 'detail'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/field.tpl.php', [
        'name' => 'id',
        'more' => true,
        'sort' => true,
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/field.tpl.php', [
        'type' => 'text',
        'name' => 'title',
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/field.tpl.php', [
        'type' => 'relone',
        'name' => 'category_id',
        'reltype' => 'category',
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/field.tpl.php', [
        'type' => 'relmany',
        'name' => 'children',
        'reltype' => 'category',
    ]) ?>
<?php endif ?>