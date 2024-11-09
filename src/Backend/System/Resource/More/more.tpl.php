<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $btn = $action != 'list' ?>
<?php $more = array_filter($more ?? [], fn (array $i) => $this->sentry($i['sentry'])) ?>

<?php if ($more) : ?>
    <?= $this->import('/src/Backend/System/Js/dropdown.tpl.php') ?>
    <div backend-dropdown-space>
        <?php $item = $more[0] ?>
        <a
            href="<?= $this->e($item['url']) ?>"
            class="
                text-indigo-600
                <?php if ($btn): ?>
                    inline-flex items-center rounded-md shadow-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 px-3 py-2 text-sm
                <?php else : ?>
                    inline-flex px-1 text-indigo-600
                <?php endif ?>
            "
        >
            <?= $this->e($item['title']) ?>
        </a>

        <?php if (count($more) > 1): ?>
            <div class="relative inline-block">
                <button
                    href="#"
                    class="
                        <?php if ($btn): ?>
                            inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-200
                        <?php else : ?>
                            inline-flex px-1 text-indigo-600
                        <?php endif ?>
                    "
                    onclick="backend.dropdown.toggle(this, event)"
                >
                    ⋮
                </button>

                <div
                    backend-dropdown-panel
                    style="display: none;"
                    class="opacity-100 absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 text-left ring-gray-900/5 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                >
                    <?php foreach (array_slice($more, 1) as $index => $item): ?>
                        <a
                            href="<?= $this->e($item['url']) ?>"
                            class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-200"
                            role="menuitem"
                            tabindex="-1"
                        >
                            <?= $this->e($item['title']) ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

    </div>
<?php endif ?>
