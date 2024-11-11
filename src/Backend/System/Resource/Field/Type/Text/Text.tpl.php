<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>

<?php if ($mode == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->e($value) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode == 'form'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
        <input
            type="text"
            name="<?= $this->e($name) ?>"
            value="<?= $this->e($value) ?>"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-400"
        />
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>