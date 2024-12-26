<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \Xtompie\Result\ErrorCollection $errors */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>

<input
    type="password"
    name="<?= $this->e($name) ?>"
    value="<?= $this->e($value) ?>"
    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-400"
/>
