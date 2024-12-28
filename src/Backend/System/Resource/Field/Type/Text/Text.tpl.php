<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>

<?php if ($mode == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->e($value) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode == 'form'): ?>
    <?php $err = $errors->space($name) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
    <input
        type="text"
        name="<?= $this->e($name) ?>"
        value="<?= $this->e($value) ?>"
        placeholder="<?= $this->e($placehoder ?? '') ?>"
        class="
            flex mt-2 px-3 py-2 w-full border rounded-md bg-transparent
            text-sm
            placeholder:text-gray-400 focus-visible:outline-none focus:border-gray-400
            disabled:cursor-not-allowed disabled:opacity-50
            <?php if ($err->none()): ?>
                border-gray-200
            <?php else: ?>
                border-red-400
            <?php endif ?>
        "
    />
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>