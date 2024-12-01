<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php
$type = isset($type) ? $type : 'info';
$view = isset($view) ? $view : '/src/Backend/System/Resource/Filter/Type/' . $type . '/' . $type . '.tpl.php';
$sentry = isset($sentry) ? $sentry : new \App\Sentry\Rid\BackendResourceRid(resource: $resource, action: $action, prop: $name);
?>

<?php if ($this->sentry($sentry)): ?>
    <?= $this->render($view, get_defined_vars()) ?>
<?php endif ?>
