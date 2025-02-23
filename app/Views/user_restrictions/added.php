<div class="row form-group bottom-border user-restriction-item">
    <div class="col-lg-4 col-md-8 col-sm-12">
        <?= $name ?? '' ?>
    </div>
    <div class="col-lg-4 col-md-8 col-sm-12">
        <?= $value_text ?? '' ?>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-12">
        <?= $cost ?? '' ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12">
        <button type="button" class="btn btn-sm btn-danger" onclick="removeUserRestrictionItem(this)" data-item-id="<?= $id ?? '' ?>" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Form.formRemove') ?>"><?= lang("Icons.iconRemove") ?></button>
    </div>
</div>