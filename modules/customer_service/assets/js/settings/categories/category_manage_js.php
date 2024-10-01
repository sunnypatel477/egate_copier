
<script>

	(function($) {
		"use strict";

		var InvoiceServerParams={
		};
		var category_table = $('.table-category_table');
		initDataTable(category_table, admin_url+'customer_service/category_table',[0],[0], InvoiceServerParams, [1 ,'asc']);

		$('#date_add').on('change', function() {
			category_table.DataTable().ajax.reload().columns.adjust().responsive.recalc();
		});

		var hidden_columns = [0];
		$('.table-category_table').DataTable().columns(hidden_columns).visible(false, false);

	})(jQuery); 

	function category_modal(category_id, type) {
		"use strict";

		$("#modal_wrapper").load("<?php echo admin_url('customer_service/customer_service/category_modal'); ?>", {
			category_id: category_id,
			type: type
		}, function() {

			$("body").find('#appointmentModal').modal({ show: true, backdrop: 'static' });
		});

		init_selectpicker();
		$(".selectpicker").selectpicker('refresh');

	}

	$("body").on('change', 'input[name="category_default_onoffswitch"]', function (event, state) {
		"use strict";
		
		setTimeout(function () {

			category_table.DataTable().ajax.reload(), 50
		}, 50);
	});

</script>