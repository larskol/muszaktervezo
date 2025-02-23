<script src="/assets/js/passwordGenerator.js"></script>
<div class="row form-group">
    <h5 class="col-12"><?= lang("PasswordGenerator.pwGenPasswordGenerator") ?></h5>
</div>
<div class="row form-group">
    <label for="lastname" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
        <?= lang("Icons.iconPwGenPassword") ?> <?= lang("PasswordGenerator.pwGenPassword") ?>
    </label>
    <div class="col-lg-5 col-md-8">
        <input type="text" id="pw_gen_input" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formPassword") ?>">
    </div>
</div>
<div class="row form-group">
    <label for="lastname" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
        <?= lang("Icons.iconPwGenLength") ?> <?= lang("PasswordGenerator.pwGenPasswordLength") ?>
    </label>
    <div class="col-lg-2 col-md-4">
        <select id="pw_gen_length" class="form-control form-control-sm input-style">
        <?php for ($i=6; $i <= 20; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor ?>
        </select>
    </div>
</div>
<div class="row form-group">
<label for="" class="col-lg-2 col-md-4 col-form-label col-form-label-sm"></label>
    <div class="col-lg-2 col-md-4">
        <div>
        <label>
            <input type="checkbox" id="pw_gen_numbers" checked> <?= lang("PasswordGenerator.pwGenUseNumbers") ?>
        </label>
        </div>
        <div>
        <label>
            <input type="checkbox" id="pw_gen_special_characters"> <?= lang("PasswordGenerator.pwGenUseSpecialCharacters") ?>
        </label>
        </div>
    </div>
    <div class="col-lg-3 col-md-4">
        <button type="button" class="btn btn-sm btn-info mr-2 mt-1" onclick="generatePassword('#pw_gen_input', '#pw_gen_numbers','#pw_gen_special_characters','#pw_gen_length', '#password_input', '#password_confirm_input')"><?= lang("Icons.iconPwGenGenerate") ?> <?= lang("PasswordGenerator.pwGenPasswordGenerate") ?></button>
        <button type="button" class="btn btn-sm btn-success mr-2 mt-1" onclick="usePassword('#pw_gen_input', '#password_input', '#password_confirm_input')"><?= lang("Icons.iconPwGenSet") ?> <?= lang("PasswordGenerator.pwGenPasswordSet") ?></button>
        <button type="button" class="btn btn-sm btn-primary mt-1" onclick="clearPassword('#pw_gen_input', '#password_input', '#password_confirm_input')"><?= lang("Icons.iconPwGenEmpty") ?> <?= lang("PasswordGenerator.pwGenPasswordEmpty") ?></button>
    </div>
</div>