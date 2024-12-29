<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $err = $err ?? $errors->space($name) ?>
<?php if ($err->any()): ?>
    <div
        class="
            flex mt-1
        "
    >
        <?php foreach ($err->toArray() as $e): ?>
            <div
                class="
                    flex mt-1 text-sm leading-none text-red-500
                ">
                <?= $this->e($e->message()) ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>
