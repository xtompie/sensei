<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php
$from = $offset + 1;
$to = $offset + $limit;
$to = $to > $all ? $all : $to;
?>

<?php if ($all > 0 && $from <= $all && $from > 0): ?>
    <?= $from ?> - <?= $to ?> / <?= $all ?>
<?php endif ?>
