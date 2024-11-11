<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php $fields = $fields ?? "/src/Backend/Resource/$resource/Fields.tpl.php" ?>

<?php $this->push('/src/Backend/System/Layout/Layout.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Title/Title.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/Begin.tpl.php', [
    ...get_defined_vars(),
]) ?>

<?= $this->render($fields, [
    'mode' => 'form',
    ...get_defined_vars(),
]) ?>

<?= $this->render('/src/Backend/System/Resource/Form/End.tpl.php', [
    ...get_defined_vars(),
]) ?>

