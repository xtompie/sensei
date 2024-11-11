<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $flashes = $this->service(\App\Backend\System\Flash\Flash::class)->pull(); ?>

<?php if (!empty($flashes)): ?>
    <?php foreach ($flashes as $flash): ?>
        <?php $type = $flash['type'] ?>
        <div
            class="
                alert alert-dismissible fade show
                <?php if ($type == 'error'): ?>
                    alert-danger
                <?php elseif ($type == 'ok'): ?>
                    alert-success
                <?php elseif ($type == 'warning'): ?>
                    alert-warning
                <?php else: ?>
                    alert-danger
                <?php endif; ?>
            "
            role="alert"
        >
            <?= $this->e($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach ?>
<?php endif ?>
