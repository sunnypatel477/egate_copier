<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
	  <div class="row">
		 <div class="col-md-6">
			<div class="panel_s">
			   <div class="panel-body">
				  <h4 class="no-margin">
					 <?php echo new_html_entity_decode($title); ?>
				  </h4>
				  <hr class="hr-panel-heading" />
				  <?php echo form_open($this->uri->uri_string()); ?>
				  <div class="row">
					 <div class="col-md-12">
						<input type="hidden" name="type" value="customer_service_email_template">

						<?php 
						$name = isset($template) ? $template->name : '';
						$subject = isset($template) ? $template->subject : '';
						$fromname = isset($template) ? $template->fromname : '{companyname} | CRM';
						$emailtemplateid = isset($template) ? $template->emailtemplateid : 0;
						$fromemail = isset($template) ? $template->fromemail : '';
						$plaintext = isset($template) ? $template->plaintext : 0;
						$active = isset($template) ? $template->active : 1;
						$message = isset($template) ? $template->message : '';
						$slug = isset($template) ? $template->slug : '';
						$type = isset($template) ? $template->type : 'customer_service_email_template';
						?>

						<?php echo render_input('name','template_name',$name,'text'); ?>

						<?php echo render_input('subject['.$emailtemplateid.']','template_subject',$subject); ?>
						<?php echo render_input('fromname','template_fromname',$fromname); ?>
						<div
						style="<?php echo (hooks()->apply_filters('show_deprecated_from_email_header_template_field', false) === false
						? 'display:none;'
						: ''); ?>">
						<?php if($template->slug != 'two-factor-authentication'){ ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('email_template_only_domain_email'); ?>"></i>
						<?php echo render_input('fromemail','template_fromemail',$fromemail,'email'); ?>
						<?php } ?>
					  </div>
						<div class="checkbox checkbox-primary">
						   <input type="checkbox" name="plaintext" id="plaintext" <?php if($plaintext == 1){echo 'checked';} ?>>
						   <label for="plaintext"><?php echo _l('send_as_plain_text'); ?></label>
						</div>
						<?php if($slug != 'two-factor-authentication'){ ?>
						<div class="checkbox checkbox-primary">
						   <input type="checkbox" name="disabled" id="disabled" <?php if($active == 0){echo 'checked';} ?>>
						   <label data-toggle="tooltip" title="<?php echo _l('disable_email_from_being_sent'); ?>" for="disabled"><?php echo _l('email_template_disabled'); ?></label>
						</div>
						<?php } ?>
						<hr />
						<?php
						   $editors = array();
						   array_push($editors,'message['.$emailtemplateid.']');
						   ?>
						<h4 class="bold font-medium">English</h4>
						<p class="bold"><?php echo _l('email_template_email_message'); ?></p>
						<?php echo render_textarea('message['.$emailtemplateid.']','',$message,array('data-url-converter-callback'=>'myCustomURLConverter'),array(),'','tinymce'); ?>

						<?php if(isset($template)){ ?>
						<?php foreach($available_languages as $availableLanguage){
						   $lang_template = $this->emails_model->get(array('slug'=>$template->slug,'language'=>$availableLanguage));
						   if(count($lang_template) > 0){
							 $lang_used = false;
							 if(get_option('active_language') == $availableLanguage || total_rows(db_prefix().'staff',array('default_language'=>$availableLanguage)) > 0 || total_rows(db_prefix().'clients',array('default_language'=>$availableLanguage)) > 0){
							   $lang_used = true;
							 }
							 $hide_template_class = '';
							 if($lang_used == false){
							   $hide_template_class = 'hide';
							 }
							 ?>
						<hr />
						<h4 class="font-medium pointer bold" onclick='slideToggle("#temp_<?php echo new_html_entity_decode($availableLanguage); ?>");'>
						   <?php echo ucfirst($availableLanguage); ?>
						</h4>
						<?php
						   $lang_template = $lang_template[0];
						   array_push($editors,'message['.$lang_template['emailtemplateid'].']');
						   echo '<div id="temp_'.$availableLanguage.'" class="'.$hide_template_class.'">';
						   echo render_input('subject['.$lang_template['emailtemplateid'].']','template_subject',$lang_template['subject']);
						   echo '<p class="bold">'._l('email_template_email_message').'</p>';
						   echo render_textarea('message['.$lang_template['emailtemplateid'].']','',$lang_template['message'],array('data-url-converter-callback'=>'myCustomURLConverter'),array(),'','tinymce');
						   echo '</div>';
						   }
						   } ?>
						<?php } ?>
						<?php 
						$editors = array_unique($editors);
						 ?>

						<div class="btn-bottom-toolbar text-right">
							<a href="<?php echo admin_url('customer_service/setting?group=email_template'); ?>"class="btn btn-default text-right mright5"><?php echo _l('close'); ?></a>
						   <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
						</div>
					 </div>
					 <?php echo form_close(); ?>
				  </div>
			   </div>
			</div>
		 </div>
		 <div class="col-md-6">
			<div class="panel_s">
			   <div class="panel-body">
				  <h4 class="no-margin">
					 <?php echo _l('available_merge_fields'); ?>
				  </h4>
				  <hr class="hr-panel-heading" />
				  <div class="row">
					 
					 <div class="col-md-12">
						<div class="row available_merge_fields_container">
						   <?php
							  $mergeLooped = array();
							  foreach($available_merge_fields as $field){
							   foreach($field as $key => $val){
								echo '<div class="col-md-6 merge_fields_col">';
								echo '<h5 class="bold">'._l(ucfirst($key)).'</h5>';
								foreach($val as $_field){
								  if(count($_field['available']) == 0
									&& isset($_field['templates']) && in_array($slug, $_field['templates'])) {
									  // Fake data to simulate foreach loop and check the templates key for the available slugs
									$_field['available'][] = '1';
								}
								foreach($_field['available'] as $_available){
								  if(($_available == $type || isset($_field['templates']) && in_array($slug, $_field['templates'])) && !in_array($_field['name'], $mergeLooped)){
									$mergeLooped[] = $_field['name'];
									echo '<p>'.$_field['name'];
									echo '<span class="pull-right"><a href="#" class="add_merge_field">';
									echo new_html_entity_decode($_field['key']);
									echo '</a>';
									echo '</span>';
									echo '</p>';
								  }
								}
							  }
							  echo '</div>';
							  }
							  }
							  ?>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
		 </div>
	  </div>
	  <div class="btn-bottom-pusher"></div>
   </div>
</div>
<?php init_tail(); ?>
<?php 
require 'modules/customer_service/assets/js/settings/email_templates/add_edit_email_template_js.php';
	
 ?>

</body>
</html>
