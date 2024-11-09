<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

{% set value = value[name] ?? null %}

{% if action == 'list' %}
    {% if options[value] is defined %}
        {{ options[value] }}
    {% else %}
        {{ value }}
    {% endif %}
{% elseif action == 'detail' %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/begin.tpl.php') ?>
        {% if options[value] is defined %}
            {{ options[value] }}
        {% else %}
            {{ value }}
        {% endif %}
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/end.tpl.php') ?>
{% else %}
    <?= $this->render('/src/Backend/System/Resource/Field/Form/begin.tpl.php') ?>
        <select
            name="{{ name }}"
        >
            {% for option_value, option_title in options %}
                <option
                    value="{{ option_value }}"
                    {% if value == option_value %}
                        selected="selected"
                    {% endif %}
                >
                    {{ option_title }}
                </option>
            {% endfor %}
        </select>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/end.tpl.php') ?>
{% endif %}
