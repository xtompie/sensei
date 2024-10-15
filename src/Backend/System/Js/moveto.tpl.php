<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $backend = $this->service(\App\Backend\System\Backend\Backend::class) ?>

<?php if ($backend->moveto()): ?>
    <script> window.scrollTo({behavior: "instant", top: <?= $this->raw((string) $backend->moveto()) ?>}) </script>
<?php endif ?>
