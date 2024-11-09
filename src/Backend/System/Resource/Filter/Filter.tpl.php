<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

{% set type = type is defined ? type : 'info' %}
{% set view = view is defined ? view : '@backend/system/resource/filter/type/' ~ type ~ '/' ~ type ~ '.tpl.php' %}
{% set sentry = sentry is defined ? sentry : 'backend.resource.' ~ resource ~ '.action.' ~ action ~ '.prop.' ~ name %}

{% if sentry(sentry) %}
    {% include view %}
{% endif %}