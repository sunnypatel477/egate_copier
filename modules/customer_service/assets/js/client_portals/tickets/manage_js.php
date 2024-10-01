<script type="text/javascript">
	function rating_modal(ticket_id) {
		"use strict";

		$('input[name="ticket_id"]').val(ticket_id);
		$.post(site_url + 'customer_service/customer_service_client/get_ticket_information/'+ticket_id).done(function(response){
			response = JSON.parse(response);

			$('.modal-title').html('');
			$('.modal-title').append(response.ticket_name);

			$('#rating_modal').modal('show');

		});
		
	}
</script>