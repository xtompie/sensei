<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>

<?php if ($mode == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?php
        $src = null;
        if ($value) {
            $src = App\Media\Application\Model\Image::tryFrom($value)
                ?->variants()
                ->filterByPreset(\App\Media\Application\Model\ImagePreset::l())
                ->first()
                ?->url()
            ;
        }
    ?>

    <?= $this->e($value) ?>

    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode == 'form'): ?>
    <?= $this->import('/src/Backend/Resource/Image/Upload.tpl.php') ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
    <?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */
 ?>
    <?php
        $src = null;
        if ($value) {
            $src = App\Media\Application\Model\Image::tryFrom($value)
                ?->variants()
                ->filterByPreset(\App\Media\Application\Model\ImagePreset::l())
                ->first()
                ?->url()
            ;
        }
    ?>
    <div
        backend-resource-image-media-space
    >
        <input
            backend-resource-image-media-source
            type="text"
            name="<?= $this->e($name) ?>"
            value="<?= $this->e($value) ?>"
        />
        <input
            backend-resource-image-media-file
            onchange="backend.resource.image.media.upload(this)"
            type="file"
            accept="image/*"
        />
        <div
            backend-resource-image-media-errors
        >
            <?= $this->render('/src/Backend/System/Resource/Field/Form/Errors.tpl.php', get_defined_vars()) ?>
        </div>
        <div
            backend-resource-image-media-preview
            class="relative"
            <?php if ($src === null) : ?>
                style="display: none;"
            <?php endif ?>
        >
            <div
                onclick="this.up('[backend-resource-image-media-space]').one('[backend-resource-image-media-modal]').classList.remove('hidden')"
                class="mt-3 cursor-pointer"
            >
                <img
                    backend-resource-image-media-img
                    src="<?= $this->e($src ?? '') ?>"
                    class="w-full rounded shadow"
                />
            </div>

            <div
                backend-resource-image-media-modal
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden"
                onclick="if (event.target === this) this.classList.add('hidden')"
            >
                <div class="relative bg-white rounded shadow-lg max-w-md w-full">
                    <img
                        backend-resource-image-media-img
                        src="<?= $this->e($src ?? '') ?>"
                        class="w-full rounded-t"
                    />
                    <a class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        onclick="this.up('[backend-resource-image-media-space]').one('[backend-resource-image-media-modal]').classList.add('hidden')">
                        ✕
                </a>
                </div>
            </div>

            <a
                backend-resoruce-image-media-reset
                onclick="backend.resource.image.media.reset(this)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </a>

        </div>
    </div>
<?php endif ?>