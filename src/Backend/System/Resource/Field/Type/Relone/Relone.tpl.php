<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

{% set value = value[name] ?? null %}

{% if action == 'list' %}
    {% set relone_entity = value|default(null)|any ? backend().repository().__call(reltype).findById(value) : null %}
    {% if relone_entity|any %}
        {{ backend().pilot().__call(reltype).title('detail', relone_entity) }}
    {% endif %}
{% elseif action == 'detail' %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/begin.tpl.php') ?>
    {% set relone_entity = value is defined ? backend().repository().__call(reltype).findById(value) : null %}
    {% if relone_entity|any %}
        {% set relone_link = backend().pilot().__call(reltype).link('detail', relone_entity) %}
        {% set relone_sentry = sentry(relone_link.sentry) %}
        {% if relone_sentry %}
            <a href="{{ relone_link.url }}">
        {% endif %}
        {{ relone_link.title }}
        {% if relone_sentry %}
            </a>
        {% endif %}
    {% elseif value is defined and value is not null %}
        <strike>{{ value }}</strike>
    {% endif %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/end.tpl.php') ?>
{% else %}
    {{ include_once('@backend/system/js/selection.tpl.php') }}
    {{ include_once('@backend/system/js/formsubmit.tpl.php') }}
    {% set label = label|default(null)|any ? label : backend().pilot().__call(reltype).title('list') %}
    <?= $this->render('/src/Backend/System/Resource/Field/Form/begin.tpl.php') ?>
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
    <?= $this->render('/src/Backend/System/Resource/Field/Form/end.tpl.php') ?>
{% endif %}
