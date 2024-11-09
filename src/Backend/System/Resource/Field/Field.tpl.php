<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $type = $type ?? 'Info' ?>
<?php $view = $view ?? "src/Backend/System/Resource/Field/Type/{$type}/{$type}.tpl.php" ?>
<?php $entity = $entity ?? null ?>
<?php $sentry = $sentry ?? "backend.resource.$resource.action. $action" . ($entity ? ".id.{$entity['id']}" : '') . ".prop.$name" ?>

<?php if ($this->sentry($sentry)) : ?>
    <?php if (isset($list_header) && $list_header) : ?>
        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0">
            <?php
                if ($list_sort && $sort) {
                    $list_sort_link = null;
                    if ($order == $name . ':asc') {
                        $list_sort_link = $this->alterUri(['page' => '0', 'order' => $name . ':desc']);
                    } elseif ($order == $name . ':desc') {
                        $list_sort_link = $this->alterUri(['page' => '0', 'order' => $name . ':asc']);
                    } else {
                        $list_sort_link = $this->alterUri(['page' => '0', 'order' => $name . ':' . ($sort_dir ?? 'asc')]);
                    }
                    if ($order == $name . ':asc') {
                        $list_sort_dir = 'asc';
                    } elseif ($order == $name . ':desc') {
                        $list_sort_dir = 'desc';
                    } else {
                        $list_sort_dir = null;
                    }
                }
            ?>

            <?php if ($list_sort_link && $list_sort_link) : ?>
                <a href="<?= $list_sort_link ?>">
            <?php endif ?>

            <?php if (isset($label)) : ?>
                <?= $label ?>
            <?php else: ?>
                <?= $this->t('backend', $name) ?>
            <?php endif ?>

            <?php if ($list_sort_dir) : ?>
                <?php if ($list_sort_dir == 'asc') : ?>
                    &uarr;
                <?php else: ?>
                    &darr;
                <?php endif ?>
            <?php endif ?>

            <?php if ($list_sort_link) : ?>
                </a>
            <?php endif ?>
        </th>
    <?php elseif ($action == 'list') : ?>
        <td class="whitespace-nowrap px-3 py-3.5 text-sm text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0">
            <?php $more_url = null ?>
            <?php if ($more && $list_link) : ?>
                <?php $more = $more === true ? 'detail' : $more ?>
                <?php foreach ($this->service(\App\Backend\System\Resource\Pilot\ResourcePilotRegistry::class)->__call($resource)->more($action, $value) as $item) : ?>
                    <?php if ($item['action'] == $more && $this->sentry($item['sentry'])) : ?>
                        <?php $more_url = $item['url'] ?>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>

            <?php if ($more_url) : ?>
                <a href="<?= $this->e($more_url) ?>">
            <?php endif ?>

            <?= $this->render($view) ?>

            <?php if ($more_url) : ?>
                </a>
            <?php endif ?>
        </td>
    <?php else : ?>
        <?= $this->render($view) ?>
    <?php endif ?>
<?php endif ?>
