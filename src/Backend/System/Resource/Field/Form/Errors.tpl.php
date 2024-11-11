<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

<?php $err = $err ?? $errors->space($name) ?>
<div>
    <?php if ($err->any()): ?>
        <?php foreach ($err->toArray() as $e): ?>
            <div class="invalid-feedback"><?= $this->e($e->message()) ?></div>
        <?php endforeach ?>
    <?php endif ?>
</div>
