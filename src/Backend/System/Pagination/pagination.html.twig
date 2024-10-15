{% if last != 1 %}

    {% set params = {
        'current': page + 1,
        'max': last,
        'route': route,
        'arrows': true,
    } %}

    {% set current = params.current %}
    {% set max = params.max %}
    {% set route = params.route %}
    {% set arrows = params.arrows %}
    {% set prev = current > 1 ? current - 1 : null %}
    {% set next = current < max ? current + 1 : null %}
    {% set items = [1] %}

    {% if max > 1 %}
        {% if current > 6 %}
            {% set items = items|merge(['…']) %}
        {% endif %}

        {% set r = 3 %}
        {% set r1 = current - r %}
        {% set r2 = current + r %}

        {% for i in range(r1 > 2 ? r1 : 2, min(max, r2)) %}
            {% set items = items|merge([i]) %}
        {% endfor %}

        {% if r2 + 1 < max %}
            {% set items = items|merge(['…']) %}
        {% endif %}

        {% if r2 < max %}
            {% set items = items|merge([max]) %}
        {% endif %}
    {% endif %}

    <nav>
        <ul class="pagination flex-wrap mt-3">
            {% if arrows and prev is not null %}
                <li class="page-item">
                    <a href="{{ path(route, app.request.query|merge({'page': prev})) }}" class="page-link">
                        <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    </a>
                </li>
            {% endif %}
            {% for item in items %}
                {% if item == '…' %}
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                {% elseif item == current %}
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ item }}</span>
                    </li>
                {% else %}
                    <li class="page-item">
                        <a href="{{ path(route, app.request.query|merge({'page': item})) }}" class="page-link">
                            {{ item }}
                        </a>
                    </li>
                {% endif %}
            {% endfor %}
            {% if arrows and next is not null %}
                <li class="page-item">
                    <a href="{{ path(route, app.request.query|merge({'page': next})) }}" class="page-link">
                        <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    </a>
                </li>
            {% endif %}
        </ul>
    </nav>

{% endif %}
