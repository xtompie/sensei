<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/Util.tpl.php') ?>
<script>
var backend = backend || {};
backend.resource = backend.resource || {};
backend.resource.image = backend.resource.image || {};
backend.resource.image.media = (function() {
	let prefix = "backend-resource-image-media";
	async function upload(ctx) {
		event.preventDefault();
		if (!ctx.files.length) {
			return;
		}
		reset(ctx);

		const space = ctx.closest(`[${prefix}-space]`);
		const file = ctx.files[0];
		const result = await request(space, file);

		if (result.hasOwnProperty("errors")) {
			const errorsElement = space.one(`${prefix}-errors`);
			errorsElement.style.display = "";
			errorsElement.innerText = result.errors[0].msg;
			return;
		}
		space.one(`[${prefix}-source]`).value = result.id;
		preview(space, file);
	}
	function reset(ctx) {
		const space = ctx.closest(`[${prefix}-space]`);
		space.all(`[${prefix}-img]`).each(img => { img.src = ""; });
		space.one(`[${prefix}-preview]`).style.display = "none";
		space.one(`[${prefix}-errors]`).innerHTML = "";
		space.one(`[${prefix}-source]`).value = "";
	}
	async function request(space, file) {
		const form = new FormData();
		form.append("_csrf", space.up('form').querySelector('[name="_csrf"]').value);
		form.append("upload", file);
		const response = await fetch("/media/image/upload", {
			method: "POST",
			body: form,
		});
		return await response.json();
	}
	function preview(space, file) {
		space.one(`[${prefix}-preview]`).style.display = "";
		const reader = new FileReader();
		reader.onload = () => {
			console.log(reader.result);
			space.all(`[${prefix}-img]`).forEach(img => img.src = reader.result);
		};
		reader.readAsDataURL(file);
	}
	return {
		upload,
		reset,
	};
})();
</script>