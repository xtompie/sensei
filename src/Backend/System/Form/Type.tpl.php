<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php
$type = $type ?? 'Text';
$view = $view ?? "/src/Backend/System/Form/Type/{$type}.tpl.php";
?>

<?= $this->render($view, get_defined_vars()) ?>