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
            <?= form_open('', ['class' => 'row skill-level-selector']) ?>
                <label for="name" class="col-12 col-form-label col-form-label-sm">
                    <?= lang("Icons.iconFormKnowledge") ?> <?= lang("Form.formKnowledge") ?>
                </label>
                <div class="col-lg-4 col-md-5 form-group">
                    <select name="knowledge" class="form-control form-control-sm input-style" id="skill-knowledge-name">
                        <option value=""><?= lang("Site.siteChoose") ?></option>
                        <?php if(isset($knowledge)): foreach($knowledge as $key => $value): ?>
                            <option value="<?= $value->id?>" <?= $value->available ? "" : "hidden" ?>><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-lg-4 col-md-5 form-group">
                    <select name="level" class="form-control form-control-sm input-style" id="skill-knowledge-level">
                        <option value=""><?= lang("Site.siteChoose") ?></option>
                        <?php if(isset($knowledgeLevels)): foreach($knowledgeLevels as $key => $value): ?>
                            <option value="<?= $value->id?>"><?= $value->name ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                    <div class="col-lg-1 col-md-2 form-group">
                        <button type="button" id="add-skill-btn" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Form.formAdd') ?>"><i class="bi bi-plus"></i></button>
                    </div>
            <?= form_close() ?>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormKnowledge") ?> <?= lang("Form.formKnowledge") ?>
                    </label>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <label for="" class="col-form-label col-form-label-sm">
                        <?= lang("Icons.iconFormLevel") ?> <?= lang("Form.formLevel") ?>
                    </label>
                </div>
            </div>
            <div id="skills-wrapper">
                <?php if(isset($knowledge)): foreach($knowledge as $key => $value): ?>
                    <?= view_cell('\App\Controllers\Skills::showItem', ['class' => ($value->available ? 'd-none' : '').' knowledge-'.$value->id, 'id' => $value->id, 'name' => $value->name, 'knowledgeValue' => $value->value ?? null, 'knowledgeLevels' => $knowledgeLevels]) ?>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
<script>    
    $('form#skill-level-selector').find('input,select,textarea').on('input', function(e){
        $(this).removeClass('is-invalid');
    });

    $('#add-skill-btn').on('click', function(){
        let knowledge = $('#skill-knowledge-name').val();
        let data = new FormData($('form.skill-level-selector').get(0));
        data.append('insert', true);
        saveUserKnowledge(data, knowledge);
    });

    $(document).on('change', '.skill-knowledge-name, .skill-knowledge-level', function(){
        let data = new FormData($(this).closest('form.skill-item-wrapper').get(0));
        saveUserKnowledge(data, false);
    });
    function saveUserKnowledge(data, knowledge = null){
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: '/skills/save',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(data) {
                $('#result-messages').html(data.message);
                fadeResultmessages();
                if(data.status){
                    $('form.skill-level-selector').get(0).reset();
                    if(knowledge){
                        $('#skill-knowledge-name').find('option[value="'+knowledge+'"]').prop('hidden', true);
                        $('.knowledge-'+knowledge).removeClass('d-none');
                        if(data.level){
                            $('.knowledge-'+knowledge).find('.skill-knowledge-level option[value="'+data.level+'"]').prop('selected', true);
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
    }
</script>