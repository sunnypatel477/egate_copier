<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-plus"> </span><span class="text-danger"> <?php echo _l('cs_email_group'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_send_an_email_to_assigned_ticket_deparment'); ?></label>
				<?php echo render_select('email_group['. $nodeId .']', $email_groups, array('name', 'label'), 'cs_email_group', '', ['df-email_group' => ''], [], '', '', true); ?>

				<!-- TODO -->
				<?php echo render_select('email_group_template['. $nodeId .']', $email_templates, array('slug', 'name'), 'cs_email_template', '', ['df-email_group_template' => ''], [], '', '', true); ?>
			</div>
		</div>
	</div>
</div>