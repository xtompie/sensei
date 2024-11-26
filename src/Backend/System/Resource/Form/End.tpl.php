<?php /** @var \App\Shared\Tpl\Tpl $this */ ?>
<?php /** @var \App\Backend\System\Validation\UberErrorCollection $errors */ ?>

            <div class="col-span-full">
                <?= $this->render('/src/Backend/System/Resource/Form/Submit.tpl.php', [
                    'action' => $action,
                    'resource' => $resource,
                ]) ?>
            </div>

            <?php $rest = $errors->rest() ?>
            <div resterrors-list>
                <?php foreach ($rest as $err): ?>
                    <div class="invalid-feedback"><?= $this->e($err->message()) ?></div>
                <?php endforeach ?>
            </div>
            <script>
                document.querySelector('[resterrors-target]').appendChild(document.querySelector('[resterrors-list]'));
            </script>

        </div>
    </div>
</form>
