    <div class="card no-bgcolor card-border">
        <div class="card-body">
            <h5 class="card-title">
                <?= $title ?? '' ?>
                <?php if(isset($backButtonUrl)): ?>
                    <a href="<?= $backButtonUrl?>" id="form-back-button" class="btn btn-sm btn-dark float-right">
                        <span class="btn-icon"><?= lang("Icons.iconBack") ?></span> <?= lang("Form.formBack") ?>
                    </a>
                <?php endif; ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted empty-space-bottom"><?= $subtitle ?? '' ?></h6>
            <div class="row">
                <div class="col-12" id="result-messages"></div>
            </div>
            <?= form_open('', ['id' => 'restriction-form']) ?>
            <div class="row form-group">
                <div class="col-12">
                    <input type="hidden" readonly name="id" class="form-control form-control-sm input-style" value="<?= isset($item, $item->id) ? $item->id : '' ?>">
                </div>
            </div>
            <div class="row form-group">
                <label for="name" class="col-lg-2 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormName") ?> <?= lang("Form.formName") ?>
                </label>
                <div class="col-lg-7">
                    <input type="text" name="name" readonly class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formName") ?>" value="<?= isset($item, $item->name) ? $item->name : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="cost" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                <?= lang("Icons.iconFormCost") ?> <?= lang("Form.formMinimumCost") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="number" min="0" name="cost" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formMinimumCost") ?>" value="<?= isset($item, $item->cost) ? $item->cost : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-12">
                    <button type="submit" id="form-save-button" class="btn btn-sm btn-success">
                        <span class="btn-icon"><?= lang("Icons.iconSave") ?></span> <?= lang("Form.formSave") ?>
                    </button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
<script>
    $('form#restriction-form').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    postFormData('/restrictions/save', '#restriction-form', '#form-save-button', <?= isset($item) ? "'/restrictions'" : "''" ?>);
</script>