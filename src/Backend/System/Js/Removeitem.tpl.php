<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/Util.tpl.php') ?>
<?= $this->import('/src/Backend/System/Js/Formsubmit.tpl.php') ?>
<script>
var backend = backend || {};
backend.removeitem = (function(name) {
    function remove(ctx) {
        ctx.up('[backend-removeitem]').remove();
    }
    function form(ctx) {
        f = backend.formsubmit.form(ctx);
        remove(ctx);
        backend.formsubmit.submit(f);
    }
    return {
        remove,
        form,
    };
})();
</script>
