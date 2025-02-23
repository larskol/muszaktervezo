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
            <div class="row" >
                    <div class="col-12" id="result-messages"></div>
            </div>
            <?= form_open('', ['id' => 'capacity-demands-form']) ?>
            <div class="row form-group">
                <div class="col-12">
                    <input type="hidden" readonly name="id" class="form-control form-control-sm input-style" value="<?= isset($item, $item->id) ? $item->id : '' ?>">
                </div>
            </div>
            <div class="row form-group">
                <label for="department" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormDepartment") ?> <?= lang("Form.formDepartment") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <span id="cd-department"></span>
                </div>
            </div>
            <div class="row form-group">
                <label for="day" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormDate") ?> <?= lang("Form.formDay") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="date" name="day" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formDay") ?> <?= lang("Form.formDateFormat") ?>" value="<?= isset($item, $item->day) ? $item->day : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="shift_id" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormShift") ?> <?= lang("Form.formShift") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <select name="shift_id" class="form-control form-control-sm input-style" id="cd-shift_id">
                        <option value=""><?= lang("Site.siteChoose") ?></option>
                        <?php if(isset($shifts)): foreach($shifts as $key => $value): ?>
                            <option value="<?= $value->id ?>" <?= isset($item, $item->shift_id) && $item->shift_id == $value->id ? 'selected' : '' ?>><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                        </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="knowledge_id" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormKnowledge") ?> <?= lang("Form.formKnowledge") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <select name="knowledge_id" class="form-control form-control-sm input-style" id="cd-knowledge_id">
                        <option value=""><?= lang("Site.siteChoose") ?></option>
                        <?php if(isset($knowledge)): foreach($knowledge as $key => $value): ?>
                            <option value="<?= $value->id ?>" <?= isset($item, $item->knowledge_id) && $item->knowledge_id == $value->id ? 'selected' : '' ?>><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                        </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="knowledge_level_id" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormExperience") ?> <?= lang("Form.formExperience") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <select name="knowledge_level_id" class="form-control form-control-sm input-style" id="cd-knowledge_level_id">
                        <option value=""><?= lang("Form.formUndefined") ?></option>
                        <?php if(isset($knowledgeLevels)): foreach($knowledgeLevels as $key => $value): ?>
                            <option value="<?= $value->id ?>" <?= isset($item, $item->knowledge_level_id) && $item->knowledge_level_id == $value->id ? 'selected' : '' ?>><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                        </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="amount" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormAmount") ?> <?= lang("Form.formAmount") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="number" name="amount" min="0" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formAmount") ?>" value="<?= isset($item, $item->amount) ? $item->amount : '' ?>">
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
    $(function(){
        $('#cd-department').text($("#department-switcher option:selected").html());
    });
    $('form#capacity-demands-form').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    postFormData('/capacity-demands/save', '#capacity-demands-form', '#form-save-button', <?= isset($item) ? "'/capacity-demands'" : "''" ?>);
</script>