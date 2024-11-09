<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value ?: ($where[$name] ?? null) ?>

<select name="<?= $this->e($name) ?>">
    <option value=""></option>
    <?php foreach ($options as $option_value => $option_title): ?>
        <option value="<?= $this->e($option_value) ?>"
            <?php if ($value == $option_value): ?>
                selected="selected"
            <?php endif ?>
        >
            <?= $this->e($option_title) ?>
        </option>
    <?php endforeach ?>
</select>
