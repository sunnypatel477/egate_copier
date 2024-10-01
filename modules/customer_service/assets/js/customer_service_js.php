<script type="text/javascript">
	function customer_service_status_mark_as(status, task_id, type) {
		"use strict"; 
		
		var url = 'customer_service/customer_service_status_mark_as/' + status + '/' + task_id + '/' + type;
		var taskModalVisible = $('#task-modal').is(':visible');
		$("body").append('<div class="dt-loader"></div>');

		requestGetJSON(url).done(function (response) {
			$("body").find('.dt-loader').remove();
			if (response.success === true || response.success == 'true') {

				if(type == 'stage_status' || type == 'ticket_status'){
					location.reload();
				}
				var av_tasks_tables = ['.table-ticket_table'];
				$.each(av_tasks_tables, function (i, selector) {
					if ($.fn.DataTable.isDataTable(selector)) {
						$(selector).DataTable().ajax.reload(null, false);
					}
				});
				alert_float('success', response.message);
			}
		});
	}
</script>