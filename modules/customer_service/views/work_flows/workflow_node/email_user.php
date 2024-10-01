<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-user"> </span><span class="text-danger"> <?php echo _l('cs_email_user'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_send_an_email_to_requester_or_assignee'); ?></label>
				
				<?php echo render_select('email_user['. $nodeId .']', $staffs, array('staffid', array('firstname', 'lastname')), 'cs_email_user', '', ['df-email_user' => ''], [], '', '', true); ?>

				<?php echo render_select('email_user_template['. $nodeId .']', $email_templates, array('slug', 'name'), 'cs_email_template', '', ['df-email_user_template' => ''], [], '', '', true); ?>
			</div>
		</div>
	</div>
</div>