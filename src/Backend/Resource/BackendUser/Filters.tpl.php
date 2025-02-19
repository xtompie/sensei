<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\Resource\BackendUser\Pilot::class) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/Filter.tpl.php', [
    'type' => 'Text',
    'name' => 'email:match',
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/Filter.tpl.php', [
    'type' => 'Select',
    'name' => 'role',
    'options' => $pilot->roles(),
    ...get_defined_vars(),
]) ?>
