<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?php $moveto = $this->service(\App\Backend\System\Moveto\Moveto::class) ?>

<?php if ($moveto->__invoke()): ?>
    <script> window.scrollTo({behavior: "instant", top: <?= $this->raw((string) $moveto->__invoke()) ?>}) </script>
<?php endif ?>
