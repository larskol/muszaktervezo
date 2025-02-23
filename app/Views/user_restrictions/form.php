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

            <div class="row">
                <label for="datepicker-input" class="col-12 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormDate") ?> <?= lang("Form.formDate") ?> - <?= lang("Form.formWeekFinder") ?>
                </label>
                <div class="col-lg-4 col-md-8 form-group">
                    <input type="date" class="form-control form-control-sm input-style" id="datepicker-input">
                </div>
                <div class="col-lg-4 col-md-8 form-group">
                    <span id="week-of-the-year"></span>
                </div>
            </div>
            <div class="row">
                <label class="col-lg-4 col-md-6 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormBonusPoint") ?> <?= lang("Form.formBonusPointsSpentThisMonth") ?>
                </label>
                <div class="col-lg-4 col-md-6 form-group">
                <?= lang("Icons.iconFormBonusPoint") ?> <span class="text-bold" id="bonus-points-spent"><?= $spentPoints ?? "0" ?></span>
                </div>                
            </div>
            <div class="row">
                <label class="col-lg-4 col-md-6 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormBonusPoint") ?> <?= lang("Form.formBonusPointsAvailableThisMonth") ?>
                </label>
                <div class="col-lg-4 col-md-6 form-group">
                <?= lang("Icons.iconFormBonusPoint") ?> <span class="text-bold" id="bonus-points-available"><?= $availablePoints ?? "0" ?></span>
                </div>                
            </div>
            <?= form_open('', ['class' => 'row restriction-selector']) ?>
                <label for="restriction_type" class="col-12 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormRestriction") ?> <?= lang("Form.formRestriction") ?>
                </label>
                <div class="col-lg-4 col-md-8 form-group">
                    <select name="restriction_type" class="form-control form-control-sm input-style" id="restriction-name">
                        <option value=""><?= lang("Site.siteChoose") ?></option>
                        <?php if(isset($restrictions)): foreach($restrictions as $key => $value): ?>
                            <option value="<?= $value->id?>"><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-lg-4 col-md-8 form-group" id="restriction-value-wrapper"></div>
                <div class="col-lg-2 col-md-3 form-group" id="restriction-cost-wrapper"></div>
                <div class="col-lg-1 col-md-2 form-group">
                    <button type="button" id="add-restriction-btn" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Form.formAdd') ?>"><i class="bi bi-plus"></i></button>
                </div>
                <div class="col-lg-4 col-md-8 form-group"></div>
                <div class="col-lg-4 col-md-8 form-group" id="restriction-options-wrapper"></div>
                <div class="col-lg-4 col-md-8 form-group"></div>
            <?= form_close() ?>
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
                    <?= view_cell('\App\Controllers\UserRestrictions::showItem', get_user_selected_restriction_item_data($value)) ?>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
<script>
    $('form#restriction-selector').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });

    $('#restriction-name').on('change', function(){
        $('#restriction-value-wrapper').html('');
        let data = new FormData($('form.restriction-selector').get(0));
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: '/my-restrictions/get-input',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data) {
                $('#result-messages').html(data.message);
                fadeResultmessages();
                if(data.status){
                    if(data.view){
                        $('#restriction-value-wrapper').html(data.view);
                        $('#restriction-cost-wrapper').html(data.costView);
                        if(data.optionsView){
                            $('#restriction-options-wrapper').html(data.optionsView);
                        }else{
                            $('#restriction-options-wrapper').html('');
                        }
                    }
                }
                if(data.errors){
                    $('#result-messages').html(data.errors);
                }
            },
            error: function(data){
                
            }
        });
    });

    $('#add-restriction-btn').on('click', function(){
        let data = new FormData($('form.restriction-selector').get(0));
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: '/my-restrictions/save',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data) {
                $('#result-messages').html(data.message);
                fadeResultmessages();
                if(data.status){
                    if(data.view){
                        $('form.restriction-selector').get(0).reset();
                        $('#restriction-value-wrapper').html('');
                        $('#restriction-cost-wrapper').html('');
                        $('#restriction-options-wrapper').html('');
                        $('#restrictions-wrapper').append(data.view);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                    if(data.spentPoints){
                        $('#bonus-points-spent').html(data.spentPoints);
                    }
                    if(data.availablePoints){
                        $('#bonus-points-available').html(data.availablePoints);
                    }
                }
                if(data.errors){
                    $('#result-messages').html(data.errors);
                }
            },
            error: function(data){
                
            }
        });
    });
    
    $(function(){
        let dateInput = $('#datepicker-input');
        dateInput.val(moment().format('YYYY-MM-DD'));
        
        getWeekOfTheYear(dateInput, '#week-of-the-year', '<?= lang("Site.siteNthWeek") ?>');

        dateInput.on("input", function(){
            getWeekOfTheYear($(this), '#week-of-the-year', '<?= lang("Site.siteNthWeek") ?>');
        });
    });
</script>