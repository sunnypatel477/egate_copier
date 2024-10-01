<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-tags"> </span><span class="text-danger"> <?php echo _l('cs_ticket_type'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_change_the_type_of_a_ticket'); ?></label>
				
				<?php echo render_select('ticket_type['. $nodeId .']', cs_ticket_type(), array('id', 'name'), 'cs_status', '', ['df-ticket_type' => ''], [], '', '', true); ?>
			</div>
		</div>
	</div>
</div>