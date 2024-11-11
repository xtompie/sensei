<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/Util.tpl.php') ?>
<script>
var backend = backend || {};
backend.dropdown = (function () {
    const _space = '[backend-dropdown-space]';
    const _panel = '[backend-dropdown-panel]';
    function closeother(leave) {
        let a = document
            .al(_panel)
            .filter(function(el) { return el.style.display !== 'none' })
            .filter(function(el) { return !leave.includes(el) })
            .each(function(el) { el.style.display = 'none' })
        ;
    };
    function closeall(event) {
        let element = event.target;
        const parents = [];
        while (element) {
            parents.push(element);
            element = element.parentElement;
        }
        const leave = parents.filter(el => el.matches(_panel));
        closeother(leave);
    };
    function toggle(ctx, event) {
        const panel = ctx.up(_space).one(_panel);
        panel.style.display = panel.style.display == '' ? 'none' : '';
        event.stopPropagation();
        closeother([panel]);
    };
    return {
        closeall,
        toggle,
    }
})();
</script>