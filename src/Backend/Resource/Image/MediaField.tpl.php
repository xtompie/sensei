<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>

<?php $value = isset($value[$name]) ? $value[$name] : '' ?>

<?php if ($mode == 'list'): ?>
    <?= $this->e($value) ?>
<?php elseif ($mode == 'detail'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/Begin.tpl.php', get_defined_vars()) ?>
    <?= $this->e($value) ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Detail/End.tpl.php', get_defined_vars()) ?>
<?php elseif ($mode == 'form'): ?>
    <?= $this->render('/src/Backend/System/Resource/Field/Form/Begin.tpl.php', get_defined_vars()) ?>
        <input
            type="text"
            name="<?= $this->e($name) ?>"
            value="<?= $this->e($value) ?>"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-400"
        />
    <?= $this->render('/src/Backend/System/Resource/Field/Form/End.tpl.php', get_defined_vars()) ?>

    <?= $this->import('/src/Backend/Resource/Image/Upload.tpl.php') ?>



    <div backend-resource-image-upload>
                <div>
                    <input type="hidden" name="{{ field.name }}" value="{{ field.value }}" data-mediaimageupload-sources/>

                    <input
                        type="file"
                        accept="image/*"
                        name="image"
                        onchange="mediaImageUpload.upload(this)"
                        data-mediaimageupload-input
                        class="
                            form-control
                            {% if field.errors|any %}
                                is-invalid
                            {% endif %}
                        "
                    />
                    <div class="invalid-feedback mt-1" data-mediaimageupload-error>
                        <div class="fs-6">
                            <span class="{% if field.errors|any %} is-invalid {% endif %} "></span>
                            {% include '@backend/resource/field/field_errors.html.twig' %}
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div data-mediaimageupload-previewwrapper style="display: none;">
                            <div class="mt-3">

                                <div style="cursor: pointer;" class="mt-3" data-bs-toggle="modal" data-bs-target="#imgUploadedNew">
                                    <img src="" class="img-fluid w-100" data-mediaimageupload-preview/>
                                </div>

                                <div class="modal fade" id="imgUploadedNew" data-bs-keyboard="false" tabindex="-1" aria-labelledby="imgUploadedNew" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content ">
                                            <img src="" data-bs-toggle="modal" data-bs-target="#imgUploadedNew" class="img-fluid w-100" data-mediaimageupload-preview/>
                                            <button type="button" class="btn btn-light btn-sm position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-light mt-2" onclick='mediaImageUpload.reset(this)' data-mediaimageupload-remove>
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            </div>
                        </div>
                        {% if field.image is not null %}
                            <div data-mediaimageupload-byserverpreviewwrapper>
                                <div class="mt-3">
                                    <div style="cursor: pointer;" class="mt-3" data-bs-toggle="modal" data-bs-target="#imgFromServerUpload">
                                        <img src="{{ field.image.variants.all[2].url }}" class="img-fluid w-100"/>
                                    </div>

                                    <div class="modal fade" id="imgFromServerUpload" data-bs-keyboard="false" tabindex="-1" aria-labelledby="imgFromServerUpload" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <img src="{{ field.image.variants.all[2].url }}" data-bs-toggle="modal" data-bs-target="#imgFromServerUpload" class="img-fluid w-100"/>
                                                <button type="button" class="btn btn-light btn-sm position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-light mt-2" onclick='mediaImageUpload.reset(this)' data-mediaimageupload-remove>
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </div>
                            </div>
                        {% endif %}
                    </div>
                </div>
            </div>

<?php endif ?>