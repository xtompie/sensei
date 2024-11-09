{# {% if backend().modal()|none %}
    {% if breadcrumb is defined %}
        <ul>
            <li>
                <a href="/backend">{{ 'Backend'|t }}</a>
            </li>
            {% for item in breadcrumb %}
                {% if sentry(item.sentry) %}
                    <li>
                        <a href="{{ item.url }}">{{ item.title }}</a>
                    </li>
                {% endif %}
            {% endfor %}
        </ul>
    {% endif %}
{% endif %} #}

{% if backend().modal()|none and breadcrumb is defined %}
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-4">
            <li>
                <div>
                    <a href="/backend" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">{{ 'Backend'|t }}</span>
                    </a>
                </div>
            </li>
            {% for item in breadcrumb %}
                {% if sentry(item.sentry) %}
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <a href="{{ item.url }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ item.title }}</a>
                        </div>
                    </li>
                {% endif %}
            {% endfor %}
        </ol>
    </nav>
{% endif %}
