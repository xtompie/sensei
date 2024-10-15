<script>
var backend = backend || {};
backend.formsubmit = (function () {
    function form(ctx) {
        console.log(ctx);
        console.log(ctx.closest('form'));
        return ctx.matches('form') ? ctx : ctx.closest('form');
    };
    function next(form, name) {
        let next = -1;
        for (var [key, value] of new FormData(form)) {
            if (key.startsWith(name)) {
                let offset = key.substring(name.length+1).split(']')[0];
                next = Math.max(next, offset);
            }
        }
        next =  next === -1 ? 0 : next + 1;
        return next;
    }
    function val(f, name, value) {
        if (value !== null && typeof value === 'object') {
            for (let property in value) {
                if (!value.hasOwnProperty(property)) {
                    continue;
                }
                val(f, `${name}[${property}]`, value[property])
            }
        }
        else {
            scalar(f, name, value)
        }
    }
    function scalar(f, name, value) {
        const input = document.createElement("input");
        input.setAttribute("name", name);
        input.setAttribute("type", "hidden");
        input.value = value;
        f.appendChild(input);
    }
    function moveto(f) {
        scalar(f, "_moveto", document.documentElement.scrollTop);
    }
    function set(ctx, name, value) {
        const f = form(ctx);
        val(f, name, value);
        moveto(f);
        f.submit();
    };
    function add(ctx, name, values) {
        const f = form(ctx);
        for (index in values) {
            const n = next(f, name);
            val(f, `${name}[${n}]`, values[index]);
        }
        moveto(f);
        f.submit();
    };
    function submit(ctx) {
        const f = form(ctx);
        moveto(f);
        f.submit();
    };
    return {
        set,
        add,
        submit,
        form,
    };
})();
</script>