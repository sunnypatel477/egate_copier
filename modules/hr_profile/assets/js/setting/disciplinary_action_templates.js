(function(){
    'use strict';

    appValidateForm($('#disciplinary_action form'), {
        'discipline_reason_name': 'required',
        'discipline_category': 'required',
        'discipline_reason_remarks': 'required',
        'Score': 'required',
        'company_rules': 'required',
        'content_template': 'required',
    })

    var ContractsServerParams = {
        "memberid": "[name='memberid']",
        "member_view": "[name='member_view']",
    };

    var table_dependent_person = $('.table-table_dependent_person');
    initDataTable(table_dependent_person, admin_url+'hr_profile/table_dependent_person', [0], [0], ContractsServerParams, [0, 'desc']);

    //hide first column
    var hidden_columns = [0,1,3,9];
    $('.table-table_dependent_person').DataTable().columns(hidden_columns).visible(false, false);

})(jQuery);

function new_disciplinary_action_type(){
    'use strict';
    $('#disciplinary_action').modal('show');
    $('#dependent_action_id').html('');
    tinyMCE.activeEditor.setContent("");
    
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
}

function edit_disciplinary_action_type(invoker,id){
    'use strict';
    tinyMCE.activeEditor.setContent("");

    requestGetJSON('hr_profile/get_disciplinary_action/' + id).done(function(response) {
        console.log("response", response.disciplinary_action_type.discipline_reason_name);
    
        $('#dependent_action_id').append(hidden_input('id', response.disciplinary_action_type.id));
        $('#disciplinary_action input[name="discipline_reason_name"]').val(response.disciplinary_action_type.discipline_reason_name);
        $('#disciplinary_action input[name="discipline_category"]').val(response.disciplinary_action_type.discipline_category);
        $('#disciplinary_action input[name="discipline_reason_remarks"]').val(response.disciplinary_action_type.discipline_reason_remarks);
        $('#disciplinary_action input[name="Score"]').val(response.disciplinary_action_type.score);

        $("select[name='discipline_category']").selectpicker('destroy');
		$("select[name='discipline_category']").val(response.disciplinary_action_type.discipline_category);
		$("select[name='discipline_category']").selectpicker('refresh');
        
        tinyMCE.get('company_rules').setContent(response.disciplinary_action_type.company_rules);
        tinyMCE.get('content_template').setContent(response.disciplinary_action_type.content_template);
        
        $('#disciplinary_action').modal('show');
        $('.add-title').addClass('hide');
        $('.edit-title').removeClass('hide');
    });
}

$("#disciplinary_action").on("hidden.bs.modal", function() {
    $("#discipline_category").val('').selectpicker('refresh');
    $("#discipline_reason_name").val('');
    $("#discipline_reason_remarks").val('');
    $("#Score").val('');
    tinyMCE.get("company_rules").setContent('');
    tinyMCE.get("content_template").setContent('');
  });