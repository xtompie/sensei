<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $fields = $fields ?? "/src/Backend/Resource/$resource/Fields.tpl.php" ?>

<?php $this->push('/src/Backend/System/Layout/Layout.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Title/Title.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/Filters.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Nodata/Nodata.tpl.php', [
    'entities' => $entities,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Field/List/List.tpl.php', [
    'fields' => $fields,
    'resource' => $resource,
    'entities' => $entities,
    'order' => $order,
]) ?>

