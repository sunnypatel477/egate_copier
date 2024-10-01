<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>

<div id="wrapper">
	<div class="content">
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="customer-profile-group-heading"><?php echo _l($title); ?></h4>
				<?php echo form_hidden('csrf_token_hash', $this->security->get_csrf_hash()); ?>

				<?php echo form_open(admin_url('customer_service/add_edit_work_flow/'.$id),array('id'=>'workflow-form','autocomplete'=>'off')); ?>
				<?php echo form_hidden('workflow',(isset($workflow) ? $workflow->workflow : '')); ?>
				<?php echo form_close(); ?>
				<div class="row wrapper">
					<div class="col-md-2 action-tab">
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="flow_start">
							<span class="text-success glyphicon glyphicon-log-in"> </span><span class="text-success"> <?php echo _l('cs_flow_start'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="stage">
							<span class="text-primary glyphicon glyphicon-th-list"> </span><span class="text-primary"> <?php echo _l('cs_stage'); ?></span>
						</div>
						
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="ticket_status">
							<span class="text-primary 	glyphicon glyphicon-hand-up"> </span><span class="text-primary"> <?php echo _l('cs_ticket_status'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="stage_status">
							<span class="text-primary glyphicon glyphicon-fullscreen"> </span><span class="text-primary"> <?php echo _l('cs_stage_status'); ?></span>
						</div>
						
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="ticket_priority">
							<span class="text-primary glyphicon glyphicon-signal"> </span><span class="text-primary"> <?php echo _l('cs_ticket_priority'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="ticket_type">
							<span class="text-primary glyphicon glyphicon-tags"> </span><span class="text-primary"> <?php echo _l('cs_ticket_type'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="email_user">
							<span class="text-primary	glyphicon glyphicon-user"> </span><span class="text-primary"> <?php echo _l('cs_email_user'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="email_group">
							<span class="text-primary 	glyphicon glyphicon-plus"> </span><span class="text-primary"> <?php echo _l('cs_email_group'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="assignee">
							<span class="text-primary glyphicon glyphicon-refresh"> </span><span class="text-primary"> <?php echo _l('cs_assignee'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="condition">
							<span class="text-primary glyphicon glyphicon-wrench"> </span><span class="text-primary"> <?php echo _l('cs_condition'); ?></span>
						</div>
						<div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="wait">
							<span class="text-primary glyphicon glyphicon-time"> </span><span class="text-primary"> <?php echo _l('cs_wait'); ?></span>
						</div>
						
					</div>
					<div class="col-md-10">
						<div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">
							<div class="btn-export" onclick="save_workflow(); return false;"><?php echo _l('save'); ?></div>
							<div class="btn-clear" onclick="editor.clearModuleSelected()">Clear</div>
							<div class="btn-close"><a href="<?php echo admin_url('customer_service/work_flows'); ?>" class="text-white"><?php echo _l('cs_close'); ?></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php init_tail(); ?>
<?php require 'modules/customer_service/assets/js/work_flows/add_edit_work_flow_js.php';?>

