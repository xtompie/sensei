<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<div class="p-1">
    <?php foreach ($this->service(\App\Backend\System\Menu\Menu::class)->__invoke() as $item): ?>
        <a
            href="<?= $this->e($item['url']) ?>"
            class="
                flex justify-between items-center rounded-md m-1 px-4 py-2 text-sm text-gray-900
                <?php if ($this->isUriAciive($item['url'])): ?>
                    bg-gray-100
                <?php else: ?>
                    hover:bg-gray-100
                <?php endif ?>
            "
        >
            <div class="flex items-center gap-x-2">
                <?php if (\array_key_exists('icon', $item)): ?>
                    <span class="size-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="<?= $this->e($item['icon']) ?>" />
                        </svg>
                    </span>
                <?php endif ?>
                <span><?= $this->e($item['label']) ?></span>
            </div>
            <?php if (\array_key_exists('badge', $item)): ?>
                <span class="text-gray-500">
                    <?= $this->e($item['badge']) ?>
                </span>
            <?php endif ?>
        </a>
    <?php endforeach ?>
</div>
