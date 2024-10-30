<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

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

<?php $selection = $this->service(\App\Backend\System\Resource\Selection::class) ?>
<?= $this->render('/src/Backend/System/Resource/Field/List/list.tpl.php', [
    'list_link' => !$selection->enabled(),
    'list_more' => !$selection->enabled(),
    'list_selection' => $selection->enabled(),
    'list_selection_single' => $selection->single(),
    'list_sort' => !$selection->enabled(),
    'values' => $values,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Seleciton/submits.tpl.php') ?>
