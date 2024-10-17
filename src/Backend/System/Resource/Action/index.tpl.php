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

<?= $this->render('/src/Backend/System/Resource/Field/List/list.tpl.php', [
    'list_link' => !$this->backend()->selection()->enabled(),
    'list_more' => !$this->backend()->selection()->enabled(),
    'list_selection' => $this->backend()->selection()->enabled(),
    'list_selection_single' => $this->backend()->selection()->single(),
    'list_sort' => !$this->backend()->selection()->enabled(),
    'values' => $values,
]) ?>

<?= $this->render('/src/Backend/System/Resource/Seleciton/submits.tpl.php') ?>
