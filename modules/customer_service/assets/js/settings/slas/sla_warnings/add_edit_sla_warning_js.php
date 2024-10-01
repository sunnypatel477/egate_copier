<script>
	(function($) {
		"use strict";
		
		init_selectpicker();
		$(".selectpicker").selectpicker('refresh');

		appValidateForm($("body").find('#add_edit_sla_warning'), {
			'level': 'required',
			'action': 'required',
			'order_number': 'required',
		}); 


		$('select[name="action"]').on('change', function() {
			"use strict";  

			var action =$(this).val();

			if(action == 'trigger_an_email'){
				$('.agent_manager_hide').removeClass('hide');
				$('.action_value_hide').addClass('hide');

			}else if(action == 'increase_the_priority'){
				$('.action_value_hide').removeClass('hide');
				$('.agent_manager_hide').addClass('hide');

			}
		});

	})(jQuery); 

</script>