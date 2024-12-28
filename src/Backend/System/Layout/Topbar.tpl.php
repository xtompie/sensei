<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<div class="flex justify-between border-b border-border min-h-14">
    <div class="flex-shrink-0 mx-2 my-1">
		<?= $this->render('/src/Backend/System/Layout/Breadcrumb.tpl.php', get_defined_vars()) ?>
    </div>
    <div class="flex-shrink-0 mx-2 my-1">
		<?= $this->render('/src/Backend/System/Layout/Topright.tpl.php', get_defined_vars()) ?>
    </div>
</div>
