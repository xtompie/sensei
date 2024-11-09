<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\Admin\Pilot::class) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/filter.tpl.php', [
    'type' => 'text',
    'name' => 'email:match',
]) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/filter.tpl.php', [
    'type' => 'select',
    'name' => 'role',
    'options' => $pilot->roles(),
]) ?>
