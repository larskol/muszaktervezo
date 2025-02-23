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
            <?= form_open('', ['id' => 'knowledge-form']) ?>
            <div class="row form-group">
                <div class="col-12">
                    <input type="hidden" readonly name="id" class="form-control form-control-sm input-style" value="<?= isset($item, $item->id) ? $item->id : '' ?>">
                </div>
            </div>
            <div class="row form-group">
                <label for="name" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormName") ?> <?= lang("Form.formName") ?>
                </label>
                <div class="col-lg-7 col-md-8">
                    <input type="text" name="name" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formName") ?>" value="<?= isset($item, $item->name) ? $item->name : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <?php if(isset($departments)): ?>
            <div class="row form-group">
                <label for="" class="col-lg-2 col-md-2 col-form-label col-form-label-sm">
                <?= lang("Icons.iconFormDepartments") ?> <?= lang("Form.formDepartments") ?>
                </label>
                <div class="col-lg-10 col-md-8">
                    <?php foreach ($departments as $key => $value): ?>
                    <div>
                        <label>
                            <input type="checkbox" name="departments[]" class="input-style" value="<?= $value->id ?>" <?= isset($item) && in_array($value->id, $item->departments) ? "checked" : "" ?>> <?= $value->name ?>
                        </label>
                    </div>
                    <div class="invalid-feedback"></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
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
    $('form#knowledge-form').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    postFormData('/knowledge/save', '#knowledge-form', '#form-save-button', <?= isset($item) ? "'/knowledge'" : "''" ?>);
</script>