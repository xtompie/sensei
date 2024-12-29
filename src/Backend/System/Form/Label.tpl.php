<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $label = isset($label) ? $label : ucfirst($name) ?>

<label
    class="
        text-sm font-medium leading-none
        <?php if (isset($err) && $err->any()): ?>
            text-red-500
        <?php endif ?>

    ">
    <?= $this->e($label) ?>
</label>
