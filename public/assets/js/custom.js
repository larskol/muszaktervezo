$(function(){
    $('[data-toggle="tooltip"]').tooltip();

    $(function () {
        $('[data-toggle="popover"]').popover({html: true, content: function(){
            return $('#popover_content_wrapper').html();
         }});
      })
    
    $('.dropdown-btn').on('click', function(){
        let item = $(this).next('.dropdown-container');
        if(!$(this).hasClass("active")){
            $(this).find('i.nav-icon-right').removeClass('bi-caret-down');
            $(this).find('i.nav-icon-right').addClass('bi-caret-up');
        }else{
            $(this).find('i.nav-icon-right').removeClass('bi-caret-up');
            $(this).find('i.nav-icon-right').addClass('bi-caret-down');
        }
        $(this).toggleClass('active');
        $(item).toggle('slow');
    });
    
    $('.nav-menu-item').on('click', function(){
        $('.nav-menu-item').removeClass('active');
        $(this).addClass('active');
    });

    $('select#department-switcher').on("change", function(e){
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: '/department-switch',
            data: new FormData($('form#department-switch').get(0)),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                if(data.status){
                    location.reload();
                }
            },
            error: function(data){
                
            }
        });
    });
});

function postFormData(url, formId, buttonId, redirectUrl, resetViewButton){
    $('form'+formId).on('submit', function(e){
        e.preventDefault();
        showSpinnerIcon(buttonId);
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: url,
            data: new FormData($('form'+formId).get(0)),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                showSpinnerIcon(buttonId, false);
                $('#result-messages').html(data.message);
                fadeResultmessages();
                if(data.status){
                    if(redirectUrl){
                        window.location = redirectUrl;
                    }else{
                        $('form'+formId).get(0).reset();
                        $('form'+formId).find('input.is-invalid,select.is-invalid,textarea.is-invalid').removeClass('is-invalid');
                        $(`#dep-message`).html('');
                        if(resetViewButton){
                            if($('#password_input').prop('type') == 'text'){
                                $(resetViewButton).trigger('click');
                            }
                        }
                    }
                }
                if(data.errors){
                    $(`#dep-message`).html('');
                    for (const key in data.errors) {
                        if (Object.hasOwnProperty.call(data.errors, key)) {
                            const element = data.errors[key];
                            $(`input[name="${key}"],select[name="${key}"],textarea[name="${key}"]`).addClass('is-invalid');
                            $(`input[name="${key}"],select[name="${key}"],textarea[name="${key}"]`).next('div.invalid-feedback').html(element);

                            if(key == "departments.*"){
                                $(`#dep-message`).html(element);
                            }
                        }
                    }
                }
                if(data.customError){
                    $('#result-messages').html(data.customError);
                }
            },
            error: function(data){
                showSpinnerIcon(buttonId, false);
            }
        });
    });
}

function postTableData(url, dataArray, buttonId, table, redirectUrl){
        showSpinnerIcon(buttonId);
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: url,
            data: JSON.stringify({items: dataArray }),
            dataType: 'json',
            contentType: 'application/json',
            processData: false,
            success: function(data) {
                $('#result-messages').html(data.message);
                fadeResultmessages();
                if(data.status){
                    if(redirectUrl){
                        window.location = redirectUrl;
                    }
                    table.rows( '.selected' )
                    .remove()
                    .draw();
                    dtEditButton(table);
                    dtDeleteButton(table);
                }
            },
            error: function(data){
                showSpinnerIcon(buttonId, false);
            }
        });
}

function postUploadData(url, formId, buttonId, redirectUrl){
    $('form'+formId).on('submit', function(e){
        e.preventDefault();
        showSpinnerIcon(buttonId);
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: url,
            data: new FormData($('form'+formId).get(0)),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                showSpinnerIcon(buttonId, false);

                $('#success-messages').html(data.successMessage);
                $('#error-messages').html(data.errorMessage);
                if(data.status){
                    if(redirectUrl){
                        window.location = redirectUrl;
                    }else{                        
                        $('form'+formId).get(0).reset();                        
                    }
                }
                if(data.errors){
                    for (const key in data.errors) {
                        if (Object.hasOwnProperty.call(data.errors, key)) {
                            const element = data.errors[key];
                            $(`input[name="${key}"]`).addClass('is-invalid');
                            $(`input[name="${key}"]`).next('div.invalid-feedback').html(element);
                        }
                    }
                }
            },
            error: function(data){
                showSpinnerIcon(buttonId, false);
            }
        });
    })
}

