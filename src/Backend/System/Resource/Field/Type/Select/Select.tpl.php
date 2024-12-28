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
            class="
                flex mt-2 px-3 py-2 border border-gray-200 rounded-md bg-transparent
                text-sm
                placeholder:text-gray-400 focus-visible:outline-none focus:border-gray-400
                disabled:cursor-not-allowed disabled:opacity-50
            "
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
