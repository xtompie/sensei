<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<button
    type="submit"
    name="_submit[commit]"
    class="
        btn
        {% if action == 'delete' %}
            btn-danger
        {% else %}
            btn-primary
        {% endif %}
    "
>
    <?php if ($action == 'create'): ?>
        Save
    <?php elseif ($action == 'update'): ?>
        Save
    <?php elseif ($action == 'delete'): ?>
        Delete
    <?php else : ?>
        Save
    <?php endif ?>
</button>
