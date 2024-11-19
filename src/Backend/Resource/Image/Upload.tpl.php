<?php /** @var App\Shared\Tpl\Tpl $this */ ?>
<?= $this->import('/src/Backend/System/Js/Util.tpl.php') ?>
<script>
var backend = backend || {};
backend.resource = backend.resource || {};
backend.resource.image = backend.resource.image || {};
backend.resource.image.upload = (function () {

          async function upload(ctx) {
            event.preventDefault();

            if (!ctx.files.length) {
              return;
            }

            reset(ctx);

            const wrapper = ctx.closest("[data-mediaImageUpload-wrapper]");
            const form = ctx.closest("form");

            const previewWrapper = wrapper.querySelector(
              "[data-mediaImageUpload-previewWrapper]"
            );
            const errorWrapper = wrapper.querySelector("[data-mediaImageUpload-error]");
            const previewImages = wrapper.querySelectorAll(
              "[data-mediaImageUpload-preview]"
            );
            const source = wrapper.querySelector("[data-mediaImageUpload-sources]");

            try {
              const formData = new FormData();

              formData.append("_csrf", form.querySelector('[name="_csrf"]').value);
              formData.append("image", event.target.files[0]);

              const response = await fetch("/backend/media/create/image", {
                method: "POST",
                body: formData,
              });

              const result = await response.json();

              if (result.hasOwnProperty("errors")) {
                errorWrapper.style.display = "block";
                ctx.classList.add("is-invalid");
                errorWrapper.innerText = result.errors[0].msg;
                return;
              } else {
              const event = new CustomEvent("mediaImageUploadOk", {detail: result.name});
              document.dispatchEvent(event);
                previewWrapper.style.display = "block";
                getImgUrl(ctx, previewImages);
                source.value = result.identifier;
              }
            } catch (error) {
              console.log(error);
            }
          }

          function reset(ctx) {
            const wrapper = ctx.closest("[data-mediaImageUpload-wrapper]");
            if (wrapper) {
            const byServerPreviewWrapper = wrapper.querySelector("[data-mediaImageUpload-byServerPreviewWrapper]")
              const previewWrapper = wrapper.querySelector(
                "[data-mediaImageUpload-previewWrapper]"
              );
              const errorWrapper = wrapper.querySelector(
                "[data-mediaImageUpload-error]"
              );
              const previewImages = wrapper.querySelectorAll(
                "[data-mediaImageUpload-preview]"
              );
              const source = wrapper.querySelector("[data-mediaImageUpload-sources]");
              const input = wrapper.querySelector("[data-mediaImageUpload-input]");

            previewImages.forEach(img => {
                 img.src = "";
            })

              previewWrapper.style.display = "none";
              errorWrapper.innerHTML = "";
              source.value = "";
              input.classList.remove("is-invalid");

            if(byServerPreviewWrapper) {
                byServerPreviewWrapper.style.display = "none"
            }
            }
          }

          function getImgUrl(input, imgs) {
            if (input.files && input.files[0]) {
              const reader = new FileReader();

              reader.addEventListener(
                "load",
                function () {
                imgs.forEach(img => {
                      img.src = reader.result;

                })
                },
                false
              );

              reader.readAsDataURL(input.files[0]);
            }
          }

          return {
            upload,
            reset,
          };
    })();

</script>
