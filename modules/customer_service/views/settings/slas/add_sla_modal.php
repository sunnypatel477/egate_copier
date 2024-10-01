<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title"><?php echo new_html_entity_decode(_l('add_sla')); ?></h4>
			</div>
			<?php echo form_open(admin_url('customer_service/add_sla_modal'), array('id' => 'add_sla')); ?>
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">

						<div class="col-md-12">
							<div class="col-md-4">
								<?php echo render_input('code','cs_code_label', $code,'text', ['readonly' => true]); ?>   
							</div>
							<div class="col-md-8">
								<?php echo render_input('name','cs_name_label','','text'); ?>   
							</div>
							<div class="col-md-12">
								<?php echo render_input('grace_period','cs_grace_period_label','','text'); ?>   
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label><?php echo _l('cs_over_due_warning_alert_label'); ?></label>
									<div class="checkbox checkbox-primary">
										<input type="checkbox" id="over_due_warning_alert" name="over_due_warning_alert" <?php if(get_option('over_due_warning_alert') == 1 ){ echo 'checked';} ?> value="over_due_warning_alert">
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
								<?php echo render_select('event', $sla_events, array('name', 'label'),'cs_event',''); ?>   
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
								<?php echo render_select('breach_action', $sla_breach_actions, array('name', 'label'),'cs_breach_action',''); ?>   
							</div>

							<div class="col-md-12 breach_action_value_hide hide">

								<?php echo render_select('breach_action_value', cs_priority(), array('id', 'name'),'cs_breach_action_value',''); ?>   
							</div>
							<div class="col-md-12 breach_action_agent_manager_hide hide">
								<?php echo render_select('breach_action_agent_manager', $staffs, array('staffid', array('firstname', 'lastname')),'cs_breach_action_agent_manager',''); ?>   
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
								<?php echo render_select('hours_of_operation', $sla_hours_of_operations, array('name', 'label'),'cs_hours_of_operation',''); ?>   
							</div>
							
							<div class="col-md-12">
								<p class="bold"><?php echo _l('cs_admin_note'); ?></p>
								<?php
               					// onclick and onfocus used for convert ticket to task too
								echo render_textarea('admin_note','',(isset($routing) ? $routing->admin_note : ''),array('rows'=>6,'placeholder'=>_l('cs_admin_note'),'data-task-ae-editor'=>true, !is_mobile() ? 'onclick' : 'onfocus'=>(!isset($routing) || isset($routing) && $routing->admin_note == '' ? 'warranty_receipt_process_init_editor(\'.tinymce-task\', {height:200, auto_focus: true});' : '')),array(),'no-mbot','tinymce-task'); ?>
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('cs_close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>
<?php require('modules/customer_service/assets/js/settings/slas/add_edit_sla_js.php'); ?>