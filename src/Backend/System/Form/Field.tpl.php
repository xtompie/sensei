<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \Xtompie\Result\ErrorCollection $errors */ ?>

<div class="col-span-full mt-3 max-w-lg">
    <?= $this->render('/src/Backend/System/Form/Label.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Type.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Errors.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Desc.tpl.php', get_defined_vars()) ?>
    <?= $this->render('/src/Backend/System/Form/Link.tpl.php', get_defined_vars()) ?>
</div>
