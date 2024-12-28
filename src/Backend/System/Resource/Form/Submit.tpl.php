<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<div class="mt-8">
    <button
        type="submit"
        name="_submit[commit]"
        class="
            flex px-6 py-2 items-center rounded-md justinfy-center
            text-white text-nowrap bg-gray-900 text-sm
            hover:bg-gray-800
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
