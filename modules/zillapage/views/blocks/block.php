<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="row">
      <div class="col-md-12" id="block-add-edit-wrapper">
         <div class="row">
            <div class="col-md-8">
               <div class="panel_s">
                  <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'block_form')) ;?>
                  <div class="panel-body">
                     <h4 class="no-margin">
                        <?php echo html_escape($title); ?>
                     </h4>
                     <hr class="hr-panel-heading" />
                     <?php $value = (isset($item) ? $item->name : ''); ?>
                     <?php $attrs = (isset($item) ? array() : array('autofocus'=>true)); ?>
                     <?php echo render_input('name','name',$value,'text',$attrs); ?>

                     <?php $value = (isset($item) ? $item->block_category : ''); ?>
                     <?php echo render_input('block_category','block_category',$value,'text'); ?>
                      <?php echo render_input('thumb','thumb','','file'); ?>
                      <?php if(isset($item) && $item->thumb){ ?>
                          <img src="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/thumb_blocks/'. $item->thumb); ?>" class="img img-responsive" width="200">
                      <?php } ?>

                     <p class="mtop20"><?php echo _l('block_content_description_note'); ?> <code>##image_url##</code></p>

                     <?php $value = (isset($item) ? $item->content : ''); ?>
                     <?php echo render_textarea('content','HTML',$value, ['rows' => 6]); ?>

                     <div class="checkbox checkbox-primary">
                          <input type="checkbox" name="active" id="active" <?php if(isset($item)){if($item->active == 1){echo 'checked';} } ?>>
                          <label for="active"><?php echo _l('active'); ?></label>
                      </div>
                     
                   
                     <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/blocks/js/block.js'); ?>"></script>
</body>
</html>
