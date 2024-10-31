<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $selection = $this->service(\App\Backend\System\Resource\Selection::class) ?>

<?= $this->render('/src/Backend/System/Resource/Title/title.tpl.php', [
    'action' => $action,
    'more' => $more,
    'title' => $title,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Filter/filters.tpl.php', [
    'filters' => $filters,
    'where' => $where,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Nodata/nodata.tpl.php', [
    'values' => $values,
]) ?>


<?= $this->render('/src/Backend/System/Resource/Field/List/list.tpl.php', [
    'fields' => $fields,
    'list_link' => !$selection->enabled(),
    'list_more' => !$selection->enabled(),
    'list_selection' => $selection->enabled(),
    'list_selection_single' => $selection->single(),
    'list_sort' => !$selection->enabled(),
    'resource' => $resource,
    'values' => $values,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Selection/submits.tpl.php', [
    'resource' => $resource,
]) ?>
