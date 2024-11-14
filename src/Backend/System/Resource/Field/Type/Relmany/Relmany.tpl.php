<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value ?: $value[$name] ?? [] ?>

<?php if ($mode === 'list'): ?>
    <?php if (is_iterable($value)): ?>
        <?= $this->e(count($value)) ?>
    <?php endif ?>
<?php elseif ($mode === 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?php $entities = $this->service(\App\Backend\System\Resource\Repository\UberRepository::class)->findAllKeyed(resource: $reltype, where: ['id' => array_column($value, 'id')]) ?>
    <?php foreach ($value as $value_item): ?>
        <?php
            if (!isset($value_item['id']) || !is_string($value_item['id'])) {
                continue;
            }
        $value_item_id = $value_item['id'];
        $relmany_entity = $entities[$value_item_id] ?? null;
        ?>
        <?php if ($relmany_entity): ?>
            <?php $relmany_link = $this->service(\App\Backend\System\Resource\Pilot\UberPilot::class)->link(resource: $reltype, action: 'detail', entity: $relmany_entity) ?>
            <a href="<?= $this->e($relmany_link['url']) ?>">
                <?= $this->e($relmany_link['title']) ?>
            </a>
        <?php else: ?>
            <?= $this->e($value_item_id) ?>
        <?php endif ?>
    <?php endforeach ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php else: ?>
    <?= $this->import('/src/Backend/System/Js/Selection.tpl.php') ?>
    <?= $this->import('/src/Backend/System/Js/Formsubmit.tpl.php') ?>
    <?= $this->import('/src/Backend/System/Js/RemoveItem.tpl.php') ?>
    <?= $this->import('/src/Backend/System/Js/Sortable.tpl.php') ?>
    <?php $label = $label ?? $this->service(\App\Backend\System\Resource\Pilot\UberPilot::class)->title(resource: $reltype, action: 'index') ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php') ?>
    <div backend-sortable-space>
        <div backend-sortable-sortable>
            {% for value in value %}
            <?php foreach ($value as $value_index => $value_item): ?>
                <?php
                    if (!isset($value_item['id']) || !is_string($value_item['id'])) {
                        continue;
                    }
                $value_item_id = $value_item['id'];
                ?>
                    <div backend-removeitem>
                        <span class="sortable-handle">=</span>
                        <input type="hidden" name="<?= $this->e($name) ?>[<?= $this->e($value_index) ?>][id]" value="<?= $this->e($value_item_id) ?>" />
                        <?php $relmany_entity = $this->service(\App\Backend\System\Resource\Repository\UberRepository::class)->findById(resource: $reltype, id: $value_item_id) ?>
                        <?php if ($relmany_entity): ?>
                            <?php $relmany_link = $this->service(\App\Backend\System\Resource\Pilot\UberPilot::class)->link(resource: $reltype, action: 'detail', entity: $relmany_entity) ?>
                            <a href="<?= $this->e($relmany_link['url']) ?>" target="_blank"><?= $this->e($relmany_link['title']) ?></a>
                        <?php else: ?>
                            <span class="line-through"><?= $this->e($value_item_id) ?></span>
                        <?php endif ?>
                        <button type="button" onclick='backend.removeitem.form(this);'>X</button>
                    </div>
            <?php endforeach ?>
        </div>
        <script>
            backend.sortable(document.currentScript);
        </script>
    </div>
    <button
        type="button"
        onclick='
            var ctx = this;
            backend.selection.select(
                "<?= $this->e($reltype) ?>",
                false,
                function (result) {
                    backend.formsubmit.add(ctx, "<?= $this->e($name) ?>", result.data);
                }
            );
        '
    >
        ...
    </button>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php') ?>
<?php endif ?>
