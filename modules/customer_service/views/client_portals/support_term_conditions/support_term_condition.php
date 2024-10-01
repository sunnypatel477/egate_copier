<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div class="panel_s section-heading section-invoices">
	<div class="panel-body">
		<div class="col-md-6">
			<h4 class="no-margin section-text"><?php echo _l('cs_tickets'); ?></h4>
			<br>
			<span><a href="<?php echo admin_url('customer_service/customer_service_client/support_term_condition') ?>"><?php echo _l('cs_support_term_condition'); ?></a></span>
		</div>

		<div class="col-md-6">
			<ul class="nav navbar-nav navbar-right">
				<a href="<?php echo admin_url('customer_service/customer_service_client/add_edit_ticket') ?>" class="btn btn-info"><?php echo _l('cs_add_ticket'); ?></a>
			</ul>

		</div>
	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
		<div class="col-md-12">
			
			<div class="row mbot25">
				<html><?php echo get_option('cs_support_term_condition'); ?></html>
			</div>
		</div>
	</div>
</div>
<?php hooks()->do_action('app_customers_portal_footer'); ?>
