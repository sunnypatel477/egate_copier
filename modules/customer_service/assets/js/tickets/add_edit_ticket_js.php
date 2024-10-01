<script type="text/javascript">
	$(function(){
		'use strict';

		appValidateForm($("body").find('#add_ticket'), {
			'client_id': 'required',
			'category_id': 'required',
			'issue_summary': 'required',
			'department_id': 'required',
			'assigned_id': 'required',
			'ticket_type': 'required',
			'sla_id': 'required',
		}); 

		$('select[name="client_id"]').on('change', function() {
			"use strict";  

			var client_id = $('select[name="client_id"]').val();
			$('select[name="item_id"]').html('');
			$('input[name="item_description"]').val('');
			
			if(client_id != '' && client_id != undefined){

				$.post(admin_url + 'customer_service/get_invoice_by_client/'+client_id).done(function(response){
					response = JSON.parse(response);

					$('select[name="invoice_id"]').html('');
					$('select[name="invoice_id"]').append(response.invoice_option);
					init_selectpicker();
					$(".selectpicker").selectpicker('refresh');
					
				});

			}else{
				$('select[name="invoice_id"]').html('');
				$('select[name="warranty_receipt_process_id"]').html('');
				$('select[name="item_id"]').html('');
				$('.invoice-item table.invoice-items-table.items tbody').html('');

				init_selectpicker();
				$(".selectpicker").selectpicker('refresh');
			}
		});


		$('select[name="category_id"]').on('change', function() {
			"use strict";  

			var category_id = $('select[name="category_id"]').val();

			if(category_id != '' && category_id != undefined){

				$.post(admin_url + 'customer_service/get_category_info/'+category_id).done(function(response){
					response = JSON.parse(response);

					$('select[name="priority_level"]').val(response.priority_level).selectpicker('refresh');
					$('select[name="department_id"]').val(response.department_id).selectpicker('refresh');
					$('select[name="sla_id"]').val(response.sla_id).selectpicker('refresh');

					$('select[name="invoice_id"]').append(response.invoice_option);
					init_selectpicker();
					$(".selectpicker").selectpicker('refresh');
					
				});

			}else{
				$('select[name="priority_level"]').val('').selectpicker('refresh');
				$('select[name="department_id"]').val('').selectpicker('refresh');
				$('select[name="sla_id"]').val('').selectpicker('refresh');

				init_selectpicker();
				$(".selectpicker").selectpicker('refresh');
			}

		});

		$('select[name="invoice_id"]').on('change', function() {
			"use strict";  

			var invoice_id = $('select[name="invoice_id"]').val();
			$('input[name="item_description"]').val('');

			if(invoice_id != '' && invoice_id != undefined){

				$.post(admin_url + 'customer_service/get_list_item_warranty_by_invoice/'+invoice_id).done(function(response){
					response = JSON.parse(response);

					$('select[name="item_id"]').html('');
					$('select[name="item_id"]').append(response.item_warranty_option);
					init_selectpicker();
					$(".selectpicker").selectpicker('refresh');
					
				});

			}else{
				$('select[name="item_id"]').html('');
				$('select[name="warranty_receipt_process_id"]').html('');
				$('.invoice-item table.invoice-items-table.items tbody').html('');

				init_selectpicker();
				$(".selectpicker").selectpicker('refresh');
			}

		});

		$('select[name="item_id"]').on('change', function() {
			"use strict";

			var item_description = $( "#item_id option:selected" ).text();
			$('input[name="item_description"]').val(item_description);

		});

	});
</script>