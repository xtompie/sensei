<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>
<?php $class = isset($class) ? $class : '' ?>
<?php $err = $errors->space($name) ?>

<div class="mt-6">

    <?= $this->render('/src/Backend/System/Form/Label.tpl.php', get_defined_vars()) ?>

    <input
        type="password"
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
            <?= $this->e($class) ?>
        "
    />

    <?= $this->render('/src/Backend/System/Form/Errors.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Desc.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Link.tpl.php', get_defined_vars()) ?>

</div>