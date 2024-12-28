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
        <textarea
            name="<?= $this->e($name) ?>"
            class="
                flex mt-2 px-3 py-2 border border-gray-200 rounded-md bg-transparent
                text-sm
                placeholder:text-gray-400 focus-visible:outline-nonefocus:border-gray-400
                disabled:cursor-not-allowed disabled:opacity-50
            "
        ><?= $this->e($value) ?></textarea>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>