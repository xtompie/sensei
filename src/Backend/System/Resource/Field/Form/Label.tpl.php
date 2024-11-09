<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $label = isset($label) ? $label : $name ?>

<label class="block text-sm font-medium leading-6 text-gray-900 mb-2">
    <?= $this->e($label) ?>
</label>
