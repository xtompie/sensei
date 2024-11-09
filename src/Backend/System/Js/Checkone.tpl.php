<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/util.tpl.php') ?>
<script>
var backend = backend || {};
backend.checkone = (function () {
    return function (ctx) {
        ctx
            .up('[backend-checkone-space]')
            .all('[backend-checkone-item]')
            .filter(option => option != ctx)
            .each(option => option.checked = false)
        ;
    };
})();
</script>