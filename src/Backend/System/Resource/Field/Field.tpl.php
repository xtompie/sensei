<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php
$type = $type ?? 'Info';
$view = $view ?? "/src/Backend/System/Resource/Field/Type/{$type}/{$type}.tpl.php";
$entity = $entity ?? null;
$sentry = $sentry ?? new \App\Sentry\Rid\BackendResourceRid(resource: $resource, action: $action, id: $entity ? $entity['id'] : null, prop: $name);
$list_sort = $list_sort ?? false;
$list_link = $list_link ?? false;
$sort = $sort ?? false;
$more = $more ?? false;
?>
<?php if ($this->sentry($sentry)): ?>
    <?php if (isset($list_header) && $list_header): ?>
        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0">
            <?php
                $list_sort_link = null;
        $list_sort_dir = null;
        if ($list_sort && $sort) {
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
            }
        }
$label = $label ?? ucfirst($name);
?>

            <?php if ($list_sort_link): ?>
                <a href="<?= $this->e($list_sort_link) ?>">
            <?php endif ?>

            <?= $this->e($label) ?>

            <?php if ($list_sort_dir): ?>
                <?php if ($list_sort_dir == 'asc'): ?>
                    &uarr;
                <?php else: ?>
                    &darr;
                <?php endif ?>
            <?php endif ?>

            <?php if ($list_sort_link): ?>
                </a>
            <?php endif ?>
        </th>
    <?php elseif ($mode == 'list'): ?>
        <td class="whitespace-nowrap px-3 py-3.5 text-sm text-gray-900 [&:first-child]:pl-0 [&:last-child]:pr-0">
            <?php $more_url = null ?>
            <?php if ($more) : ?>
                <?php $more = $more === true ? 'detail' : $more ?>
                <?php foreach ($this->service(\App\Backend\System\Resource\Pilot\Pilots::class)->get($resource)->more(action: $action, entity: $value) as $item): ?>
                    <?php if ($item['action'] == $more && $this->sentry($item['sentry'])): ?>
                        <?php $more_url = $item['url'] ?>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>

            <?php if ($more_url): ?>
                <a href="<?= $this->e($more_url) ?>">
            <?php endif ?>

            <?= $this->render($view, get_defined_vars()) ?>

            <?php if ($more_url): ?>
                </a>
            <?php endif ?>
        </td>
    <?php else : ?>
        <?= $this->render($view, get_defined_vars()) ?>
    <?php endif ?>
<?php endif ?>
