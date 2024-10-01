<script type="text/javascript">
	(function($) {
		"use strict";

		init_selectpicker();

		appValidateForm($("body").find('#add_edit_work_flow'), {
			'workflow_name': 'required',
			'sla_id': 'required',
			'kpi_id': 'required',
		}); 

	})(jQuery);
</script>