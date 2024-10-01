<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php if(is_admin()){ ?>
							<div class="_buttons">
								<a href="<?php echo admin_url('customer_service/cs_run_cron_manually') ?>" class="btn btn-info mbot10"><?php echo _l('cs_manual_mail_scanning'); ?></a>
							</div>
						<?php } ?>
						<div class="row">
							<div class="col-md-4">
								<?php echo render_date_input('activity_log_date','utility_activity_log_filter_by_date','',array(),array(),'','activity-log-date'); ?>
							</div>
						</div>

						<?php render_datatable(array(
							_l('ticket_pipe_name'),
							_l('ticket_pipe_date'),
							_l('ticket_pipe_email_to'),
							_l('ticket_pipe_email'),
							_l('ticket_pipe_subject'),
							_l('ticket_pipe_message'),
							_l('ticket_pipe_status'),
							_l('cs_ticket'),
							_l('options'),
						),'ticket_pipe_log_table'); ?>

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
require 'modules/customer_service/assets/js/ticket_pipe_logs/ticket_pipe_log_manage_js.php';
?>
</body>
</html>
