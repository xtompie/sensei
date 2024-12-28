<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $err = $err ?? $errors->space($name) ?>
<?php if ($err->any()): ?>
    <div class="mt-6">
        <?php foreach ($err->toArray() as $e): ?>
            <div
                class="
                    flex mt-2 px-4 py-2 rounded-lg items-center justify-between text-sm
                    bg-red-100 text-red-800
                "
            >
                <?= $this->e($e->message()) ?>
        </div>
        <?php endforeach ?>
    </div>
<?php endif ?>
