<script>
	$(function(){

		'use strict';

		var email_template_params = {
		};
		
		var email_template_table = $('table.table-email_template_table');
		var _table_api = initDataTable(email_template_table, admin_url+'customer_service/email_template_table', [0], [0], email_template_params);

		var hidden_columns = [0,1,4];
		$('.table-email_template_table').DataTable().columns(hidden_columns).visible(false, false);
		
		appValidateForm($('form'),{value:'required',type:'required', rel_type:'required'});
	});

</script>