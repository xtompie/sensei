<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $label = isset($label) ? $label : $name ?>

<dt class="text-sm font-medium leading-6 text-gray-900">
    <?= $this->e($label) ?>
</dt>