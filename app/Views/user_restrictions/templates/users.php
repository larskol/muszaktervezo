<select name="restriction_value" class="form-control form-control-sm input-style" id="restriction-value">
    <option value=""><?= lang("Site.siteChoose") ?></option>
    <?php if(isset($data)): foreach($data as $key => $value): ?>
        <option value="<?= $value->id ?>"><?= "{$value->lastname} {$value->firstname} ({$value->email})" ?></option>
    <?php endforeach; endif; ?>
</select>