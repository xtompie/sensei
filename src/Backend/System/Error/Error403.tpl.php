{% extends "@backend/system/layout/default.tpl.php" %}
{% set title = '403 Forbidden' %}

{% block content %}

    {% if backend_selection().enabled() %}
        {{ include_once("@backend/system/selection/selection.tpl.php") }}
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                <h4 class="h4 fw-bold text-uppercase mt-1 mb-0 me-3">{{ title }}</h4>
                <div class="d-flex text-end">
                    <button
                        class="btn btn-secondary ms-1"
                        onclick="window.parent.modal.cancel()"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    {% endif %}
    <div class="mt-2 mb-2">
        <div class="alert alert-secondary">{{ title }}</div>
    </div>

{% endblock %}
