<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $label = $label ?? 'backend.Submit' ?>

<div class="col-span-full">

    <div class="mt-6">
        <button type="submit" name="_submit[commit]" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-200"
        >
            <?= $this->e($this->t($label)) ?>
        </button>
    </div>
</div>