function showSpinnerIcon(id, showSpinner = true, icon = '<i class="bi bi-check-circle"></i>'){
    if(showSpinner){
        $(id).find('.btn-icon').html('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>');
        $(id).attr('disabled', true);
    }else{
        $(id).find('.btn-icon').html(icon);
        $(id).removeAttr('disabled');

    }
}

function dtEditButton(table){
    if(table.rows('.selected').data().length == 1){
        $('.dt-edit-btn').prop('disabled', false);
    }else{
        $('.dt-edit-btn').prop('disabled', true);
    }
}

function dtDeleteButton(table){
    if(table.rows('.selected').data().length >= 1){
        $('.dt-delete-btn').prop('disabled', false);
        $('.dt-delete-confirm-btn').prop('disabled', false);
    }else{
        $('.dt-delete-btn').prop('disabled', true);
        $('.dt-delete-confirm-btn').prop('disabled', true);
    }
}

function fadeResultmessages(){
    $("#result-messages").fadeTo(10000, 500).slideUp(500, function(){
        $("#result-messages").slideUp(500);
    });
}

function changeInputType(item, id, fromType = 'password', toType = 'text'){
    let type = $(id).prop('type');
    if(type == fromType){
        $(id).prop('type', toType);
        $(item).html($(item).data("switch-back"));
    }else{
        $(id).prop('type', fromType);
        $(item).html($(item).data("switch"));

    }
}

function removeSkillItem(data, e){
    $.ajax({
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        type: 'POST',
        url: '/skills/delete',
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data) {
            $('#result-messages').html(data.message);
            fadeResultmessages();
            if(data.status){
                $('[data-toggle="tooltip"]').tooltip('hide');
                $('#skills-wrapper .knowledge-'+data.itemId).addClass('d-none');
                $('#skill-knowledge-name option[value="'+data.itemId+'"]').prop("hidden", false);
            }
            if(data.errors){
                $('#result-messages').html(data.errors);
            }
        },
        error: function(data){
            
        }
    });
}

function removeUserRestrictionItem(e){
    let id = $(e).data('item-id');
    $.ajax({
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        type: 'POST',
        url: '/my-restrictions/delete',
        data: JSON.stringify({id: id}),
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data) {
            $('#result-messages').html(data.message);
            fadeResultmessages();
            if(data.status){
                $('[data-toggle="tooltip"]').tooltip('hide');
                $(e).closest('.user-restriction-item').remove();
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
}

function authUser(formId, buttonId){
    $('form'+formId).on('submit', function(e){
        e.preventDefault();
        showSpinnerIcon(buttonId);
        $.ajax({
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            type: 'POST',
            url: '/auth',
            data: new FormData($('form'+formId).get(0)),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data) {
                $('form'+formId).get(0).reset();
                showSpinnerIcon(buttonId, false);
                if(data.status){
                    if(data.redirectUrl){
                        window.location = data.redirectUrl;
                    }
                }else{
                    $('#result-messages').html(data.message);
                    fadeResultmessages();
                }
            },
            error: function(data){
                $('form'+formId).get(0).reset();
                showSpinnerIcon(buttonId, false);
            }
        });
    });
}

function getWeekOfTheYear(dateInput, whereToShow, textToAdd = ''){
    if(dateInput.val() != ''){
        let weekNumber = moment(dateInput.val()).isoWeek();
        if(!isNaN(weekNumber)){
            $(whereToShow).html(weekNumber+textToAdd);
        }else{
            $(whereToShow).html('-');
        }
    }else{
        $(whereToShow).html(textToAdd);
    }
}