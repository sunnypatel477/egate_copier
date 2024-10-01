<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
	<div class="col-md-12">
		<h5 class="no-margin font-bold h5-color"><?php echo _l('cs_support_term_condition') ?></h5>
		<hr class="hr-color">
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php echo render_textarea('cs_support_term_condition', '', get_option('cs_support_term_condition'), array(), array(), '', 'tinymce'); ?>
	</div>
</div>

<?php if(has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') ){ ?>
	<button type="button" class="btn btn-info pull-right submit_policies_information" onclick ="submit_support_term_condition(this); return false"><?php echo _l('submit'); ?></button>
	<?php } ?>

</body>
</html>