<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={
			"activity_log_date": "[name='activity_log_date']",
		};
		var ticket_pipe_log_table = $('.table-ticket_pipe_log_table');

		initDataTable(ticket_pipe_log_table, admin_url+'customer_service/ticket_pipe_log_table',[0],[0], InvoiceServerParams, [1 ,'desc']);


		$('#activity_log_date').on('change', function() {
			ticket_pipe_log_table.DataTable().ajax.reload();
		});

	})(jQuery);
</script>