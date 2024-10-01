<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color" ><?php echo _l('cs_customer_service_management_label')?></h5>
		<hr class="hr-color" >
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<div class="checkbox checkbox-primary">
				<input onchange="auto_create_change_setting(this); return false" type="checkbox" id="customer_service_display_on_portal" name="purchase_setting[customer_service_display_on_portal]" <?php if(get_option('customer_service_display_on_portal') == 1 ){ echo 'checked';} ?> value="customer_service_display_on_portal">
				<label for="customer_service_display_on_portal"><?php echo _l('cs_display_customer_service_on_client_portal'); ?>
				<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_display_customer_service_on_client_portal'); ?>"></i></a>
			</label>
		</div>
	</div>
</div>
</div>
<?php echo form_open_multipart(admin_url('customer_service/general'),array('class'=>'general','autocomplete'=>'off')); ?>

<div class="row">
	<div class="col-md-12">
		<h5 class="font-bold" ><?php echo _l('cs_service_business_time_label')?></h5>
	</div>

	<div class="col-md-2">
		<?php echo render_input('customer_service_business_from_hours', 'customer_service_business_from_hours_label', get_option('customer_service_business_from_hours')); ?>
	</div>
	<div class="col-md-2">
		<?php echo render_input('customer_service_business_to_hours', 'customer_service_business_to_hours_label', get_option('customer_service_business_to_hours')); ?>
	</div>
	<div class="col-md-8">
		<?php 
		$customer_service_business_day_value = new_explode(',', get_option('customer_service_business_days'));
		?>

		<div class="form-group">
			<label for="customer_service_business_days"><?php echo _l('customer_service_business_days_label'); ?></label>
			<select name="customer_service_business_days[]" id="customer_service_business_days" multiple="true" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
				<option value="0" <?php if(in_array(0, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('monday'); ?></option>
				<option value="1" <?php if(in_array(1, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('tuesday'); ?></option>
				<option value="2" <?php if(in_array(2, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('wednesday'); ?></option>
				<option value="3" <?php if(in_array(3, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('thursday'); ?></option>
				<option value="4" <?php if(in_array(4, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('friday'); ?></option>
				<option value="5" <?php if(in_array(5, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('saturday'); ?></option>
				<option value="6" <?php if(in_array(6, $customer_service_business_day_value)){echo 'selected';} ?>><?php echo _l('sunday'); ?></option>
			</select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h5 class="font-bold" ><?php echo _l('cs_mail_scan_from_departments')?></h5>
	</div>
	<div class="col-md-12">
		<?php 
		$cs_mail_scan_from_departments = get_option('cs_mail_scan_from_departments');
		$cs_mail_scan_from_departments = explode(",", $cs_mail_scan_from_departments);
		?>
		<?php echo render_select('cs_mail_scan_from_departments[]', $departments, array('departmentid', 'name'), '', $cs_mail_scan_from_departments, ['multiple' => true, 'data-actions-box' => true], [], '', '', false) ?>
	</div>
</div>

<div class="clearfix"></div>

<div class="modal-footer">
	<?php if(has_permission('customer_service', '', 'create') || has_permission('customer_service', '', 'edit') ){ ?>
		<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	<?php } ?>
</div>
<?php echo form_close(); ?>


</body>
</html>


