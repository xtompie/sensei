{% set from = last is defined and page > last ? 1 : limit * page + 1 %}
{% set to = limit * (page + 1) %}
{% set to = to > all ? all : to %}
{% if all > 0 and from <= all and from > 0 %}
    {{ from }} - {{ to }} / {{ all }}
{% endif %}
