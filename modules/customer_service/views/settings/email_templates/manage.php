<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div> 
	<?php if(has_permission('customer_service', '', 'create')){ ?>
		<a href="<?php echo admin_url('customer_service/add_edit_email_template') ?>" class="btn btn-info pull-left display-block">
			<?php echo _l('cs_add'); ?>
		</a>
	<?php } ?>
	<br>
	<br>
	<div role="tabpanel" class="tab-pane active" id="email_template">
		<?php 
		render_datatable(
			array(
				_l('id'),
				_l('type'),
				_l('cs_name_label'),
				_l('cs_subject'),
				_l('active'),
				_l('options'),
			),'email_template_table'
		);
		?>
	</div>
</body>
</html>
