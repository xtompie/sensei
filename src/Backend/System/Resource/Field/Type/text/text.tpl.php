<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : null ?>

<?php if ($action == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($action == 'detail'): ?>
    <?= $this->render('src/Backend/System/Resource/Field/Detail/begin.tpl.php') ?>
    <?= $this->e($value) ?>
    <?= $this->render('src/Backend/System/Resource/Field/Detail/end.tpl.php') ?>
<?php else: ?>
    <?= $this->render('src/Backend/System/Resource/Field/Form/begin.tpl.php') ?>
        <input
            type="text"
            name="<?= $this->e($name) ?>"
            value="<?= $this->e($value) ?>"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-400"
        />
    <?= $this->render('src/Backend/System/Resource/Field/Form/end.tpl.php') ?>
<?php endif ?>