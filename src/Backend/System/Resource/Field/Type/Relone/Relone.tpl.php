<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = $value[$name] ?? '' ?>

<?php if ($mode === 'list'): ?>
    <?php $relone_entity = $value ? $this->service(\App\Backend\System\Resource\Repository\ResourceRepositoryRegistry::class)->__call($reltype)->findById($value) : null ?>
    <?php if ($relone_entity): ?>
        <?php $title = $this->service(\App\Backend\System\Resource\Pilot\ResourcePilot::class)->__call($reltype)->title('detail', $relone_entity) ?>
        <?= $this->e($title) ?>
    <?php endif ?>
<?php if ($mode === 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?php $relone_entity = $value ? $this->service(\App\Backend\System\Resource\Repository\ResourceRepositoryRegistry::class)->__call($reltype)->findById($value) : null ?>
    <?php if ($relone_entity): ?>
        <?php $relone_link = $this->service(\App\Backend\System\Resource\Pilot\ResourcePilot::class)->__call($reltype)->link('detail', $relone_entity) ?>
        <?php $relone_sentry = $this->sentry($relone_link['sentry']) ?>
        <?php if ($relone_sentry): ?>
            <a href="<?= $this->e($relone_link['url']) ?>">
        <?php endif ?>
        <?= $this->e($relone_link['title']) ?>
        <?php if ($relone_sentry): ?>
            </a>
        <?php endif ?>
    <?php elseif ($value): ?>
        <strike><?= $this->e($value) ?></strike>
    <?php endif ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode === 'form'): ?>
    <?= $this->import('/src/Backend/System/Js/Selection.tpl.php') ?>
    <?= $this->import('/src/Backend/System/Js/Formsubmit.tpl.php') ?>
    {% set label = label|default(null)|any ? label : backend().pilot().__call(reltype).title('list') %}
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php') ?>
    <input type="hidden" name="{{ name }}" value="{{ value }}" />
    {% set relone_entity = value|any ? backend().repository().__call(reltype).findById(value) : null %}
    {% if relone_entity|any %}
        {% set relone_link = backend().pilot().__call(reltype).link('detail', relone_entity) %}
        <a
            href="{{ relone_link.url }}"
            target="_blank"
            class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400"
        >
            {{ relone_link.title }}
        </a>
        <button
            type="button"
            onclick='backend.formsubmit.set(this, "{{ name }}", null);'
        >
            X
        </button>
    {% else %}
        <strike>{{ value }}</strike>
    {% endif %}
    <button
        type="button"
        onclick='
            var ctx = this;
            backend.selection.select(
                "{{ reltype }}",
                true,
                function (result) { backend.formsubmit.set(ctx, "{{ name }}", result.data[0].id); }
            );
        '
    >
        ...
    </button>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php') ?>
{% endif %}
