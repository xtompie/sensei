<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= $this->csrf() ?>" />
    <div resterrors-target></div>
