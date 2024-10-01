<script type="text/javascript">
	$(function(){
		'use strict';
		
		var kpi_table = $('table.table-kpi_table');
		var _table_api = initDataTable(kpi_table, admin_url+'customer_service/kpi_table', [0], [0]);
		var hidden_columns = [0,12];
		$('.table-kpi_table').DataTable().columns(hidden_columns).visible(false, false);
		appValidateForm($('form'),{first_response_time:'required',first_response_time_measure:'required', average_resolution_time:'required', average_resolution_time_measure:'required', average_handle_time:'required', average_handle_time_measure:'required', number_of_tickets:'required', number_of_resolved_tickets:'required', number_of_tickets_by_medium:'required', escalation_rate:'required', customer_satisfaction_score:'required', code:'required', name:'required'},manage_spam_filters);

		$('#spam_filter').on('hidden.bs.modal', function(event) {
			$('#spam_filter select').selectpicker('val','');
			$('#spam_filter #spam_filter_additional').html('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
			
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
				$('.table-kpi_table').DataTable().ajax.reload();
				alert_float('success', response.message);
			}

			$(form).trigger('reinitialize.areYouSure');
			$('#spam_filter').modal('hide');

		});
		return false;
	}

	function new_kpi(){
		'use strict';

		$('#spam_filter').modal('show');
		$('.edit-title').addClass('hide');

		$.post(admin_url + 'customer_service/get_prefix_code/kpi_code').done(function(response){
			response = JSON.parse(response);

			$('input[name="code"]').val(response.prefix_code);
			$('input[name="first_response_time"]').val(5);
			$('select[name="first_response_time_measure"]').val('minutes').change();
			$('input[name="average_resolution_time"]').val(1);
			$('select[name="average_resolution_time_measure"]').val('hours').change();
			$('input[name="average_handle_time"]').val(4);
			$('select[name="average_handle_time_measure"]').val('hours').change();
			$('input[name="number_of_tickets"]').val(10);
			$('input[name="number_of_resolved_tickets"]').val(10);
			$('input[name="number_of_tickets_by_medium"]').val(10);
			$('input[name="escalation_rate"]').val(20);
			$('input[name="customer_satisfaction_score"]').val(70);

			init_selectpicker();
			$(".selectpicker").selectpicker('refresh');

		});
	}

	function edit_kpi(invoker,id){
		'use strict';

		var code = $(invoker).data('code');
		var name = $(invoker).data('name');
		var first_response_time = $(invoker).data('first_response_time');
		var first_response_time_measure = $(invoker).data('first_response_time_measure');
		var average_resolution_time = $(invoker).data('average_resolution_time');
		var average_resolution_time_measure = $(invoker).data('average_resolution_time_measure');
		var average_handle_time = $(invoker).data('average_handle_time');
		var average_handle_time_measure = $(invoker).data('average_handle_time_measure');
		var number_of_tickets = $(invoker).data('number_of_tickets');
		var number_of_resolved_tickets = $(invoker).data('number_of_resolved_tickets');
		var number_of_tickets_by_medium = $(invoker).data('number_of_tickets_by_medium');
		var escalation_rate = $(invoker).data('escalation_rate');
		var customer_satisfaction_score = $(invoker).data('customer_satisfaction_score');

		$('#spam_filter_additional').append(hidden_input('id',id));
		$('#spam_filter input[name="code"]').val(code);
		$('#spam_filter input[name="name"]').val(name);
		$('#spam_filter input[name="first_response_time"]').val(first_response_time);
		$('#spam_filter select[name="first_response_time_measure"]').selectpicker('val',first_response_time_measure);
		$('#spam_filter input[name="average_resolution_time"]').val(average_resolution_time);
		$('#spam_filter select[name="average_resolution_time_measure"]').selectpicker('val',average_resolution_time_measure);
		$('#spam_filter input[name="average_handle_time"]').val(average_handle_time);
		$('#spam_filter select[name="average_handle_time_measure"]').selectpicker('val',average_handle_time_measure);
		$('#spam_filter input[name="number_of_tickets"]').val(number_of_tickets);
		$('#spam_filter input[name="number_of_resolved_tickets"]').val(number_of_resolved_tickets);
		$('#spam_filter input[name="number_of_tickets_by_medium"]').val(number_of_tickets_by_medium);
		$('#spam_filter input[name="escalation_rate"]').val(escalation_rate);
		$('#spam_filter input[name="customer_satisfaction_score"]').val(customer_satisfaction_score);

		$('#spam_filter').modal('show');
		$('.add-title').addClass('hide');
	}
</script>