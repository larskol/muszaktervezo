<div class="row">
    <label for="restriction_date" class="col-12"><?= lang("Icons.iconFormWhen") ?> <?= lang("Form.formWhen") ?></label>
    <div class="col-12">
        <input type="date" class="form-control form-control-sm input-style" name="restriction_date" placeholder="<?= lang("Form.formPlaceholderDate") ?>">
    </div>
</div>
<div class="row">
    <?php if(isset($options, $available)): foreach($available as $key => $value): if($value): ?>
    <div class="col-4">
        <label><input type="radio" value="<?= ($key+1) ?>" class="" name="restriction_date_type"> <?= lang("Site.siteRestrictionOption_{$options[$key]}") ?></label>
    </div>
    <?php endif; endforeach; endif; ?>
</div>