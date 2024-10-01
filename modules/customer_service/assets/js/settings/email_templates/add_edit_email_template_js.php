<script>
	$(function(){
		'use strict';
		
		<?php foreach($editors as $id){ ?>
			init_editor('textarea[name="<?php echo new_html_entity_decode($id); ?>"]',{urlconverter_callback:'merge_field_format_url'});
		<?php } ?>
		var merge_fields_col = $('.merge_fields_col');
		 // If not fields available
		 $.each(merge_fields_col, function() {
		 	var total_available_fields = $(this).find('p');
		 	if (total_available_fields.length == 0) {
		 		$(this).remove();
		 	}
		 });
	 // Add merge field to tinymce
	 $('.add_merge_field').on('click', function(e) {
	 	e.preventDefault();
	 	tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
	 });
	 appValidateForm($('form'), {
	 	name: 'required',
	 	fromname: 'required',
	 });
	});
</script>