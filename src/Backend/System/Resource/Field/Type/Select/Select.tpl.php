<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value[$name] ?? null ?>

<?php if ($mode == 'list'): ?>
    <?php if (isset($options[$value])): ?>
        <?= $this->e($options[$value]) ?>
    <?php else: ?>
        <?= $this->e($value) ?>
    <?php endif ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?php if (isset($options[$value])): ?>
        <?= $this->e($options[$value]) ?>
    <?php else: ?>
        <?= $this->e($value) ?>
    <?php endif ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php else: ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
        <select
            name="<?= $this->e($name) ?>"
            class="block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-400"
        >
            <?php foreach ($options as $option_value => $option_title): ?>
            <option
                value="<?= $this->e($option_value) ?>"
                <?php if ($value == $option_value): ?>
                    selected="selected"
                <?php endif ?>
            >
                <?= $this->e($option_title) ?>
            </option>
            <?php endforeach ?>
        </select>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>
