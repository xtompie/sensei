<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<form method="POST" class="mb-8">
    <input type="hidden" name="_csrf" value="<?= $this->csrf() ?>" />
    <div resterrors-target></div>
