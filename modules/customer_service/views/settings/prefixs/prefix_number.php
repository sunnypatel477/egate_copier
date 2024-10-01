<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open_multipart(admin_url('customer_service/prefix_number'),array('class'=>'prefix_number','autocomplete'=>'off')); ?>

<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_sla_code') ?></h5>
		<hr class="hr-color">
	</div>
</div>

<div class="form-group">
	<label><?php echo _l('cs_sla_prefix'); ?></label>
	<div  class="form-group" app-field-wrapper="cs_sla_prefix">
		<input type="text" id="cs_sla_prefix" name="cs_sla_prefix" class="form-control" value="<?php echo get_option('cs_sla_prefix'); ?>"></div>
	</div>

	<div class="form-group">
		<label><?php echo _l('cs_sla_number'); ?></label>
		<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_next_number_tooltip'); ?>"></i>
		<div  class="form-group" app-field-wrapper="cs_sla_number">
			<input type="number" min="0" id="cs_sla_number" name="cs_sla_number" class="form-control" value="<?php echo get_option('cs_sla_number'); ?>">
		</div>

	</div>

	<div class="row">
		<div class="col-md-12">
			<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_kpi_code') ?></h5>
			<hr class="hr-color">
		</div>
	</div>

	<div class="form-group">
		<label><?php echo _l('cs_kpi_prefix'); ?></label>
		<div  class="form-group" app-field-wrapper="cs_kpi_prefix">
			<input type="text" id="cs_kpi_prefix" name="cs_kpi_prefix" class="form-control" value="<?php echo get_option('cs_kpi_prefix'); ?>"></div>
		</div>

		<div class="form-group">
			<label><?php echo _l('cs_kpi_number'); ?></label>
			<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_next_number_tooltip'); ?>"></i>
			<div  class="form-group" app-field-wrapper="cs_kpi_number">
				<input type="number" min="0" id="cs_kpi_number" name="cs_kpi_number" class="form-control" value="<?php echo get_option('cs_kpi_number'); ?>">
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_ticket_category_code') ?></h5>
				<hr class="hr-color">
			</div>
		</div>

		<div class="form-group">
			<label><?php echo _l('cs_ticket_category_prefix'); ?></label>
			<div  class="form-group" app-field-wrapper="cs_ticket_category_prefix">
				<input type="text" id="cs_ticket_category_prefix" name="cs_ticket_category_prefix" class="form-control" value="<?php echo get_option('cs_ticket_category_prefix'); ?>"></div>
			</div>

			<div class="form-group">
				<label><?php echo _l('cs_ticket_category_number'); ?></label>
				<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_next_number_tooltip'); ?>"></i>
				<div  class="form-group" app-field-wrapper="cs_ticket_category_number">
					<input type="number" min="0" id="cs_ticket_category_number" name="cs_ticket_category_number" class="form-control" value="<?php echo get_option('cs_ticket_category_number'); ?>">
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_ticket_code') ?></h5>
					<hr class="hr-color">
				</div>
			</div>

			<div class="form-group">
				<label><?php echo _l('cs_ticket_prefix'); ?></label>
				<div  class="form-group" app-field-wrapper="cs_ticket_prefix">
					<input type="text" id="cs_ticket_prefix" name="cs_ticket_prefix" class="form-control" value="<?php echo get_option('cs_ticket_prefix'); ?>"></div>
				</div>

				<div class="form-group">
					<label><?php echo _l('cs_ticket_number'); ?></label>
					<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_next_number_tooltip'); ?>"></i>
					<div  class="form-group" app-field-wrapper="cs_ticket_number">
						<input type="number" min="0" id="cs_ticket_number" name="cs_ticket_number" class="form-control" value="<?php echo get_option('cs_ticket_number'); ?>">
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_workflow_code') ?></h5>
						<hr class="hr-color">
					</div>
				</div>

				<div class="form-group">
					<label><?php echo _l('cs_workflow_prefix'); ?></label>
					<div  class="form-group" app-field-wrapper="cs_workflow_prefix">
						<input type="text" id="cs_workflow_prefix" name="cs_workflow_prefix" class="form-control" value="<?php echo get_option('cs_workflow_prefix'); ?>"></div>
					</div>

					<div class="form-group">
						<label><?php echo _l('cs_workflow_number'); ?></label>
						<i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_next_number_tooltip'); ?>"></i>
						<div  class="form-group" app-field-wrapper="cs_workflow_number">
							<input type="number" min="0" id="cs_workflow_number" name="cs_workflow_number" class="form-control" value="<?php echo get_option('cs_workflow_number'); ?>">
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


