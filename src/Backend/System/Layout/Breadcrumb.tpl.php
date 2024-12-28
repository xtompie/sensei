<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php if ($breadcrumb): ?>
    <div class="inline-flex pl-4 py-1">
        <a
            class="w-auto flex justify-center h-10 px-3 py-0 items-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700"
            href="/backend"
        >
            <svg class="h- w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
            </svg>
        </a>
        <?php foreach ($breadcrumb as $item): ?>
            <?php if ($this->sentry($item['sentry'])): ?>
                    <span class="flex items-center w-auto">
                        <svg class="flex h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                    </span>
                    <a
                        class="w-auto flex justify-center items-center px-3 rounded-md text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                        href="<?= $this->e($item['url']) ?>"
                    >
                        <?= $this->e($item['title']) ?>
                    </a>
            <?php endif ?>
        <?php endforeach ?>
    </div>
<?php endif ?>
