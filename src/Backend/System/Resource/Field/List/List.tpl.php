<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php
$selection = $this->service(\App\Backend\System\Resource\Selection\Selection::class);
$fields = $fields ?? "/src/Backend/Resource/$resource/Fields.tpl.php";
$mode = isset($mode) && in_array($mode, ['index', 'card', 'rel']) ? $mode : 'index';
$list_selection = $mode === 'index' && $selection->enabled();
$list_link = $list_selection ? false : true;
$list_more = $list_selection || $mode == 'rel' ? false : true;
$list_link_blank = $list_selection || $mode == 'rel' ? true : false;
$list_selection_single = $selection->enableSingle();
$list_sort = $mode == 'index';
$list_removeitem = $mode == 'rel';
?>
<?php if ($entities): ?>
    <?php if ($list_selection): ?>
        <?= $this->import('/src/Backend/System/Js/Checkone.tpl.php') ?>
    <?php endif ?>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">

            <table
                class="min-w-full divide-y divide-gray-300"
                <?php if ($list_selection): ?>
                    backend-checkone-space
                    backend-selection-space
                <?php endif ?>
            >
                <thead>
                    <tr>
                        <?php if ($list_selection): ?>
                            <th class="[&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                        <?php endif ?>
                        <?= $this->render($fields, [
                            'action' => 'list',
                            'list_header' => true,
                            'list_sort' => $list_sort,
                            'mode' => 'index',
                            'order' => $order,
                            'resource' => $resource,
                        ]) ?>
                        <?php if ($list_more): ?>
                            <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                        <?php endif ?>
                        <?php if ($list_removeitem): ?>
                            <th class="px-3 py-3.5 text-left font-medium uppercase tracking-wide text-gray-500 [&:first-child]:pl-0 [&:last-child]:pr-0"></th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    <?php foreach ($entities as $entity): ?>
                        <?php $value = $entity ?>
                        <tr
                            <?php if ($list_removeitem): ?>
                                backend-removeitem
                            <?php endif ?>
                            <?php if ($list_selection): ?>
                                onclick="this.querySelector('[backend-clickdelegate]').click();"
                            <?php endif ?>
                        >
                            <?php if ($list_selection): ?>
                                <td
                                    style="width: 40px;"
                                    class="whitespace-nowrap px-3 py-2 text-sm text-gray-900  [&:first-child]:pl-0 [&:last-child]:pr-0"
                                >
                                    <input
                                        backend-selection-id
                                        <?php if ($list_selection_single): ?>
                                            backend-checkone-item
                                        <?php endif ?>
                                        backend-clickdelegate
                                        type="checkbox"
                                        value="<?= $this->e($entity['id']) ?>"
                                        class="form-check-input"
                                        <?php if ($list_selection_single): ?>
                                            onclick="backend.checkone(this); event.stopPropagation();"
                                        <?php else : ?>
                                            onclick="event.stopPropagation();"
                                        <?php endif ?>
                                    />
                                </td>
                            <?php endif ?>
                            <?= $this->render($fields, [
                                ...get_defined_vars(),
                                'action' => 'list',
                                'list_header' => false,
                                'mode' => 'list',
                            ]) ?>
                            <?php if ($list_more): ?>
                                <td
                                    class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 text-right [&:first-child]:pl-0 [&:last-child]:pr-0"
                                >
                                    <?= $this->render('/src/Backend/System/Resource/More/More.tpl.php', [
                                        ...get_defined_vars(),
                                        'action' => 'list',
                                        'list_header' => false,
                                        'mode' => 'list',
                                        'more' => $this->service(App\Backend\System\Resource\Pilot\Pilots::class)->get($resource)->more(action: 'list', entity: $entity),
                                    ]) ?>
                                </td>
                            <?php endif ?>
                            <?php if ($list_removeitem): ?>
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

    <?= $this->render('/src/Backend/System/Resource/Selection/Submits.tpl.php', get_defined_vars()) ?>
<?php endif ?>