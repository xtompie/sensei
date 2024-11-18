<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $selection = $this->service(\App\Backend\System\Resource\Selection\Selection::class) ?>
<?php if ($selection->enabled()): ?>
    <?= $this->import('/src/Backend/System/Js/Selection.tpl.php') ?>
    <div>
        <button
            type="button"
            onclick="backend.selection.commit(this, '<?= $this->e(strtolower($resource)) ?>')"
        >
            Select
        </button>
        <button
            type="button"
            onclick="backend.selection.cancel('<?= $this->e(strtolower($resource)) ?>')"
        >
            Cancel
        </button>
    </div>
<?php endif ?>
