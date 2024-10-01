<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">

						<?php if(has_permission('customer_service', '', 'create')){ ?>
							<div class="_buttons">
								<a href="#" onclick="add_workflow_modal(0,'add'); return false;" class="btn btn-info mbot10"><?php echo _l('cs_add'); ?></a>
							</div>
							<br>
						<?php } ?>

						<?php render_datatable(array(
							_l('id'),
							_l('cs_code_label'),
							_l('cs_SLA_plan'),
							_l('cs_kpi'),
							_l('cs_status'),
							_l('cs_date_created'),
							_l('options'),
						),'work_flow_table'); ?>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php echo form_close(); ?>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>
<div id="modal_wrapper"></div>

<?php init_tail(); ?>

<?php 
require 'modules/customer_service/assets/js/work_flows/work_flow_manage_js.php';
?>
</body>
</html>
