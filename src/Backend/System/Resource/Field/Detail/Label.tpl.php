<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $label = isset($label) ? $label : ucfirst($name) ?>

<div
    class="
        text-sm font-medium leading-none
    "
>
    <?= $this->e($label) ?>
</div>