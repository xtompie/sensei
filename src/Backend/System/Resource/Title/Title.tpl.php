<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<div class="pb-5 sm:flex sm:items-center sm:justify-between max-w-3xl">
    <h3 class="text-base font-semibold leading-6 text-gray-900">
        <?= $this->e($title) ?>
    </h3>
    <?= $this->render('/src/Backend/System/Resource/More/more.tpl.php', [
        'action' => $action,
        'more' => $more,
    ]) ?>
</div>


