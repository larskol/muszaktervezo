<div class="card no-bgcolor card-border">
        <div class="card-body">
            <h5 class="card-title"><?= $title ?? '' ?></h5>
            <h6 class="card-subtitle mb-2 text-muted empty-space-bottom"><?= $subtitle ?? '' ?></h6>
            <div class="row">
                <div class="col-12" id="result-messages"></div>
            </div>
            <?= form_open('', ['id' => 'login-form']) ?>
            <div class="row form-group">
                <label for="name" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormEmail") ?> <?= lang("Form.formEmail") ?>
                </label>
                <div class="col-lg-7 col-md-8">
                    <input type="email" name="email" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formEmail") ?>" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
            <label for="name" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormPassword") ?> <?= lang("Form.formPassword") ?>
                </label>
                <div class="col-lg-7 col-md-8">
                    <input type="password" name="password" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formPassword") ?>" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-12">
                    <button type="submit" id="form-save-button" class="btn btn-sm btn-success">
                        <span class="btn-icon"><?= lang("Icons.iconLogin") ?></span> <?= lang("Form.formLogin") ?>
                    </button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
<script>
    $('form#login-form').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    authUser('#login-form', '#form-save-button');
</script>