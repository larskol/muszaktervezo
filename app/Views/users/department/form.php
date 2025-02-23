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
            <?= form_open('', ['id' => 'user-form']) ?>
            <div class="row form-group">
                <div class="col-12">
                    <input type="hidden" readonly name="id" class="form-control form-control-sm input-style" value="<?= isset($item, $item->id) ? $item->id : '' ?>">
                </div>
            </div>
            <div class="row form-group">
                <label for="lastname" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormName") ?> <?= lang("Form.formLastname") ?>
                </label>
                <div class="col-lg-5 col-md-8">
                    <input type="text" name="lastname" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formLastname") ?>" value="<?= isset($item, $item->lastname) ? $item->lastname : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="firstname" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormName") ?> <?= lang("Form.formFirstname") ?>
                </label>
                <div class="col-lg-5 col-md-8">
                    <input type="text" name="firstname" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formFirstname") ?>" value="<?= isset($item, $item->firstname) ? $item->firstname : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row form-group">
                <label for="email" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormEmail") ?> <?= lang("Form.formEmail") ?>
                </label>
                <div class="col-lg-5 col-md-8">
                    <input type="text" name="email" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formEmail") ?>" value="<?= isset($item, $item->email) ? $item->email : '' ?>">
                    <div class="invalid-feedback"></div>
                </div>                
            </div>
            <div class="row form-group">
                <label for="weekly_work_hours" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormWeeklyWorkHours") ?> <?= lang("Form.formWeeklyWorkHours") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="number" name="weekly_work_hours" min="0" step="0.5" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formWeeklyWorkHours") ?>" value="<?= isset($item, $item->weekly_work_hours) ? $item->weekly_work_hours : '0' ?>">
                    <div class="invalid-feedback"></div>
                </div>                
            </div>
            <div class="row form-group">
                <label for="paid_annual_leave" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormPaidAnnualLeave") ?> <?= lang("Form.formPaidAnnualLeave") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="number" name="paid_annual_leave" min="0" step="0.5" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formPaidAnnualLeave") ?>" value="<?= isset($item, $item->paid_annual_leave) ? $item->paid_annual_leave : '0' ?>">
                    <div class="invalid-feedback"></div>
                </div>                
            </div>
            <div class="row form-group">
                <label for="bonus_point" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormBonusPoint") ?> <?= lang("Form.formBonusPoint") ?>
                </label>
                <div class="col-lg-3 col-md-8">
                    <input type="number" name="bonus_point" min="0" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formBonusPoint") ?>" value="<?= isset($item, $item->bonus_point) ? $item->bonus_point : '0' ?>">
                    <div class="invalid-feedback"></div>
                </div>                
            </div>
            <div class="row form-group">
                <label for="password" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormPassword") ?> <?= lang("Form.formPassword") ?>
                </label>
                <div class="col-lg-5 col-md-8">
                    <input type="password" id="password_input" name="password" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formPassword") ?>" value="">
                    <div class="invalid-feedback"></div>
                </div>      
                <div class="col-lg-2 col-md-4">
                    <button type="button" id="change_input_type_btn" class="btn btn-sm btn-secondary" onclick="changeInputType(this, '#password_input')" data-switch='<?= lang("Icons.iconFormViewItem") ?> <?= lang("Form.formViewItem") ?>' data-switch-back='<?= lang("Icons.iconFormHideItem") ?> <?= lang("Form.formHideItem") ?>'><?= lang("Icons.iconFormViewItem") ?> <?= lang("Form.formViewItem") ?></button>
                </div>
            </div>
            <div class="row form-group">
                <label for="password_confirm" class="col-lg-2 col-md-4 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormPassword") ?> <?= lang("Form.formPasswordConfirm") ?>
                </label>
                <div class="col-lg-5 col-md-8">
                    <input type="password" id="password_confirm_input" name="password_confirm" class="form-control form-control-sm input-style" placeholder="<?= lang("Form.formPasswordConfirm") ?>" value="">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <?= view_cell("\App\Controllers\BaseController::showPasswordGenerator") ?>
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
    $('form#user-form').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });
    postFormData('/department-users/save', '#user-form', '#form-save-button', <?= isset($item) ? "'/department-users'" : "''" ?>, '#change_input_type_btn');
</script>