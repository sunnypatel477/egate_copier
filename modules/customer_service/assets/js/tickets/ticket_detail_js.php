<script type="text/javascript">
	var timer = null;
	var id = document.getElementById("drawflow");
	const editor = new Drawflow(id);
	(function($) {
		"use strict";

		editor.reroute = true;
		editor.start();
		<?php if(isset($workflow) && $workflow->workflow != ''){ ?> 
			const dataToImport = <?php echo json_decode($workflow->workflow); ?>;
			editor.import(dataToImport);
		<?php } ?>
		
		<?php if(!isset($is_edit)){ ?>
			editor.editor_mode='fixed';
		<?php } ?>

		var elements = document.getElementsByClassName('drag-drawflow');
		for (var i = 0; i < elements.length; i++) {
			elements[i].addEventListener('touchend', drop, false);
			elements[i].addEventListener('touchmove', positionMobile, false);
			elements[i].addEventListener('touchstart', drag, false );
		}

		var mobile_item_selec = '';
		var mobile_last_move = null;
		$( document ).ready(function() {
			$('input[type=radio][name^=lead_data_from]').change();
			$('input[type=radio][name^=complete_action]').change();
			$('select[name^=action]').change();
			$('select[name^=track]').change();
		});

	})(jQuery);

	(function($) {
		"use strict";

		if($('#dropzoneDragArea').length > 0){
			expenseDropzone = new Dropzone(".post_reply", appCreateDropzoneOptions({
				autoProcessQueue: false,
				clickable: '#dropzoneDragArea',
				previewsContainer: '.dropzone-previews',
				addRemoveLinks: true,
				maxFiles: 10,

				success:function(file,response){
					response = JSON.parse(response);
					if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

						location.reload();
					}else{
						expenseDropzone.processQueue();
					}
				},

			}));
		}

		appValidateForm($("body").find('.post_reply'), {
			'to' : 'required',
			'response' : 'required',
		},postReplySubmitHandler);

		appValidateForm($("body").find('.post_internal_reply'), {
			'note_title' : 'required',
			'note_details' : 'required',
		});

		appValidateForm($("body").find('.department_transfer'), {
			'comment' : 'required',
			'department_id' : 'required',
		});
		appValidateForm($("body").find('.reassign_ticket'), {
			'comment' : 'required',
			'department_id' : 'required',
		});


	})(jQuery); 



	Dropzone.options.expenseForm = false;
	var expenseDropzone;

	function postReplySubmitHandler(form){
		"use strict";

		var data ={};

		data.formdata = $( form ).serializeArray();
		data.ticket_id = <?php echo new_html_entity_decode($ticket->id); ?>;

		$.post(form.action, data).done(function(response) {

			var response = JSON.parse(response);

			if (response.post_id) {
				if(typeof(expenseDropzone) !== 'undefined'){
					if (expenseDropzone.getQueuedFiles().length > 0) {
						expenseDropzone.options.url = admin_url + 'customer_service/add_post_reply_attachment/' + response.post_id + '/' + response.ticket_id;
						expenseDropzone.processQueue();
					} else {
						location.reload();

					}
				} else {
					window.location.assign(response.url);
				}
			} else {
				window.location.assign(response.url);
			}
		});
		return false;
	}


	function delete_company_attachment(wrapper, id) {
		"use strict";

		if (confirm_delete()) {
			$.get(admin_url + 'recruitment/delete_company_file/' + id, function (response) {
				if (response.success == true) {
					$(wrapper).parents('.dz-preview').remove();

					var totalAttachmentsIndicator = $('.dz-preview'+id);
					var totalAttachments = totalAttachmentsIndicator.text().trim();

					if(totalAttachments == 1) {
						totalAttachmentsIndicator.remove();
					} else {
						totalAttachmentsIndicator.text(totalAttachments-1);
					}
					alert_float('success', "<?php echo _l('delete_company_file_success') ?>");

				} else {
					alert_float('danger', "<?php echo _l('delete_company_file_false') ?>");
				}
			}, 'json');
		}
		return false;
	}

	function delete_ticket_history(wrapper, id, type) {
		"use strict"; 
		if (confirm_delete()) {
			requestGetJSON('customer_service/delete_ticket_history/' + id +'/'+type).done(function(response) {
				if (response.success === true || response.success == 'true') { $(wrapper).parents('.feed-item').remove(); }
			}).fail(function(data) {
				alert_float('danger', data.responseText);
			});
		}
	}

</script>