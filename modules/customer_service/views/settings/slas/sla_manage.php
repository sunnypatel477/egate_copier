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
								<a href="#" onclick="add_sla(0,0,' hide'); return false;" class="btn btn-info mbot10"><?php echo _l('cs_add'); ?></a>

							</div>
							<br>
						<?php } ?>

						<?php render_datatable(array(
							_l('id'),
							_l('name'),
							_l('cs_grace_period_label'),
							_l('cs_over_due_warning_alert_label'),
							_l('cs_event'),
							_l('cs_breach_action'),
							_l('cs_breach_action_value'),
							_l('cs_breach_action_agent_manager'),
							_l('cs_hours_of_operation'),
							_l('cs_status'),
							_l('cs_date_created'),
							_l('options'),
						),'sla_table'); ?>

						<div id="modal_wrapper"></div>

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
require('modules/customer_service/assets/js/settings/slas/sla_manage_js.php');

?>
</body>
</html>