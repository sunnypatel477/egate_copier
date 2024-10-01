<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">

			<div class="col-md-5">
				<div class="row">
					<div class="panel_s">
						<?php 

						$id = isset($sla) ? $sla->id : '';
						$code = isset($sla) ? $sla->code : '';
						$name = isset($sla) ? $sla->name : '';
						$admin_note = isset($sla) ? $sla->admin_note : '';
						$grace_period = isset($sla) ? $sla->grace_period : '';
						$event = isset($sla) ? $sla->event : '';
						$breach_action = isset($sla) ? $sla->breach_action : '';
						$breach_action_value = isset($sla) ? $sla->breach_action_value : '';
						$breach_action_agent_manager = isset($sla) ? $sla->breach_action_agent_manager : '';
						$hours_of_operation = isset($sla) ? $sla->hours_of_operation : '';
						
						$over_due_warning_alert_checked = '';
						$over_due_warning_alert = isset($sla) ? $sla->over_due_warning_alert : '';
						if($over_due_warning_alert == 'disabled'){
							$over_due_warning_alert_checked = ' checked';
						}

						$breach_action_value_hide = ' hide';
						$breach_action_agent_manager_hide = ' hide';

						if($breach_action == 'trigger_an_email'){
							$breach_action_value_hide = ' hide';
							$breach_action_agent_manager_hide = '';
						}else{
							$breach_action_value_hide = '';
							$breach_action_agent_manager_hide = ' hide';
						}


						?>
						<?php echo form_open(admin_url('customer_service/add_sla_modal/'.$id), array('id' => 'add_sla')); ?>

						<div class="panel-body">
							<h4 class="no-margin">
								<?php echo new_html_entity_decode($code); ?>
							</h4>
							<hr class="hr-panel-heading" />

							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
										<?php echo render_input('code','cs_code_label', $code,'text', ['readonly' => true]); ?>   
									</div>
									<div class="col-md-8">
										<?php echo render_input('name','cs_name_label', $name,'text'); ?>   
									</div>
									<div class="col-md-12">
										<?php echo render_input('grace_period','cs_grace_period_label', $grace_period,'text'); ?>   
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label><?php echo _l('cs_over_due_warning_alert_label'); ?></label>
											<div class="checkbox checkbox-primary">
												<input type="checkbox" id="over_due_warning_alert" name="over_due_warning_alert" <?php echo new_html_entity_decode($over_due_warning_alert_checked);?> value="over_due_warning_alert">
												<label for="over_due_warning_alert"><?php echo _l('cs_disable_overdue_and_warning_alerts_notices'); ?>
												<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_disable_overdue_and_warning_alerts_notices'); ?>"></i></a></label>
											</div>
										</div>
									</div>

									<div class="col-md-12">
										<?php 
										$sla_events = [];
										$sla_events[] = [
											'name' => 'fist_response',
											'label' => _l('cs_fist_response'),
										];
										$sla_events[] = [
											'name' => 'close',
											'label' => _l('cs_close'),
										];

										?>
										<?php echo render_select('event', $sla_events, array('name', 'label'),'cs_event', $event); ?>   
									</div>

									<div class="col-md-12">
										<?php 
										$sla_breach_actions = [];
										$sla_breach_actions[] = [
											'name' => 'trigger_an_email',
											'label' => _l('cs_trigger_an_email'),
										];
										$sla_breach_actions[] = [
											'name' => 'increase_the_priority',
											'label' => _l('cs_increase_the_priority'),
										];

										?>
										<?php echo render_select('breach_action', $sla_breach_actions, array('name', 'label'),'cs_breach_action', $breach_action); ?>   
									</div>

									<div class="col-md-12 breach_action_value_hide <?php echo new_html_entity_decode($breach_action_value_hide); ?>">

										<?php echo render_select('breach_action_value', cs_priority(), array('id', 'name'),'cs_breach_action_value', $breach_action_value, [], [], '', '', false); ?>
									</div>
									<div class="col-md-12 breach_action_agent_manager_hide <?php echo new_html_entity_decode($breach_action_agent_manager_hide); ?>">
										<?php echo render_select('breach_action_agent_manager', $staffs, array('staffid', array('firstname', 'lastname')),'cs_breach_action_agent_manager', $breach_action_agent_manager, [], [], '', '', false); ?> 
									</div>


									<div class="col-md-12">
										<?php 
										$sla_hours_of_operations = [];
										$sla_hours_of_operations[] = [
											'name' => 'full_support',
											'label' => _l('cs_full_support'),
										];
										$sla_hours_of_operations[] = [
											'name' => 'business_hours',
											'label' => _l('cs_business_hours'),
										];

										?>
										<?php echo render_select('hours_of_operation', $sla_hours_of_operations, array('name', 'label'),'cs_hours_of_operation', $hours_of_operation); ?>   
									</div>

									<div class="col-md-12">
										<p class="bold"><?php echo _l('cs_admin_note'); ?></p>

										<?php
										echo render_textarea('admin_note','',($admin_note),array('rows'=>6,'placeholder'=>_l('task_add_admin_note'),'data-task-ae-editor'=>true, !is_mobile() ? 'onclick' : 'onfocus'=>(!isset($sla) || isset($sla) && $sla->admin_note == '' ? 'warranty_receipt_process_init_editor(\'.tinymce-task\', {height:200, auto_focus: true});' : 'warranty_receipt_process_init_editor(\'.tinymce-task\', {height:200, auto_focus: true});')),array(),'no-mbot','tinymce-task'); ?>

									</div>	
								</div>
								
							</div>

							<hr />
							<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
							<a href="<?php echo admin_url('customer_service/sla_manage'); ?>"  class="btn btn-default pull-right mright5 "><?php echo _l('close'); ?></a>
						</div>
						<?php echo form_close(); ?>
					</div>

				</div>
			</div>

			<div class="col-md-7">
				<div class="row">

					<div class="panel_s"> 
						<div class="panel-body">

							<div class="row">
								<div class="col-md-12">
									<h4 class="h4-color no-margin"><i class="fa fa-list-alt" aria-hidden="true"></i> <?php echo _l('cs_sla_warnings'); ?></h4>
								</div>
							</div>
							<hr class="hr-color">

							<?php if(has_permission('customer_service', '', 'create')){ ?>
								<div class="_buttons">
									<a href="#" onclick="sla_warning_modal(<?php echo new_html_entity_decode($id) ?>,0,'add'); return false;" class="btn btn-info mbot10"><?php echo _l('cs_add_sla_warning'); ?></a>

								</div>
								<br>
							<?php } ?>

							<?php render_datatable(array(
								_l('id'),
								_l('service_level_agreement_id'),
								_l('level'),
								_l('cs_action'),
								_l('cs_action_value'),
								_l('cs_agent_manager'),
								_l('cs_order_number'),
								_l('options'),
								
							),'sla_warning_table'); ?>
						</div>

					</div>
				</div>
				<div id="modal_wrapper"></div>
			</div>


		</div>
	</div>
</div>
<div id="contract_file_data"></div>

<?php echo form_hidden('service_level_agreement_id',$id); ?>
<?php init_tail(); ?>
<?php 
require('modules/customer_service/assets/js/settings/slas/add_edit_sla_js.php');
require('modules/customer_service/assets/js/settings/slas/sla_warnings/sla_warning_manage_js.php');

?>
</body>
</html>
