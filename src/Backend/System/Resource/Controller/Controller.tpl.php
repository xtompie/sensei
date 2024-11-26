<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $fields = $fields ?? "/src/Backend/Resource/$resource/Fields.tpl.php" ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', get_defined_vars()) ?>

<?= $this->render('/src/Backend/System/Resource/Title/Title.tpl.php', get_defined_vars()) ?>

<?php if ($mode === 'index'): ?>

    <?= $this->render('/src/Backend/System/Resource/Filter/Filters.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Resource/Nodata/Nodata.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/List/List.tpl.php', get_defined_vars()) ?>

<?php elseif ($mode === 'detail'): ?>

    <?= $this->render('/src/Backend/System/Resource/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->render($fields, get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Resource/Detail/End.tpl.php', get_defined_vars()) ?>

<?php elseif ($mode === 'form'): ?>

    <?= $this->render('/src/Backend/System/Resource/Form/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->render($fields, get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Resource/Form/End.tpl.php', get_defined_vars()) ?>

<?php endif ?>
