<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $label = $label ?? 'backend.Submit' ?>


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
        <?= $this->e($this->t($label)) ?>
    </button>
</div>
