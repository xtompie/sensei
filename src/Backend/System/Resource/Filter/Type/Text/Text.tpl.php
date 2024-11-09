<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value ?: ($where[$name] ?? null) ?>

<input
    type="text"
    name="{{ name }}"
    value="{{ value }}"
/>
