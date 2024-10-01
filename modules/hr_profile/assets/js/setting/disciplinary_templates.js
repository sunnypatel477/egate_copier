    appValidateForm($('#add_disciplinary_type'), {
        name: 'required',
        content: 'required',
    });


    appValidateForm($('#add_penalty_type'), {
        name: 'required',
        content: 'required',
        point: 'required',
    });

    function new_disciplinary_type(){
        'use strict';
        $('#disciplinary_type').modal('show');
        $('.edit-title').addClass('hide');
        $('.add-title').removeClass('hide');
        $('#disciplinary_type input[name="name_disciplinarytype"]').val('');
        $('#additional_disciplinary_type').html('');
        tinyMCE.activeEditor.setContent("");
    
    }
    function edit_disciplinary_type(invoker,id){
        'use strict';
    
        $('#additional_disciplinary_type').html('');
        $('#additional_disciplinary_type').append(hidden_input('id',id));
        tinyMCE.activeEditor.setContent("");
    
        requestGetJSON('hr_profile/get_disciplinary_template/' + id).done(function (response) {
            $('#disciplinary_type input[name="name"]').val(response.disciplinary_type.name);
            tinyMCE.get('content').setContent(response.disciplinary_type.content);
        });
    
    
        $('#disciplinary_type').modal('show');
        $('.add-title').addClass('hide');
        $('.edit-title').removeClass('hide');
    } 

function new_penalty_type(){
    'use strict';
    $('#penalty_type').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#penalty_type input[name="name"]').val('');
    $('#penalty_type input[name="point"]').val('');
    $('#additional_penalty_type').html('');
    tinyMCE.activeEditor.setContent("");

}
function edit_penalty_type(invoker,id){
    'use strict';

    $('#additional_penalty_type').html('');
    $('#additional_penalty_type').append(hidden_input('id',id));
    tinyMCE.activeEditor.setContent("");

    requestGetJSON('hr_profile/get_penalty_template/' + id).done(function (response) {
        console.log("response",response);
        $('#penalty_type input[name="name"]').val(response.penalty_type.name);
        $('#penalty_type input[name="point"]').val(response.penalty_type.point);
        tinyMCE.activeEditor.setContent(response.penalty_type.content);
    });


    $('#penalty_type').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
} 
