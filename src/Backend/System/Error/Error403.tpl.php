<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $modal = $this->service(\App\Backend\System\Modal\Modal::class)->is() ?>

<?php $this->wrap('/src/Backend/System/Layout/Layout.tpl.php', [
    'title' => '403 Forbidden',
    'layout_clean' => true,
    ...get_defined_vars(),
]) ?>

<?php if ($modal): ?>
    <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h4 class="h4 fw-bold text-uppercase mt-1 mb-0 me-3">403 Forbidden</h4>
            <div class="d-flex text-end">
                <button
                    class="btn btn-secondary ms-1"
                    onclick="window.parent.modal.cancel()"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="mt-2 mb-2">
    <div class="alert alert-secondary">
        403 Forbidden
    </div>
</div>

