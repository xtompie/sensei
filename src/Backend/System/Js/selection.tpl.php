<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/httpbuildquery.tpl.php') ?>
<?= $this->import('/src/Backend/System/Js/modal.tpl.php') ?>
<?= $this->import('/src/Backend/System/Js/util.tpl.php') ?>
<script>
var backend = backend || {};
backend.selection = (function () {
    function ids(ctx) {
        return ctx
            .up('[backend-selection-space]')
            .all('[backend-selection-id]')
            .filter(i => i.checked)
            .map(i => i.value)
        ;
    }
    function result(resource, ids) {
        window.location.href = '/backend/resource/' + resource + '?' + backend.httpbuildquery(
            ids.length ? {_selection_result: ids} : {_selection_cancel: true}
        );
    }
    function commit(ctx, resource) {
        result(resource, ids(ctx));
    };
    function cancel(resource) {
        result(resource, []);
    };
    function select(resource, single, callback) {
        backend.modal.open(
            '/backend/resource/' + resource + '?' + backend.httpbuildquery({
                _selection: true, _selection_single: single ? true : null
            }),
            function (result) {
                if (!result) {
                    return;
                }
                callback(result);
            }
        );
    };
    return {
        commit,
        cancel,
        select,
    };
})();
</script>
