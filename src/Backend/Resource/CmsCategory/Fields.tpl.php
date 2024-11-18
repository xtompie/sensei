<?php /** @var App\Shared\Tpl\Tpl $this */ ?>

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
        'name' => 'title',
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        'type' => 'Relone',
        'name' => 'category_id',
        'reltype' => 'CmsCategory',
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>

<?php if (in_array($action, ['list', 'detail', 'create', 'update'])) : ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Field.tpl.php', [
        'type' => 'Text',
        'name' => 'index',
        ...get_defined_vars(),
    ]) ?>
<?php endif ?>
