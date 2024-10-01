
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12" >
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/all') ?>" class=" cs-portal-a-pause" >
									<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_all'); ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['all']) ? $ticket_status['all'] : 0 , true) ?></span>
								</a>
							</div>
							<div class="col-md-2 list-status statement-bg  projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/open') ?>" class=" cs-portal-a-cancelled" >
									<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_open') ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['open']) ? $ticket_status['open'] : 0 , true) ?></span>
								</a>
							</div>

							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/inprogress') ?>" class=" cs-portal-a-renewal" >
									<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_inprogress'); ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['inprogress']) ? $ticket_status['inprogress'] : 0 , true) ?></span>
								</a>
							</div>

							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/answered') ?>" class=" cs-portal-a-expired">
									<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_answered'); ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['answered']) ? $ticket_status['answered'] : 0 , true) ?></span>
								</a>
							</div>

							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/on_hold') ?>" class=" cs-portal-a-complete">
									<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_on_hold'); ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['on_hold']) ? $ticket_status['on_hold'] : 0 , true) ?></span>
								</a>
							</div>


							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/closed') ?>" class=" cs-portal-a-activate" >
									<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_close') ; ?></h4>
									<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['closed']) ? $ticket_status['closed'] : 0 , true) ?></span>
								</a>
							</div>
						</div>

						<div class="row mtop15">
							<div class="col-md-3">
								<div class="form-group" id="report-time">
									<label for="mo_months-report"><?php echo _l('period_datepicker'); ?></label><br />
									<select class="selectpicker" name="mo_months-report" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
										<option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
										<option value="this_month"><?php echo _l('this_month'); ?></option>
										<option value="1"><?php echo _l('last_month'); ?></option>
										<option value="this_year"><?php echo _l('this_year'); ?></option>
										<option value="last_year"><?php echo _l('last_year'); ?></option>
										<option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
										<option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
										<option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
										<option value="custom"><?php echo _l('period_datepicker'); ?></option>
									</select>
								</div>
							</div>
						</div>

							<div class="row">
								
							<div class="col-md-2 list-status projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/all') ?>" class=" cs-portal-a-total-hours" >
									<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_total_hours'); ?></h4>
									<span class="bold cs-portal-h-cancelled ticket_total_hours" ><?php echo app_format_number(isset($ticket_total_hours['total_hours']) ? $ticket_total_hours['total_hours'] : 0 , true) ?></span>
								</a>
							</div>
							<div class="col-md-2 list-status statement-bg  projects-status">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/open') ?>" class=" cs-portal-a-avg-resolution-time" >
									<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_avg_resolution_time') ?></h4>
									<span class="bold cs-portal-h-cancelled ticket_avg_resolution_time" ><?php echo app_format_number(isset($ticket_total_hours['avg_resolution_time']) ? $ticket_total_hours['avg_resolution_time'] : 0 , true) .' '._l('cs_hours')?></span>
								</a>
							</div>
							<div class="col-md-2 list-status statement-bg  projects-status" data-original-title="<?php echo _l('customer_satisfaction_score_label'); ?>" data-toggle="tooltip" data-placement="top">
								<a href="<?php echo site_url('customer_service/customer_service_client/tickets/open') ?>" class=" cs-portal-a-customer-satisfaction-score" >
									<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_CSAT') ?></h4>
									<span class="bold cs-portal-h-cancelled ticket_avg_resolution_time" ><?php echo app_format_number(isset($cal_CSAT) ? $cal_CSAT : 0 , true) .' %'?></span>
								</a>
							</div>
							
						</div>

						<div class="row">
							<div class="col-md-12">
								<div id="report_by_ticket_on_hold_closed">
								</div>
							</div>
							<div class="col-md-6">
								<div id="report_by_ticket_by_issue_type">
								</div>
							</div>
							<div class="col-md-6">
								<div id="report_by_ticket_avg_resolution_time_by_issue_type">
								</div>
							</div>
							<div class="col-md-6">
								<div id="report_by_ticket_status">
								</div>
							</div>
							<div class="col-md-6">
								<div id="report_by_ticket_category">
								</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<?php require('modules/customer_service/assets/js/dashboards/dashboard_js.php'); ?>

</body>
</html>
