<script type="text/javascript">
	function submit_support_term_condition(event){
		"use strict"; 
		
		var myContent = tinymce.get("cs_support_term_condition").getContent();
		var data = {};
		data.myContent = myContent;

		$.post(admin_url+'customer_service/update_support_term_condition', data).done(function(response){
			response = JSON.parse(response);
			if(response.status == true || response.status == 'true'){
				alert_float('success', response.message);
			}

		}).fail(function(error) {

		});

	}
</script>