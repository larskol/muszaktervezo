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
            
            <?= form_open('', ['method' => 'get', 'id' => 'capacity-demand-month-selector']) ?>
            <div class="row">
                <?php if(isset($dates) && is_array($dates) && !empty($dates)): ?>
                <label for="datepicker-input" class="col-12 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormDate") ?> <?= lang("Form.formEarlierCapacityDemands") ?>
                </label>
                <div class="col-lg-3 col-md-8 form-group">
                   <select name="month" id="history-month-selector" class="form-control form-control-sm input-style">
                    <option value=""><?= lang("Form.formCurrentPeriod") ?></option>
                    <?php foreach ($dates as $key => $value): ?>
                        <option value="<?= $value->date ?>" <?= isset($selectedMonth) && $selectedMonth == $value->date ? "selected" : "" ?>><?= $value->date ?></option>
                    <?php endforeach; ?>
                   </select>
                </div>
                <?php else: ?>
                    <div class="col-12"><?= lang("Form.formCapacityDemandsNotAddedYet") ?></div>
                <?php endif; ?>
            </div>
            <?= form_close() ?>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormDate") ?> <?= lang("Form.formDay") ?>
                    </label>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-12">
                        <label for="" class="col-form-label col-form-label-sm">
                            <?= lang("Icons.iconFormKnowledge") ?> <?= lang("Form.formKnowledge") ?>
                        </label>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="" class="col-form-label col-form-label-sm">
                                <?= lang("Icons.iconFormShift") ?> <?= lang("Form.formShift") ?>
                            </label>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="" class="col-form-label col-form-label-sm">
                                <?= lang("Icons.iconFormExperience") ?> <?= lang("Form.formExperience") ?>
                            </label>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="" class="col-form-label col-form-label-sm">
                                <?= lang("Icons.iconFormAmount") ?> <?= lang("Form.formAmount") ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(isset($items)): foreach($items as $key => $value): ?>
            <div class="row bottom-border">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label for="" class="col-form-label col-form-label-sm text-bold">
                        <?= $key ?> <?= $value["day"] ?? "" ?>
                    </label>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <?php if(is_array($value["items"])): foreach($value["items"] as $k => $dayItem): ?>
                        <div class="row <?= count($value["items"]) > 1 && $k != (count($value["items"])-1) ? 'bottom-border-darker' : '' ?>">
                        <div class="col-lg-5 col-md-6 col-sm-12 col-12"><?= $dayItem->knowledge_name ?? "" ?></div>
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12"><?= $dayItem->shift_name ?? "-" ?><br> (<?= $dayItem->shift_time ?? "-" ?>)</div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-12"><?= $dayItem->knowledge_level_name ?? "-" ?></div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-12"><?= $dayItem->amount ?? "" ?></div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
    <script>
        $(function(){
            $('#history-month-selector').on('change', function(e){
                $('#capacity-demand-month-selector').submit();
            });
        });
    </script>