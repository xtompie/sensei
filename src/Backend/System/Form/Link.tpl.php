<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php if (isset($link)) : ?>
    <div class="mt-2">
        <a
            href="<?= $this->e($link['uri']) ?>"
            class="
                text-sm text-gray-700 hover:underline
            ">
            <?= $this->e($link['text']) ?>
        </a>
    </div>
<?php endif ?>