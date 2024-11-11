<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/Util.tpl.php') ?>
<script>
var backend = backend || {};
backend.visible = (function () {
    function visible(ctx, tags) {
        const space = ctx.up('[backend-visible-space]');
        space.all('[backend-visible-tag]').each(function (el) {
            el.style.display = tags.includes(el.attr('backend-visible-tag')) ? '' : 'none';
        });
        space.attr('backend-visible-state', tags.join(' '));
    };
    function toggle(ctx, when, then, otherwise) {
        const space = ctx.up('[backend-visible-space]');
        visible(space, space.attr('backend-visible-state').split(' ').includes(when) ? then : otherwise);
    };
    return {
        visible,
        toggle,
    }
})();
</script>