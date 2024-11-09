{% extends "@backend/system/layout/layout.tpl.php" %}

{% block content %}

    {% include "@backend/system/resource/title/title.tpl.php" %}

    {% include "@backend/system/resource/form/begin.tpl.php" %}

    {% include fields %}

    {% include "@backend/system/resource/form/end.tpl.php" %}

{% endblock %}
