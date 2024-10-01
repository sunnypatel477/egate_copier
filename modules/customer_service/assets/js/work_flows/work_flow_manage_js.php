
<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={};
		var work_flow_table = $('.table-work_flow_table');
		initDataTable(work_flow_table, admin_url+'customer_service/work_flow_table',[0],[0], InvoiceServerParams, [0 ,'desc']);

		$('#date_add').on('change', function() {
			work_flow_table.DataTable().ajax.reload().columns.adjust().responsive.recalc();
		});

		var hidden_columns = [0];
		$('.table-work_flow_table').DataTable().columns(hidden_columns).visible(false, false);
	})(jQuery);

function add_workflow_modal(work_flow_id, slug) {
	"use strict";

	$("#modal_wrapper").load("<?php echo admin_url('customer_service/customer_service/workflow_modal'); ?>", {
		work_flow_id: work_flow_id,
		slug: slug,
	}, function() {
		if ($('.modal-backdrop.fade').hasClass('in')) {
			$('.modal-backdrop.fade').remove();
		}
		if ($('#appointmentModal').is(':hidden')) {
			$('#appointmentModal').modal({
				show: true
			});
		}
	});

	init_selectpicker();
	$(".selectpicker").selectpicker('refresh');
}

</script>