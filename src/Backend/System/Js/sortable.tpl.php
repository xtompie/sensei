<style>
.sortable-handle {
    cursor: move;
    cursor: -webkit-grabbing;
}
.sortable-ghost {
    background-color: #eff2f7;
}
[data-sortable] tr {
  counter-increment: sortable-counter;
}
[data-sortable] tr td:nth-child(2)::before {
  content: counter(sortable-counter) '.';
}
</style>
<script src="/assets/shared/sortable.js"></script>
{{ include_once("@backend/system/js/util.tpl.php") }}
<script>
var backend = backend || {};
backend.sortable = function (ctx) {
    Sortable.create(
        ctx.up('[backend-sortable-space]').one('[backend-sortable-sortable]'),
        {
            animation: 200,
            ghostClass: 'sortable-ghost',
            handle: '.sortable-handle'
        }
    );
}
</script>



