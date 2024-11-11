<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php if (isset($filters)): ?>
    <form>
        <?= $this->render($filters, get_defined_vars()) ?>
        <button type="submit">Filter</button>
        <input
            type="hidden"
            name="order"
            value="<?= $this->e(isset($order) ? $this->e($order) : '') ?>" />
    </form>
<?php endif ?>