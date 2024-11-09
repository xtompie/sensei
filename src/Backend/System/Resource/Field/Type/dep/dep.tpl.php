<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php if ($mode == 'list') : ?>
    <?php
        $deplink = $this->service(\App\Backend\System\Resource\Pilot\ResourcePilotRegistry::class)->__call($deptype)->link('index');
        $depparam = $depparam ?? $reltype . '_id';
        $depurl = $this->alterUri($deplink['url'], [$depparam => $value['id']]);
        $depcount = $this->service(\App\Backend\System\Resource\Repository\ResourceRepositoryRegistry::class)->__call($deptype)->count([$depparam => $value['id']])
    ?>
    <a href="<?= $this->e($depurl) ?>">
        <?= $this->e($deplink['title']) ?>
        (<?= $this->e($depcount) ?>)
    </a>
<?php elseif ($mode == 'detail'): ?>
    <?php
        $deplink = $this->service(\App\Backend\System\Resource\Pilot\ResourcePilotRegistry::class)->__call($deptype)->link('index');
        $depparam = $depparam ?? $reltype . '_id';
        $depurl = $this->alterUri($deplink['url'], [$depparam => $value['id']]);
        $depcount = $this->service(\App\Backend\System\Resource\Repository\ResourceRepositoryRegistry::class)->__call($deptype)->count([$depparam => $value['id']])
    ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/begin.tpl.php') ?>
    <a href="<?= $this->e($depurl) ?>">
        <?= $this->e($depcount) ?>
    </a>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/end.tpl.php') ?>
<?php endif ?>
