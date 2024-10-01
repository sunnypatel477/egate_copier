
<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={
			"service_level_agreement_id": "[name='service_level_agreement_id']",
		};
		var sla_warning_table = $('.table-sla_warning_table');
		initDataTable(sla_warning_table, admin_url+'customer_service/sla_warning_table',[0],[0], InvoiceServerParams, [1 ,'asc']);

		$('#date_add').on('change', function() {
			sla_warning_table.DataTable().ajax.reload().columns.adjust().responsive.recalc();
		});

		var hidden_columns = [0,1];
		$('.table-sla_warning_table').DataTable().columns(hidden_columns).visible(false, false);

	})(jQuery); 

	function sla_warning_modal(service_level_agreement_id, sla_warning_id, type) {
		"use strict";

		$("#modal_wrapper").load("<?php echo admin_url('customer_service/customer_service/sla_warning_modal'); ?>", {
			service_level_agreement_id: service_level_agreement_id,
			sla_warning_id: sla_warning_id,
			type: type
		}, function() {

			$("body").find('#appointmentModal').modal({ show: true, backdrop: 'static' });
		});

		init_selectpicker();
		$(".selectpicker").selectpicker('refresh');

	}

</script>