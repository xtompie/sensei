<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

{% set value = value[name] ?? null %}

{% if action == 'list' %}
    {% if value is iterable %}
        {{ value|length }}
    {% endif %}
{% elseif action == 'detail' %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php') ?>
    {% set value = value|default({})%}
    {% for value in value %}
        {% set relone_entity = value[id] is defined ? backend().repository().__call(reltype).findById(value[id]) : null %}
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
    {% endfor %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php') ?>
{% else %}
    {{ include_once('@backend/system/js/selection.tpl.php') }}
    {{ include_once('@backend/system/js/formsubmit.tpl.php') }}
    {{ include_once('@backend/system/js/removeitem.tpl.php') }}
    {{ include_once('@backend/system/js/sortable.tpl.php') }}
    {% set label = label|default(null)|any ? label : backend().pilot().__call(reltype).title('index') %}
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php') ?>
    <div backend-sortable-space>
        <div backend-sortable-sortable>
            {% for value in value %}
                {% if value['id'] is defined %}
                    <div backend-removeitem>
                        <span class="sortable-handle">=</span>
                        <input type="hidden" name="{{ name }}[{{ loop.index0 }}][id]" value="{{ value['id'] }}" />
                        {% set relone_entity = backend().repository().__call(reltype).findById(value['id']) %}
                        {% if relone_entity|any %}
                            {% set relone_link = backend().pilot().__call(reltype).link('detail', relone_entity) %}
                            <a href="{{ relone_link.url }}" target="_blank">{{ relone_link.title }}</a>
                        {% else %}
                            <strike>{{ value['id'] }}</strike>
                        {% endif %}
                        <button type="button" onclick='backend.removeitem.form(this);'>X</button>
                    </div>
                {% endif %}
            {% endfor %}
        </div>
        <script>
            backend.sortable(document.currentScript);
        </script
    </div>
    <button
        type="button"
        onclick='
            var ctx = this;
            backend.selection.select(
                "{{ reltype }}",
                false,
                function (result) {
                    backend.formsubmit.add(ctx, "{{ name }}", result.data);
                }
            );
        '
    >
        ...
    </button>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php') ?>
{% endif %}
