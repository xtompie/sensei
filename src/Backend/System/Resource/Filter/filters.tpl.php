<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php if (isset($filters)): ?>
    <form>
        <?= $this->render($filters) ?>
        <button type="submit">Filter</button>
        <input
            type="hidden"
            name="order"
            value="<?php echo isset($order) ? $this->e($order) : '' ?>" />
    </form>
<?php endif ?>