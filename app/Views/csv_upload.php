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
                <div class="col-12">
                        <?= $infoText ?? '' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="success-messages"></div>
            </div>
            <div class="row">
                <div class="col-12" id="error-messages"></div>
            </div>
            <?= form_open_multipart('', ['id' => 'csv-upload-form']) ?>
            <div class="row form-group">
                <label for="name" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormFile") ?> <?= lang("Form.formFile") ?>
                </label>
                <div class="col-lg-7 col-md-8">
                    <input type="file" name="csv" class="form-control form-control-sm input-style" required>
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
    $('form#csv-upload-form').find('input').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    postUploadData('<?= $uploadUrl ?? "" ?>', '#csv-upload-form', '#form-save-button');
</script>