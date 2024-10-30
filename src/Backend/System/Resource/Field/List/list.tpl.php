<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php if ($list_selection) : ?>
    <?= $this->render('src/Backend/System/Js/checkone.tpl.php') ?>
<?php endif ?>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">

        <table
            class="min-w-full divide-y divide-gray-300"
            <?php if ($list_selection) : ?>
                backend-checkone-space
                backend-selection-space
            <?php endif ?>
        >
            <thead>

                <tr>
                    {% if list_selection|any %}
                        <th class="[&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    {% endif %}
                    {% include fields with {
                        'action': 'list',
                        'list_header': true,
                    } %}
                    {% if list_more|any %}
                        <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    {% endif %}
                    {% if list_removeitem|default(null)|any %}
                        <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    {% endif %}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

                {% set action = 'list' %}
                {% for value in values %}
                    {% set entity = value %}
                    <tr
                        {% if list_removeitem|default(null)|any %}
                            backend-removeitem
                        {% endif %}
                        {% if list_selection|any %}
                            onclick="this.querySelector('[backend-clickdelegate]').click();"
                        {% endif %}
                    >
                        {% if list_selection|any %}
                            <td
                                style="width: 40px;"
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900  [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                <input
                                    backend-selection-id
                                    {% if list_selection_single|any %}
                                        backend-checkone-item
                                    {% endif %}
                                    backend-clickdelegate
                                    type="checkbox"
                                    value="{{ entity.id }}"
                                    class="form-check-input"
                                    {% if list_selection_single|any %}
                                        onclick="backend.checkone(this); event.stopPropagation();"
                                    {% else %}
                                        onclick="event.stopPropagation();"
                                    {% endif %}
                                />
                            </td>
                        {% endif %}
                        {% include fields %}
                        {% if list_more is defined and list_more %}
                            <td
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 text-right [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                {% include "@backend/system/resource/more/more.html.twig" with {
                                    'more':  backend().pilot().__call(resource).more(action, entity),
                                } %}
                            </td>
                        {% endif %}
                        {% if list_removeitem is defined and list_removeitem %}
                            <td
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                <button onclick="this.closest('[backend-removeitem]').remove()">X</button>
                            </td>
                        {% endif %}
                    </tr>
                {% endfor %}
          </tbody>
        </table>

      </div>
    </div>
  </div>
