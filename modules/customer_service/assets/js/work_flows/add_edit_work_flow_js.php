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

		$(document).on("keyup", "input[type=text]", function() { 
			clearTimeout(timer); 
			timer = setTimeout(workflow_input_change, 1000, $(this));
		});

		$(document).on("keyup", "input[type=number]", function() { 
			clearTimeout(timer); 
			timer = setTimeout(workflow_input_change, 1000, $(this));
		});

		$(document).on("change", "input.datetimepicker", function() { 
			clearTimeout(timer); 
			timer = setTimeout(workflow_input_change, 1000, $(this));
		});

		$(document).on("change", "input[type=time]", function() { 
			clearTimeout(timer); 
			timer = setTimeout(workflow_input_change, 1000, $(this));
		});

		$(document).on("change", "input[type=radio][name^=complete_action]", function() { 
			var parent = $(this).parents('.box');
			if(this.checked === true){
				parent.find('.div_complete_action_after').addClass('hide');
				parent.find('.div_complete_action_exact_time').addClass('hide');
				parent.find('.div_complete_action_exact_time_and_date').addClass('hide');

				parent.find('.div_complete_action_'+this.value).removeClass('hide');
			}
		});

		$(document).on("change", "input[type=radio][name^=lead_data_from]", function() { 
			var parent = $(this).parents('.box');
			if(this.checked === true){
				parent.find('.div_lead_data_from_segment').addClass('hide');
				parent.find('.div_lead_data_from_form').addClass('hide');

				parent.find('.div_lead_data_from_'+this.value).removeClass('hide');
			}

		});

		$(document).on("change", "select[name^=action]", function() {
			var parent = $(this).parents('.box');
			parent.find('.div_action_change_segments').addClass('hide');
			parent.find('.div_action_change_stages').addClass('hide');
			parent.find('.div_action_change_points').addClass('hide');
			parent.find('.div_action_point_action').addClass('hide');

			if (this.value == 'send_email_to_customer' || this.value == 'send_reminder_to_customer') {
				parent.find('.div_action_change_notify').addClass('hide');
			}else {
				parent.find('.div_action_change_notify').removeClass('hide');
			}
		});

		$(document).on("change", "select[name^=track]", function() {
			var parent = $(this).parents('.box');
			if (this.value == 'no_reply_from_customer') {
				parent.find('.div_reply_from_customer').removeClass('hide');
			}else{
				parent.find('.div_reply_from_customer').addClass('hide');
			}

		});

		$(document).on("change", "select[name^=status_type]", function() {
			var parent = $(this).parents('.box');

			var data = {};
			data.status_type = this.value;
			data.csrf_token_name = $('input[name=csrf_token_hash]').val();

			$.post(admin_url + 'customer_service/get_status_value_html', data).done(function (response) {
				response = JSON.parse(response);

				parent.find("select[name^=value_of_status]").html('');
				parent.find("select[name^=value_of_status]").append(response.html);
				init_selectpicker();
				$('.selectpicker').selectpicker('refresh');

			});
		});

		$(document).on("change", "select[name^=assignee_department_id]", function() {
			var parent = $(this).parents('.box');

			var data = {};
			data.department_id = this.value;
			data.csrf_token_name = $('input[name=csrf_token_hash]').val();

			$.post(admin_url + 'customer_service/get_staff_option_by_department', data).done(function (response) {
				response = JSON.parse(response);

				parent.find("select[name^=assignee_id]").html('');
				parent.find("select[name^=assignee_id]").append(response.html);
				init_selectpicker();
				$('.selectpicker').selectpicker('refresh');

			});
		});
		

		$(document).on("change", "select", function() {
			var parent = $(this).parents('.box');
			var nodeId = parent.attr('node-id');
			var node = editor.getNodeFromId(nodeId);
			var data_node = node.data;
			var select_name = this.name.split("[");
			console.log('this.value', this.value);
			console.log('this.name', select_name[0]);
			data_node[select_name[0]] = this.value;
			editor.updateNodeDataFromId(nodeId, data_node);
		});

		$('input[type="time"]').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});

		$( document ).ready(function() {
			$('input[type=radio][name^=lead_data_from]').change();
			$('input[type=radio][name^=complete_action]').change();
			$('select[name^=action]').change();
			$('select[name^=track]').change();
		});

	})(jQuery);


	function positionMobile(ev) {
		"use strict";
		mobile_last_move = ev;
	}

	function allowDrop(ev) {
		"use strict";
		ev.preventDefault();
	}

	function drag(ev) {
		"use strict";
		if (ev.type === "touchstart") {
			mobile_item_selec = ev.target.closest(".drag-drawflow").getAttribute('data-node');
		} else {
			ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
		}
	}

	function drop(ev) {
		"use strict";
		if (ev.type === "touchend") {
			var parentdrawflow = document.elementFromPoint( mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
			if(parentdrawflow != null) {
				addNodeToDrawFlow(mobile_item_selec, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
			}
			mobile_item_selec = '';
		} else {
			ev.preventDefault();
			var data = ev.dataTransfer.getData("node");
			addNodeToDrawFlow(data, ev.clientX, ev.clientY);
		}

	}

	function addNodeToDrawFlow(name, pos_x, pos_y) {
		"use strict";
		if(editor.editor_mode === 'fixed') {
			return false;
		}
		pos_x = pos_x * ( editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * ( editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
		pos_y = pos_y * ( editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * ( editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));


		switch (name) {
			case 'flow_start':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'flow_start',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('flow_start', 0,  1, pos_x, pos_y, 'flow_start', {}, html );

				init_selectpicker();

			});

			break;
			case 'condition':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'condition',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('condition', 1, 2, pos_x, pos_y, 'condition', {}, html );
				
				init_selectpicker();
			});

			break;
			case 'action':

			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'action',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {

				editor.addNode('action', 1, 1, pos_x, pos_y, 'action', {}, html );
				
				init_selectpicker();

			});

			break;
			case 'email':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'email',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('email', 1, 1, pos_x, pos_y, 'email', {}, html );

				init_selectpicker();
				init_datepicker();
				$('input[type="time"]').datetimepicker({
					datepicker: false,
					format: 'H:i'
				});
			});

			break;
			case 'sms':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'sms',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('sms', 1, 1, pos_x, pos_y, 'sms', {}, html );

				init_selectpicker();
				init_datepicker();
				$('input[type="time"]').datetimepicker({
					datepicker: false,
					format: 'H:i'
				});
			});



			break;

			case 'filter':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'filter',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('filter', 1, 2, pos_x, pos_y, 'filter', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'stage':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'stage',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('stage', 1, 1, pos_x, pos_y, 'stage', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'ticket_status':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'ticket_status',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('ticket_status', 1, 1, pos_x, pos_y, 'ticket_status', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'stage_status':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'stage_status',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('stage_status', 1, 1, pos_x, pos_y, 'stage_status', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'ticket_priority':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'ticket_priority',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('ticket_priority', 1, 1, pos_x, pos_y, 'ticket_priority', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'ticket_type':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'ticket_type',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('ticket_type', 1, 1, pos_x, pos_y, 'ticket_type', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'email_user':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'email_user',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('email_user', 1, 1, pos_x, pos_y, 'email_user', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'email_group':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'email_group',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('email_group', 1, 1, pos_x, pos_y, 'email_group', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'assignee':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'assignee',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('assignee', 1, 1, pos_x, pos_y, 'assignee', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			case 'wait':
			$.post(admin_url + 'customer_service/get_workflow_node_html', {
				type: 'wait',
				nodeId: editor.nodeId,
				csrf_token_name: $('input[name=csrf_token_hash]').val()
			}).done(function (html) {
				editor.addNode('wait', 1, 1, pos_x, pos_y, 'wait', {}, html );

				init_selectpicker();
				init_datepicker();
			});
			break;

			

			default:
		}



	}

	var transform = '';
	function showpopup(e) {
		"use strict";
		e.target.closest(".drawflow-node").style.zIndex = "9999";
		e.target.children[0].style.display = "block";

	transform = editor.precanvas.style.transform;
	editor.precanvas.style.transform = '';
	editor.precanvas.style.left = editor.canvas_x +'px';
	editor.precanvas.style.top = editor.canvas_y +'px';

	editor.editor_mode = "fixed";

}

function closemodal(e) {
	"use strict";
	e.target.closest(".drawflow-node").style.zIndex = "2";
	e.target.parentElement.parentElement.style.display  ="none";
	 editor.precanvas.style.transform = transform;
	 editor.precanvas.style.left = '0px';
	 editor.precanvas.style.top = '0px';
	 editor.editor_mode = "edit";
	}

	function changeModule(event) {
		"use strict";
		var all = document.querySelectorAll(".menu ul li");
		for (var i = 0; i < all.length; i++) {
			all[i].classList.remove('selected');
		}
		event.target.classList.add('selected');
	}

	function changeMode(option) {
		"use strict";

		if(option == 'lock') {
			lock.style.display = 'none';
			unlock.style.display = 'block';
		} else {
			lock.style.display = 'block';
			unlock.style.display = 'none';
		}

	}

	function save_workflow() {
		"use strict";
		$('input[name=workflow]').val(JSON.stringify(editor.export()));
		$('#workflow-form').submit();
	}

	function builder() {
		"use strict";
		window.location.assign(admin_url + 'customer_service/workflow_builder/<?php echo new_html_entity_decode(0); ?>');
	}

	function workflow_input_change(input) {
		"use strict";
		var value = input.val();

		input.attr('value',value);

		var parent = input.parents('.box');
		var nodeId = parent.attr('node-id');
		var node = editor.getNodeFromId(nodeId);
		var data_node = node.data;
		var select_name = input.attr("name").split("[");
		data_node[select_name[0]] = value;
		editor.updateNodeDataFromId(nodeId, data_node);
	}
</script>