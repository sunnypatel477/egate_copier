<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo new_html_entity_decode(_l('cs_add_work_flow')); ?></h4>
			</div>
			<?php 
				$id = '';
				$code = $ex_code;
				$workflow_name = '';
				$sla_id = '';
				$kpi_id = '';
				if(isset($workflow)){
					$id = $workflow->id;
					$code = $workflow->code;
					$workflow_name = $workflow->workflow_name;
					$sla_id = $workflow->sla_id;
					$kpi_id = $workflow->kpi_id;
				}
			 ?>

			<?php echo form_open(admin_url('customer_service/add_edit_work_flow/'.$id), array('id' => 'add_edit_work_flow')); ?>
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">
						<div class="col-md-12">
							<?php echo render_input('code','cs_code_label', $code,'text', ['readonly' => true]); ?>   
						</div>
						<div class="col-md-12">
							<?php echo render_input('workflow_name','cs_name_label', $workflow_name,'text'); ?>   
						</div>
						<div class="col-md-12">
							<?php echo render_select('sla_id', $slas, array('id', array('code', 'name')),'cs_service_level_agreement', $sla_id); ?>   
						</div>
						<div class="col-md-12">
							<?php echo render_select('kpi_id',$kpis, array('id', array('code', 'name')), 'cs_kpi', $kpi_id); ?>   
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
<?php 
require 'modules/customer_service/assets/js/work_flows/work_flow_modal_js.php';

?>