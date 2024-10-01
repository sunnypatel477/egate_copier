
<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={};
		var sla_table = $('.table-sla_table');
		initDataTable(sla_table, admin_url+'customer_service/sla_table',[0],[0], InvoiceServerParams, [0 ,'desc']);

		var hidden_columns = [0];
		$('.table-sla_table').DataTable().columns(hidden_columns).visible(false, false);

	})(jQuery);

/**
* add routing
* @param {[type]} staff_id 
* @param {[type]} role_id  
* @param {[type]} add_new  
*/
function add_sla(staff_id, role_id, add_new) {
	"use strict";

	$("#modal_wrapper").load("<?php echo admin_url('customer_service/customer_service/sla_modal'); ?>", {
		slug: 'add',
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