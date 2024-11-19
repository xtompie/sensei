<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<div class="mt-6">
    <button
        type="submit"
        name="_submit[commit]"
        class="
            inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-200
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
</div>
