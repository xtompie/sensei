<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $pilot = $this->service(\App\Backend\System\Resource\PilotRegistry::class)->__call($resource) ?>

<?php if ($list_selection) : ?>
    <?= $this->import('src/Backend/System/Js/checkone.tpl.php') ?>
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
                    <?php if ($list_selection) : ?>
                        <th class="[&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    <?php endif ?>
                    <?= $this->render($fields, [
                        'action' => 'index',
                        'list_header' => true,
                    ]) ?>
                    <?php if ($list_more) : ?>
                        <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    <?php endif ?>
                    <?php if ($list_removeitem) : ?>
                        <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

                {% for value in values %}
                <?php foreach ($values as $value) : ?>
                    <?php $entity = $value ?>
                    <tr
                        <?php if ($list_removeitem) : ?>
                            backend-removeitem
                        <?php endif ?>
                        <?php if ($list_selection) : ?>
                            onclick="this.querySelector('[backend-clickdelegate]').click();"
                        <?php endif ?>
                    >
                        <?php if ($list_selection) : ?>
                            <td
                                style="width: 40px;"
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900  [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                <input
                                    backend-selection-id
                                    <?php if ($list_selection_single) : ?>
                                        backend-checkone-item
                                    <?php endif ?>
                                    backend-clickdelegate
                                    type="checkbox"
                                    value="<?= $this->e($value['id']) ?>"
                                    class="form-check-input"
                                    <?php if ($list_selection_single) : ?>
                                        onclick="backend.checkone(this); event.stopPropagation();"
                                    <?php else : ?>
                                        onclick="event.stopPropagation();"
                                    <?php endif ?>
                                 />
                            </td>
                        <?php endif ?>
                        <?= $this->render($fields, [
                            'action' => 'list',
                            'value' => $value,
                            'entity' => $entity,
                        ]) ?>
                        <?php if ($list_more) : ?>
                            <td
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 text-right [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                <?= $this->render('/src/Backend/System/Resource/More/more.tpl.php', [
                                    'more' => $pilot->more('list', $entity),
                                ]) ?>
                            </td>
                        <?php endif ?>
                        <?php if ($list_removeitem) : ?>
                            <td
                                class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0"
                            >
                                <button onclick="this.closest('[backend-removeitem]').remove()">X</button>
                            </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
