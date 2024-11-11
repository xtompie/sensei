<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value[$name] ?? null ?>
<?php if ($mode == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->e($value) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode == 'form'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->e($value) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>