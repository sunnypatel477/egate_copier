<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">

			<div class="col-md-3">
				<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
					<?php
					$i = 0;
					foreach($tab as $gr){
						?>
						<li<?php if($i == 0){echo " class='active'"; } ?>>
						<a href="<?php echo admin_url('customer_service/setting?group='.$gr); ?>" data-group="<?php echo new_html_entity_decode($gr); ?>">
							<?php
								$icon['mail_scan_rule'] = '<span class="fa-solid fa-filter"></span>';
								$icon['sla'] = '<span class="fa fa-list-alt"></span>';
								$icon['kpi'] = '<span class="fa fa-check-square"></span>';
								$icon['work_flow'] = '<span class="fa fa-list-ol"></span>';
								$icon['category'] = '<span class="fa fa-list-alt"></span>';
								$icon['prefix_number'] = '<span class="fa fa-bars menu-icon"></span>';
								$icon['custom_form'] = '<span class="fa fa-wpforms"></span>';
								$icon['general'] = '<span class="fa fa-bars menu-icon"></span>';
								$icon['email_template'] = '<span class="fa fa-envelope menu-icon"></span>';
								$icon['support_term_condition'] = '<span class="fa fa-asterisk"></span>';

								if($gr == 'prefix_number'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_prefix_settings')); 

								}elseif($gr == 'mail_scan_rule'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_mail_scan_rules')); 

								}elseif($gr == 'sla'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_slas')); 

								}elseif($gr == 'kpi'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_kpis')); 

								}elseif($gr == 'work_flow'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_work_flows')); 

								}elseif($gr == 'category'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_categories')); 

								}elseif($gr == 'custom_form'){
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_custom_forms')); 

								}else{
									echo new_html_entity_decode($icon[$gr] .' '. _l('cs_'.$gr)); 
								}
							
							?>
						</a>
					</li>
					<?php $i++; } ?>
				</ul>
			</div>
			<div class="col-md-9">
				<div class="panel_s">
					<div class="panel-body">

						<?php $this->load->view($tabs['view']); ?>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php echo form_close(); ?>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>

<?php 
$viewuri = $_SERVER['REQUEST_URI'];
 ?>

<?php if(!(strpos($viewuri,'admin/customer_service/setting?group=mail_scan_rule') === false)){
	require 'modules/customer_service/assets/js/settings/mail_scan_rules/mail_scan_rule_js.php';
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=general') === false)){
	require('modules/customer_service/assets/js/settings/generals/general_js.php');
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=kpi') === false)){
	require('modules/customer_service/assets/js/settings/kpis/kpi_js.php');
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=sla') === false)){
	require('modules/customer_service/assets/js/settings/slas/sla_manage_js.php');
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=category') === false)){
	require('modules/customer_service/assets/js/settings/categories/category_manage_js.php');
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=email_template') === false)){
	require('modules/customer_service/assets/js/settings/email_templates/email_template_manage_js.php');
}elseif(!(strpos($viewuri,'admin/customer_service/setting?group=support_term_condition') === false)){
	require('modules/customer_service/assets/js/settings/support_term_conditions/support_term_condition_js.php');
}

 ?>
</body>
</html>
