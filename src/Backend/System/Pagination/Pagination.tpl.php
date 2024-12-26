<?php

/** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php
$current = floor($offset / $limit) + 1;
$max = ceil($all / $limit);
$prev = $current > 1 ? $current - 1 : null;
$next = $current < $max ? $current + 1 : null;
$items = [];

if ($all > $limit) {
    $items[] = 1;

    if ($max > 1) {
        if ($current > 6) {
            $items[] = 'gap';
        }

        $r = 3;
        $r1 = max(2, $current - $r);
        $r2 = min($max, $current + $r);

        for ($i = $r1; $i <= $r2; $i++) {
            $items[] = $i;
        }

        if ($r2 + 1 < $max) {
            $items[] = 'gap';
        }

        if ($r2 < $max) {
            $items[] = $max;
        }
    }
}
?>

<?php if ($items): ?>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <?php if ($prev !== null): ?>
                    <a href="<?= $this->alterUri(['page' => $prev]) ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif ?>

                <?php foreach ($items as $item): ?>
                    <?php if ($item === 'gap'): ?>
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                    <?php elseif ($item === $current): ?>
                        <a href="#" aria-current="page" lass="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"><?= $this->e($item) ?></a>
                    <?php else: ?>
                        <a href="<?= $this->alterUri(['page' => $item]) ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><?= $this->e($item) ?></a>
                    <?php endif ?>
                <?php endforeach ?>

                <?php if ($next !== null): ?>
                    <a href="<?= $this->alterUri(['page' => $next]) ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif ?>
            </nav>
        </div>
    </div>
<?php endif ?>