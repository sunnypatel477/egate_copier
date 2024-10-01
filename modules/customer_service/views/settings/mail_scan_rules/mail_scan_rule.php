<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div> 

	<?php if(has_permission('customer_service', '', 'create')){ ?>
		<a href="#" onclick="new_mail_scan_rule(); return false;" class="btn btn-info pull-left display-block">
			<?php echo _l('cs_add'); ?>
		</a>
	<?php } ?>
	<br>
	<br>

	<ul class="nav nav-tabs" role="tablist" id="filters_types">
		<li role="presentation" class="active"><a href="#blocked_sender" aria-controls="blocked_sender" role="tab" data-toggle="tab"><?php echo _l('spam_filter_blocked_senders'); ?></a></li>
		<li role="presentation"><a href="#blocked_subject" aria-controls="blocked_subject" role="tab" data-toggle="tab"><?php echo _l('spam_filter_blocked_subjects'); ?></a></li>
		<li role="presentation"><a href="#blocked_phrase" aria-controls="blocked_phrase" role="tab" data-toggle="tab"><?php echo _l('spam_filter_blocked_phrases'); ?></a></li>
		<li role="presentation" ><a href="#allowed_sender" aria-controls="allowed_sender" role="tab" data-toggle="tab"><?php echo _l('spam_filter_allowed_senders'); ?></a></li>
		<li role="presentation"><a href="#allowed_subject" aria-controls="allowed_subject" role="tab" data-toggle="tab"><?php echo _l('spam_filter_allowed_subjects'); ?></a></li>
		<li role="presentation"><a href="#allowed_phrase" aria-controls="allowed_phrase" role="tab" data-toggle="tab"><?php echo _l('spam_filter_allowed_phrases'); ?></a></li>
		
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="blocked_sender">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'blocked_sender_table'
			);
			?>
		</div>
		<div role="tabpanel" class="tab-pane" id="blocked_subject">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'blocked_subject_table'
			);
			?>
		</div>
		<div role="tabpanel" class="tab-pane" id="blocked_phrase">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'blocked_phrase_table'
			);
			?>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="allowed_sender">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'allowed_sender_table'
			);
			?>
		</div>
		<div role="tabpanel" class="tab-pane" id="allowed_subject">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'allowed_subject_table'
			);
			?>
		</div>
		<div role="tabpanel" class="tab-pane" id="allowed_phrase">
			<?php 
			render_datatable(
				array(
					_l('id'),
					_l('type'),
					_l('rel_type'),
					_l('spam_filter_content'),
					_l('cs_status'),
					_l('cs_date_created'),
					_l('staffid'),
					_l('options'),
				),'allowed_phrase_table'
			);
			?>
		</div>
		
		
	</div>


	<div class="modal fade" id="spam_filter" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<?php echo form_open_multipart(admin_url('customer_service/mail_scan_rule'), array('id'=>'add_edit_mail_scan_rule')); ?>

			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('spamfilter_edit_heading'); ?></span>
						<span class="add-title"><?php echo _l('spamfilter_add_heading'); ?></span>

						<span class="allow-edit-title"><?php echo _l('allowed_filter_edit_heading'); ?></span>
						<span class="allow-add-title"><?php echo _l('allowed_filter_add_heading'); ?></span>
						
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="spam_filter_additional"></div>
							<div class="form-group">
								<label for="type"><?php echo _l('spamfilter_type'); ?></label>
								<select name="type" id="type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
									<option value=""></option>
									<option value="sender"><?php echo _l('spamfilter_type_sender'); ?></option>
									<option value="subject"><?php echo _l('spamfilter_type_subject'); ?></option>
									<option value="phrase"><?php echo _l('spamfilter_type_phrase'); ?></option>
								</select>
							</div>
							<div class="form-group hide">
								<label for="rel_type"><?php echo _l('spamfilter_rel_type'); ?></label>
								<select name="rel_type" id="rel_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
									<option value=""></option>
									<option value="blocked"><?php echo _l('rel_type_blocked'); ?></option>
									<option value="allowed"><?php echo _l('rel_type_allowed'); ?></option>
								</select>
							</div>
							
							<?php echo render_textarea('value','spam_filter_content'); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
					<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
				</div>
			</div><!-- /.modal-content -->
			<?php echo form_close(); ?>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<input type="hidden" name="blocked_sender" value="1">   
	<input type="hidden" name="blocked_subject" value="1">   
	<input type="hidden" name="blocked_phrase" value="1">   
	<input type="hidden" name="allowed_sender" value="1">   
	<input type="hidden" name="allowed_subject" value="1">   
	<input type="hidden" name="allowed_phrase" value="1">   

</body>
</html>
