<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<div class="flex justify-between mt-8">
    <div class="flex-shrink-0 text-xl leading-6 text-gray-900">
        <?= $this->e($title) ?>
    </div>
    <div class="flex-shrink-0">
        <?= $this->render('/src/Backend/System/Resource/More/More.tpl.php', [
            'action' => $action,
            'more' => $more,
        ]) ?>
    </div>
</div>
