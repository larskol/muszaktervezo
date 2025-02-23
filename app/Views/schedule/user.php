<div class="card no-bgcolor card-border">
        <div class="card-body">
            <h5 class="card-title">
                <?= $title ?? '' ?>
                <?php if(isset($backButtonUrl)): ?>
                    <a href="<?= $backButtonUrl?>" id="form-save-button" class="btn btn-sm btn-dark float-right">
                        <span class="btn-icon"><?= lang("Icons.iconBack") ?></span> <?= lang("Form.formBack") ?>
                    </a>
                <?php endif; ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted empty-space-bottom"><?= $subtitle ?? '' ?></h6>
            <div class="row" >
                    <div class="col-12" id="result-messages"></div>
            </div>
            
            <?= form_open('', ['method' => 'get', 'id' => 'schedule-month-selector']) ?>
            <div class="row">
                <?php if(isset($dates) && is_array($dates) && !empty($dates)): ?>
                <label for="month" class="col-12 col-form-label col-form-label-sm">
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
                    <div class="col-12"><?= lang("Form.formScheduleNotAddedYet") ?></div>
                <?php endif; ?>
            </div>
            <?= form_close() ?>
            <?php if(isset($items) && !empty($items)): ?>
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
                            <?= lang("Icons.iconFormKnowledge") ?> <?= lang("Form.formShift") ?>
                        </label>
                        </div>
                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <label for="" class="col-form-label col-form-label-sm">
                                <?= lang("Icons.iconFormShift") ?> <?= lang("Form.formName") ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php foreach($items as $key => $value): ?>
            <div class="row bottom-border">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label for="" class="col-form-label col-form-label-sm text-bold">
                        <?= $key ?> <?= $value["day"] ?? "" ?>
                    </label>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <?php if(is_array($value["items"])): $c = 1; foreach($value["items"] as $k => $dayItem):  ?>
                        <div class="row <?= count($value["items"]) > $c ? 'bottom-border-darker' : '' ?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12"><?= $dayItem["name"] ?? "" ?><br><?= $dayItem["time"] ?? "" ?></div>
                        <?php if(isset($dayItem["items"]) && is_array($dayItem["items"])): foreach($dayItem["items"] as $i => $item): ?>
                        <div class="col-lg-5 col-md-6 col-sm-12 col-12"></div>
                        <div class="col-lg-7 col-md-6 col-sm-12 col-12"><b><?= $item["name"] ?? "-" ?></b> (<?= $item["email"] ?? "-" ?>)</div>
                        <?php endforeach; endif; ?>
                        </div>
                    <?php $c++; endforeach; endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
        $(function(){
            $('#history-month-selector').on('change', function(e){
                $('#schedule-month-selector').submit();
            });
        });
    </script>