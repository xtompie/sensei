<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $flashes = $this->service(\App\Backend\System\Flash\Flash::class)->pull(); ?>

<?php if (!empty($flashes)): ?>
    <?php foreach ($flashes as $flash): ?>
        <?php $type = $flash['type'] ?>
        <div
            backend-flash
            class="
                px-4 py-3 rounded-lg flex items-center justify-between
                <?php if ($type == 'error'): ?>
                    bg-red-100 text-red-800
                <?php elseif ($type == 'success'): ?>
                    bg-green-100 text-green-800
                <?php elseif ($type == 'warning'): ?>
                    bg-yellow-100 text-yellow-800
                <?php else: ?>
                    bg-red-100 text-red-800
                <?php endif; ?>
            "
            role="alert"
        >
            <span class="font-medium">
                <?= $this->e($this->t($flash['msg'])) ?>
            </span>
            <button type="button" class="text-gray-500 hover:text-gray-800 focus:outline-none" data-dismiss="alert" aria-label="Close"
                onclick="this.up('[backend-flash]').remove();"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    <?php endforeach ?>
<?php endif ?>
