<?= form_open("/department-switch", ["id" => "department-switch"]) ?>
<select name="department" class="form-control-sm input-style max-w-300" id="department-switcher">
    <?php if(isset($departments) && is_array($departments)): foreach($departments as $key => $item): ?>
        <option value="<?= $item->id ?>" <?= isset($current) && $item->id === $current ? 'selected' : '' ?>><?= $item->name ?></option>
    <?php endforeach; endif; ?>
</select>
<?= form_close() ?>