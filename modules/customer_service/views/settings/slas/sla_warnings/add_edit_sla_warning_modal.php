<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<?php 
				$title='';
				$id='';

				$level='';
				$action='';
				$action_value='';
				$agent_manager='';
				$action_value_hide = ' hide';
				$agent_manager_hide = ' hide';


				if(isset($sla_warning)){
					$title =_l('cs_update_sla_warning');
					$id= $sla_warning->id;

					$level = $sla_warning->level;
					$action = $sla_warning->action;
					$action_value = $sla_warning->action_value;
					$order_number = $sla_warning->order_number;
					$agent_manager = $sla_warning->agent_manager;

					if($action == 'trigger_an_email'){
						$action_value_hide = ' hide';
						$agent_manager_hide = '';
					}else{
						$action_value_hide = '';
						$agent_manager_hide = ' hide';
					}

				}else{
					$title =_l('cs_add_sla_warning');

					$level = '';
					$action = '';
					$order_number = $get_order_number;
				}

				$service_level_agreement_id = isset($service_level_agreement_id) ? $service_level_agreement_id : '';

				?>
				<h4 class="modal-title"><?php echo new_html_entity_decode($title); ?></h4>
			</div>
			<?php echo form_open_multipart(admin_url('customer_service/add_edit_sla_warning/'.$id), array('id' => 'add_edit_sla_warning')); ?>
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">
						<input type="hidden" value="<?php echo new_html_entity_decode($service_level_agreement_id); ?>" name="service_level_agreement_id">

						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<?php echo render_input('level','cs_level_label_percent', $level,'number', ['max' => 100, 'step' => 'any']); ?>  
								</div>
								<div class="col-md-6">
									<?php 
									$sla_actions = [];
									$sla_actions[] = [
										'name' => 'trigger_an_email',
										'label' => _l('cs_trigger_an_email'),
									];
									$sla_actions[] = [
										'name' => 'increase_the_priority',
										'label' => _l('cs_increase_the_priority'),
									];

									?>

									<?php echo render_select('action',$sla_actions,array('name', 'label'),'cs_action',$action, [], [], '', '', true); ?>
									
								</div>

								<div class="col-md-12 action_value_hide <?php echo new_html_entity_decode($action_value_hide); ?>">
									<?php echo render_select('action_value', cs_priority(), array('id', 'name'), 'cs_action_value_label', $action_value); ?>  
									
								</div>
								<div class="col-md-12 agent_manager_hide <?php echo new_html_entity_decode($agent_manager_hide); ?>">
									<?php echo render_select('agent_manager', $staffs, array('staffid', array('firstname', 'lastname')),'cs_agent_manager', $agent_manager, [], [], '', '', false); ?>
								</div>

								<div class="col-md-12">
									<div class=''>
										<?php echo render_input('order_number','cs_order_number', $order_number,'number'); ?>   
									</div>  

								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>

<?php require('modules/customer_service/assets/js/settings/slas/sla_warnings/add_edit_sla_warning_js.php'); ?>
<?php require('modules/customer_service/assets/js/settings/slas/add_edit_sla_js.php'); ?>