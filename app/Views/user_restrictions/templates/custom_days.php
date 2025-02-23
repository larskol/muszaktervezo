<select name="restriction_value" class="form-control form-control-sm input-style" id="restriction-value">
    <option value=""><?= lang("Site.siteChoose") ?></option>
    <?php for ($i=1; $i <= 7; $i++): ?>
        <option value="<?= $i ?>"><?= lang("Site.siteDay{$i}") ?></option>
    <?php endfor; ?>
</select>