<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value ?? ($where[$name] ?? '') ?>

<input
    type="text"
    name="<?= $this->e($name) ?>"
    value="<?= $this->e($value) ?>"
/>
