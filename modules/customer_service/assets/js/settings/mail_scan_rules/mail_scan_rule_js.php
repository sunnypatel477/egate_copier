<script>
	$(function(){

		'use strict';
		var blocked_sender_params = {
			"blocked_sender_filter": "[name='blocked_sender']",
		};
		var blocked_subject_params = {
			"blocked_subject_filter": "[name='blocked_subject']",
		};
		var blocked_phrase_params = {
			"blocked_phrase_filter": "[name='blocked_phrase']",
		};
		var allowed_sender_params = {
			"allowed_sender_filter": "[name='allowed_sender']",
		};
		var allowed_subject_params = {
			"allowed_subject_filter": "[name='allowed_subject']",
		};
		var allowed_phrase_params = {
			"allowed_phrase_filter": "[name='allowed_phrase']",
		};
		
		var blocked_sender_table = $('table.table-blocked_sender_table');
		var blocked_subject_table = $('table.table-blocked_subject_table');
		var blocked_phrase_table = $('table.table-blocked_phrase_table');
		var allowed_sender_table = $('table.table-allowed_sender_table');
		var allowed_subject_table = $('table.table-allowed_subject_table');
		var allowed_phrase_table = $('table.table-allowed_phrase_table');
		
		var _table_api = initDataTable(blocked_sender_table, admin_url+'customer_service/blocked_sender_table', [0], [0], blocked_sender_params);
		var _table_api = initDataTable(blocked_subject_table, admin_url+'customer_service/blocked_sender_table', [0], [0], blocked_subject_params);
		var _table_api = initDataTable(blocked_phrase_table, admin_url+'customer_service/blocked_sender_table', [0], [0], blocked_phrase_params);
		var _table_api = initDataTable(allowed_sender_table, admin_url+'customer_service/blocked_sender_table', [0], [0], allowed_sender_params);
		var _table_api = initDataTable(allowed_subject_table, admin_url+'customer_service/blocked_sender_table', [0], [0], allowed_subject_params);
		var _table_api = initDataTable(allowed_phrase_table, admin_url+'customer_service/blocked_sender_table', [0], [0], allowed_phrase_params);

		var hidden_columns = [0,1,2,6];
		$('.table-blocked_sender_table').DataTable().columns(hidden_columns).visible(false, false);
		$('.table-blocked_subject_table').DataTable().columns(hidden_columns).visible(false, false);
		$('.table-blocked_phrase_table').DataTable().columns(hidden_columns).visible(false, false);
		$('.table-allowed_sender_table').DataTable().columns(hidden_columns).visible(false, false);
		$('.table-allowed_subject_table').DataTable().columns(hidden_columns).visible(false, false);
		$('.table-allowed_phrase_table').DataTable().columns(hidden_columns).visible(false, false);
		
		appValidateForm($('form'),{value:'required',type:'required', rel_type:'required'},manage_spam_filters);

		$('#spam_filter').on('hidden.bs.modal', function(event) {
			$('#spam_filter select').selectpicker('val','');
			$('#spam_filter textarea').val('');
			$('#spam_filter #spam_filter_additional').html('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
			$('.allow-edit-title').removeClass('hide');
			$('.allow-add-title').removeClass('hide');
			
		});
	});

	function manage_spam_filters(form) {
		'use strict';

		var original_type = $('input[name="original_type"]').val();

		$('input[name="original_type"]').remove();
		var data = $(form).serialize();
		var url = form.action;
		$.post(url, data).done(function(response) {

			response = JSON.parse(response);

			if (response.success) {
				var type = $('select[name="type"]').selectpicker('val');
				$('.table-blocked_'+type+'_table').DataTable().ajax.reload();
				$('.table-allowed_'+type+'_table').DataTable().ajax.reload();

				if(type != original_type){
					$('.table-'+original_type).DataTable().ajax.reload();
				}
				alert_float('success', response.message);
			}

			$('a[href="#'+type+'"]').click();

			$(form).trigger('reinitialize.areYouSure');
			$('#spam_filter').modal('hide');

		});
		return false;
	}

	function new_mail_scan_rule(){
		'use strict';

		$('#spam_filter').modal('show');
		$('.edit-title').addClass('hide');

		/*Set the default select type selected by the active tab*/
		var activeTab = $('#filters_types').find('li.active a');
		var activeType = activeTab.attr('href');
		var type, rel_type;
		activeType = activeType.substr(1);
		if(activeType == 'blocked_sender'){
			type = 'sender';
			rel_type = 'blocked';

			$('.edit-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');

		}else if(activeType == 'blocked_subject'){
			type = 'subject';
			rel_type = 'blocked';

			$('.edit-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
		}else if(activeType == 'blocked_phrase'){
			type = 'phrase';
			rel_type = 'blocked';

			$('.edit-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
		}else if(activeType == 'allowed_sender'){
			type = 'sender';
			rel_type = 'allowed';

			$('.add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}else if(activeType == 'allowed_subject'){
			type = 'subject';
			rel_type = 'allowed';
			$('.add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}else if(activeType == 'allowed_phrase'){
			type = 'phrase';
			rel_type = 'allowed';
			$('.add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}

		if(type) {
			$('select[name="type"]').selectpicker('val', type);
		}
		if(rel_type) {
			$('select[name="rel_type"]').selectpicker('val', rel_type);
		}
		
	}

	function edit_spam_filter(invoker,id){
		'use strict';

		var type = $(invoker).data('type');
		var value = $(invoker).data('value');
		var rel_type = $(invoker).data('rel_type');
		$('#spam_filter_additional').append(hidden_input('id',id));
		$('#spam_filter select[name="type"]').selectpicker('val',type);
		$('#spam_filter select[name="rel_type"]').selectpicker('val',rel_type);
		$('#spam_filter textarea').val(value);
		$('#spam_filter').modal('show');
		$('.add-title').addClass('hide');

		var activeTab = $('#filters_types').find('li.active a');
		var activeType = activeTab.attr('href');
		var type, rel_type;
		activeType = activeType.substr(1);
		if(activeType == 'blocked_sender'){
			$('.add-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');

		}else if(activeType == 'blocked_subject'){
			$('.add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
			$('.allow-add-title').addClass('hide');

		}else if(activeType == 'blocked_phrase'){
			$('.add-title').addClass('hide');
			$('.allow-edit-title').addClass('hide');
			$('.allow-add-title').addClass('hide');

		}else if(activeType == 'allowed_sender'){
			$('.add-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}else if(activeType == 'allowed_subject'){
			$('.add-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}else if(activeType == 'allowed_phrase'){
			$('.add-title').addClass('hide');
			$('.allow-add-title').addClass('hide');
			$('.edit-title').addClass('hide');

		}
	}

</script>