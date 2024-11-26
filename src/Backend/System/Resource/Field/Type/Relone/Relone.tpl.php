<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value[$name] ?? '' ?>

<?php if ($mode === 'list'): ?>
    <?php $relone_entity = $value ? $this->service(\App\Backend\System\Resource\Repository\Repositories::class)->get($reltype)->findById($value) : null ?>
    <?php if ($relone_entity): ?>
        <?php $title = $this->service(\App\Backend\System\Resource\Pilot\Pilots::class)->get($reltype)->title(action: 'detail', entity: $relone_entity) ?>
        <?= $this->e($title) ?>
    <?php endif ?>
<?php elseif ($mode === 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?php $relone_entity = $value ? $this->service(\App\Backend\System\Resource\Repository\Repositories::class)->get($reltype)->findById($value) : null ?>
    <?php if ($relone_entity): ?>
        <?php $relone_link = $this->service(\App\Backend\System\Resource\Pilot\Pilots::class)->get($reltype)->link('detail', $relone_entity) ?>
        <?php $relone_sentry = $this->sentry($relone_link['sentry']) ?>
        <?php if ($relone_sentry): ?>
            <a href="<?= $this->e($relone_link['url']) ?>">
        <?php endif ?>
        <?= $this->e($relone_link['title']) ?>
        <?php if ($relone_sentry): ?>
            </a>
        <?php endif ?>
    <?php elseif ($value): ?>
        <span class="line-through"><?= $this->e($value) ?></span>
    <?php endif ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode === 'form'): ?>
    <?= $this->import('/src/Backend/System/Js/Selection.tpl.php') ?>
    <?= $this->import('/src/Backend/System/Js/Formsubmit.tpl.php') ?>
    <?php $label = $label ?? $this->service(\App\Backend\System\Resource\Pilot\Pilots::class)->get($reltype)->title('list') ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
    <input type="hidden" name="<?= $this->e($name) ?>" value="<?= $this->e($value) ?>" />
    <?php $relone_entity = $value ? $this->service(\App\Backend\System\Resource\Repository\Repositories::class)->get($reltype)->findById($value) : null ?>
    <?php if ($relone_entity): ?>
        <?php $relone_link = $this->service(\App\Backend\System\Resource\Pilot\Pilots::class)->get($reltype)->link(action: 'detail', entity: $relone_entity) ?>
        <a
            href="<?= $this->e($relone_link['url']) ?>"
            target="_blank"
            class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400"
        >
            <?= $this->e($relone_link['title']) ?>
        </a>
        <button
            type="button"
            onclick='backend.formsubmit.set(this, "<?= $this->e($name) ?>", null);'
        >
            X
        </button>
    <?php else: ?>
        <span class="line-through"><?= $this->e($value) ?></span>
    <?php endif ?>
    <button
        type="button"
        onclick='
            var ctx = this;
            backend.selection.select(
                "<?= $this->e(strtolower($reltype)) ?>",
                true,
                function (result) { backend.formsubmit.set(ctx, "<?= $this->e($name) ?>", result.data[0].id); }
            );
        '
    >
        ...
    </button>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>
<?php endif ?>
