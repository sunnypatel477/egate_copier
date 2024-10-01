function new_required_document(){
    "use strict";
    $('#required_document').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    
    $('#required_document input[name="document_type"]').val('');
    $('#required_document input[name="expiration_date"]').val('');
    $('#additional_required_document').html('');
}
function edit_required_document(invoker,id){
    "use strict";
    $('#additional_required_document').append(hidden_input('id',id));
    $('#required_document input[name="document_type"]').val($(invoker).data('name'));
    $('#required_document input[name="expiration_date"]').val($(invoker).data('date'));

    $('#required_document').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}
