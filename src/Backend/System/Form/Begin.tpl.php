<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<form method="POST">
    <input type="hidden" name="_csrf" value="<?= $this->csrf() ?>" />
    <div resterrors-target></div>
    <div class="space-y-12">
        <div class="pb-12">
