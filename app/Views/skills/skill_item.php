<?= form_open('', ['class' => 'row form-group bottom-border skill-item-wrapper '.$class ?? '']) ?>
    <label class="col-lg-5 col-md-5 col-sm-12">
        <div class="row">
            <div class="col-12">
                <?= $name ?? '' ?>
            </div>
        </div>
    </label>
    <div class="col-lg-5 col-md-5 col-sm-12">
        <div class="row">
            <div class="col-12">
                <input type="hidden" name="knowledge" value="<?= $id ?? '' ?>" readonly>
                <select name="level" class="form-control form-control-sm input-style skill-knowledge-level">
                    <option value=""><?= lang("Site.siteChoose") ?></option>
                    <?php if(isset($knowledgeLevels)): foreach($knowledgeLevels as $key => $value): ?>
                        <option value="<?= $value->id?>" <?= isset($knowledgeValue) && $knowledgeValue == $value->id ? 'selected' : '' ?>><?= $value->name ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12">
        <div class="col-12">
            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Form.formRemove') ?>" onclick="removeSkillItem(new FormData($(this).closest('form.skill-item-wrapper').get(0)), this)">
                <?= lang("Icons.iconRemove") ?>
            </button>
        </div>
    </div>
<?= form_close() ?>