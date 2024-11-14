<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php
if (in_array($mode, ['list', 'detail'])) {
    $dep_link = $this->service(\App\Backend\System\Resource\Pilot\UberPilot::class)->link('index', $deptype);
    $dep_param = $dep_param ?? $reltype . '_id';
    $dep_url = $this->alterUri($dep_link['url'], [$dep_param => $value['id']]);
    $dep_count = $this->service(\App\Backend\System\Resource\Repository\UberRepository::class)->count($deptype, [$dep_param => $value['id']]);
}
?>

<?php if ($mode == 'list'): ?>
    <a href="<?= $this->e($dep_url) ?>">
        <?= $this->e($dep_link['title']) ?>
        (<?= $this->e($dep_count) ?>)
    </a>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php') ?>
    <a href="<?= $this->e($dep_url) ?>">
        <?= $this->e($dep_count) ?>
    </a>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php') ?>
<?php endif ?>
