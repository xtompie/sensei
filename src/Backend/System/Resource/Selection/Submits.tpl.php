<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $selection = $this->service(\App\Backend\System\Resource\Selection::class) ?>
<?php if ($selection->enabled()) : ?>
    <?= $this->import('/src/Backend/System/Js/selection.tpl.php') ?>
    <div>
        <button
            type="button"
            onclick="backend.selection.commit(this, '<?php $this->e($resource) ?>')"
        >
            Select
        </button>
        <button
            type="button"
            onclick="backend.selection.cancel('<?php $this->e($resource) ?>')"
        >
            Cancel
        </button>
    </div>
<?php endif ?>
