<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<html>
    <head>
        <title><?= $this->e($title) ?></title>
    </head>
    <body>
        <?= $this->content() ?>
    </body>
</html>