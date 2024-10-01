<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-success glyphicon glyphicon-log-in"> </span><span class="text-success"> <?php echo _l('flow_start'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<div class="form-group">
					<h5 for="lead_data_from" class="text-center"><?php echo _l('cs_workflow_starts_here'); ?></h5>
				</div>
			</div>
		</div>
	</div>
</div>
