{% set type = type is defined ? type : 'info' %}
{% set view = view is defined ? view : '@backend/system/resource/filter/type/' ~ type ~ '/' ~ type ~ '.html.twig' %}
{% set sentry = sentry is defined ? sentry : 'backend.resource.' ~ resource ~ '.action.' ~ action ~ '.prop.' ~ name %}

{% if sentry(sentry) %}
    {% include view %}
{% endif %}