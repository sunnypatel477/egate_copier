(function($) {
"use strict";
var SiScheduleSMSServerParams = {};
initDataTable('.table-si-lead-followup-schedule', admin_url+'si_lead_followup/table', undefined, undefined,
	SiScheduleSMSServerParams, [ 0, "desc" ]);

appValidateForm($("#si_lead_followup_schedule_form"), {
	name: 'required',
	status: 'required',
	sms_content: {'required':function () {
						return ($("input[name='send_sms']").is(":checked") ? true : false);
				   },
				  'maxlength':5000},
	email_template_custom: {'required':function () {
					return ($("input[name='send_email']").is(":checked") ? true : false);
			   },
	},		   			  
	schedule_days: 'required',
	schedule_hour: 'required',
},manage_lead_sms_send_form);

function manage_lead_sms_send_form()
{
	if(!$("input[name='send_sms']").is(":checked") && !$("input[name='send_email']").is(":checked")){
		alert(select_sms_or_email);
		return false;
	}
		
	tinymce.triggerSave();
	var ubtn = $('#si_lead_followup_send');
	var form = $('#si_lead_followup_schedule_form');
	var data = form.serialize();
	var url = form.action;
	ubtn.html($('#si_lead_followup_send_wrapper').data('wait-text'));
	ubtn.addClass('disabled');
	
	$.post(url,data).done(function(response){
		response=JSON.parse(response);
		if(response['success']){
			ubtn.removeClass('disabled');
			ubtn.html($('#si_lead_followup_send_wrapper').data('original-text'));
			alert_float('success', response['message']);
			$('.table-si-lead-followup-schedule').DataTable().ajax.reload();
		}else{
			ubtn.removeClass('disabled');
			ubtn.html($('#si_lead_followup_send_wrapper').data('original-text'));
			alert_float('danger', response['message']);
		}	
	}).fail(function(response){
		alert_float('danger', response.responseText);
		ubtn.removeClass('disabled');
		ubtn.html($('#si_lead_followup_send_wrapper').data('original-text'));
	});

}
$('input[name="filter_by"]').on('change', function() {
	var filter_by = $(this).val();
	$('.div_merge_field').hide();//hide all merge fields
	$('#div_merge_field_'+filter_by).show();//show only selected filter merge fields
});
$(document).ready(function() {
	$('.div_merge_field').hide();//hide all merge fields
	$('#div_merge_field_lead').show();//show only selected filter merge fields
});
$('#si_lead_followup_clear').on('click', function() {
	$('#status').selectpicker('val','').selectpicker('refresh');
	$('#source').selectpicker('val','').selectpicker('refresh');
	$('#div_dlt_template').find('input.form-control').val('');
	$('select,textarea,input').parents('.form-group').removeClass('has-error');
	$('p.text-danger').hide();
});
$(document).on('click','.si_lead_followup_schedule_delete',function(e){
	e.preventDefault();
	requestGet('si_lead_followup/schedule_delete/' + $(this).data('id')).done(function(response) {
		response = JSON.parse(response);
		if (response.success === true || response.success == 'true') { 
			alert_float('success', response.message);
			$('.table-si-lead-followup-schedule').DataTable().ajax.reload();
		}
		else{
			alert_float('warning', response.message); 
		}
	}).fail(function(data) {
		alert_float('danger', data.responseText);
	});
});

$(document).on('click','#si_lead_followup_btn_for_edit',function(e){
	appValidateForm($("#si_lead_followup_edit_schedule_form"), {
		name: 'required',
		status: 'required',
		sms_content: {'required':function () {
					return ($("#si_lead_followup_edit_schedule_form input[name='send_sms']").is(":checked") ? true : false);
			},
			'maxlength':5000},
		edit_email_template_custom: {'required':function () {
				return ($("#si_lead_followup_edit_schedule_form input[name='send_email']").is(":checked") ? true : false);
		},
		},
		schedule_days: 'required',
		schedule_hour: 'required',
	});
	
	tinymce.triggerSave();
	var ubtn = $('#si_lead_followup_btn_for_edit');
	var form = $('#si_lead_followup_edit_schedule_form');
	if (form.length && !form.validate().checkForm())
		return false;
	var data = form.serialize();
	var url = form.attr('action');
	ubtn.html($('#si_lead_followup_edit_send_wrapper').data('wait-text'));
	ubtn.addClass('disabled');
	$.post(url,data).done(function(response){
		response=JSON.parse(response);
		if(response['success']){
			ubtn.removeClass('disabled');
			ubtn.html($('#si_lead_followup_edit_send_wrapper').data('original-text'));
			alert_float('success', response['message']);
			$('#si_lead_followup_view_schedule_modal').modal('hide');
			$('.table-si-lead-followup-schedule').DataTable().ajax.reload();
		}else{
			ubtn.removeClass('disabled');
			ubtn.html($('#si_lead_followup_edit_send_wrapper').data('original-text'));
			alert_float('danger', response['message']);
		}	
	}).fail(function(response){
		alert_float('danger', response.responseText);
		ubtn.removeClass('disabled');
		ubtn.html($('#si_lead_followup_edit_send_wrapper').data('original-text'));
	});
});	
// Remove the tinymce email edit editor
 $("body").on("hidden.bs.modal", '#si_lead_followup_view_schedule_modal', function() {
	tinyMCE.remove('.tinymce-si-edit');
});
})(jQuery);	

function view_schedule_modal(id) {
	requestGetJSON('si_lead_followup/get_schedule_sms_by_id/' + id).done(function(response) {
		var schedule_modal = $('#si_lead_followup_view_schedule_modal');
		schedule_modal.find('#div_view_schedule_sms').html(response.html);
		schedule_modal.modal('show');
		$('#si_lead_followup_btn_for_edit').hide();
	});
}
function edit_schedule_modal(id) {

	requestGetJSON('si_lead_followup/get_schedule_sms_by_id/' + id +'/1').done(function(response) {
		var schedule_modal = $('#si_lead_followup_view_schedule_modal');
		schedule_modal.find('#div_view_schedule_sms').html(response.html);
		schedule_modal.modal('show');
		$('#si_lead_followup_btn_for_edit').show();
		init_selectpicker();
		init_datepicker();
		init_editor('.tinymce-si-edit', { 
			urlconverter_callback: merge_field_format_url, 
			'max-height': 100 
		});
		tinymce.triggerSave();
	});
}