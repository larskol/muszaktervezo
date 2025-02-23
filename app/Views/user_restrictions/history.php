<script type="text/javascript" src="/assets/js/moment.js"></script>
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
            
            <?= form_open('', ['method' => 'get', 'id' => 'restriction-month-selector']) ?>
            <div class="row">
                <?php if(isset($dates) && is_array($dates) && !empty($dates)): ?>
                <label for="datepicker-input" class="col-12 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormDate") ?> <?= lang("Form.formEarlierRestrictions") ?>
                </label>
                <div class="col-lg-3 col-md-8 form-group">
                   <select name="month" id="history-month-selector" class="form-control form-control-sm input-style">
                    <option value=""><?= lang("Form.formCurrentMonth") ?></option>
                    <?php foreach ($dates as $key => $value): ?>
                        <option value="<?= $value->date ?>" <?= isset($selectedMonth) && $selectedMonth == $value->date ? "selected" : "" ?>><?= $value->date ?></option>
                    <?php endforeach; ?>
                   </select>
                </div>
                <?php else: ?>
                    <div class="col-12"><?= lang("Form.formRestrictionsNotAddedYet") ?></div>
                <?php endif; ?>
            </div>
            <?= form_close() ?>
            <div class="row">
                <label class="col-lg-4 col-md-6 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormBonusPoint") ?> <?= lang("Form.formBonusPointsSpent") ?>
                </label>
                <div class="col-lg-4 col-md-6 form-group">
                <?= lang("Icons.iconFormBonusPoint") ?> <span class="text-bold" id="bonus-points-spent"><?= $spentPoints ?? "0" ?></span>
                </div>                
            </div>
            <div class="row">
                <label class="col-lg-4 col-md-6 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormBonusPoint") ?> <?= lang("Form.formBonusPointsRemained") ?>
                </label>
                <div class="col-lg-4 col-md-6 form-group">
                <?= lang("Icons.iconFormBonusPoint") ?> <span class="text-bold" id="bonus-points-available"><?= $availablePoints ?? "0" ?></span>
                </div>                
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormRestriction") ?> <?= lang("Form.formRestriction") ?>
                    </label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormValue") ?> <?= lang("Form.formValue") ?>
                    </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormCost") ?> <?= lang("Form.formCost") ?>
                    </label>
                </div>
            </div>
            <div id="restrictions-wrapper">
                <?php if(isset($addedItems)): foreach($addedItems as $key => $value): ?>
                    <?= view_cell('\App\Controllers\UserRestrictions::showHistoryItem', get_user_selected_restriction_item_data($value)) ?>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            $('#history-month-selector').on('change', function(e){
                $('#restriction-month-selector').submit();
            });
        });
    </script>