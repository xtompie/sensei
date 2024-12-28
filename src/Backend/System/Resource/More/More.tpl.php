<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php

$list = $action == 'list';
$more_expose = $more_expose ?? ($list ? 0 : 1);
$more = array_filter($more ?? [], fn (array $i) => $this->sentry($i['sentry']));
$show = array_slice($more, 0, $more_expose);
$hide = array_slice($more, $more_expose);
?>

<?php if ($more): ?>
    <?= $this->import('/src/Backend/System/Js/Dropdown.tpl.php') ?>
    <div
        backend-dropdown-space
        class="
            flex
            <?php if (!$list): ?>
                space-x-2
            <?php endif ?>
        "
    >
        <?php foreach ($show as $index => $item): ?>
            <a
                href="<?= $this->e($item['url']) ?>"
                <?php if ($list): ?>
                    class="
                        inline-flex -my-2 px-2 py-2 items-center rounded-md
                        text-indigo-600
                        hover:bg-gray-100
                    "
                <?php elseif ($index === 0): ?>
                    class="
                        inline-flex px-4 py-2 items-center rounded-md
                        text-white bg-gray-900 text-sm
                        hover:bg-gray-800
                    "
                <?php else : ?>
                    class="
                        inline-flex px-4 py-2 items-center rounded-md
                        text-white bg-gray-500 text-sm
                        hover:bg-gray-400
                    "
                <?php endif ?>
            >
                <?= $this->e($item['title']) ?>
            </a>
        <?php endforeach ?>

        <?php if (count($hide) > 0): ?>
            <div class="relative inline-block">
                <button
                    href="#"
                    <?php if ($list): ?>
                        class="
                            inline-flex -my-2 px-3 py-2 items-center rounded-md
                            text-indigo-600
                            hover:bg-gray-100
                        "
                    <?php else : ?>
                        class="
                            inline-flex px-4 py-2 items-center rounded-md
                            text-black bg-gray-200 text-sm
                            hover:bg-gray-100
                        "
                    <?php endif ?>
                    onclick="backend.dropdown.toggle(this, event)"
                >
                    ⋮
                </button>

                <div
                    backend-dropdown-panel
                    style="display: none;"
                    class="opacity-100 absolute right-0 z-10 mt-0.5 min-w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 text-left ring-gray-900/5 focus:outline-none"
                >
                    <?php foreach ($hide as $index => $item): ?>
                        <a
                            href="<?= $this->e($item['url']) ?>"
                            class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-100"
                        >
                            <?= $this->e($item['title']) ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

    </div>
<?php endif ?>
