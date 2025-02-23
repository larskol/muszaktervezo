<select name="restriction_value" class="form-control form-control-sm input-style" id="restriction-value">
    <option value=""><?= lang("Site.siteChoose") ?></option>
    <?php if(isset($data)): foreach ($data as $key => $value): ?>
        <option value="<?= $value->id ?>"><?= $value->name ?> (<?= (new \DateTime($value->start_time))->format("H:i")." - ".(new \DateTime($value->end_time))->format("H:i") ?>)</option>
    <?php endforeach; endif; ?>
</select>