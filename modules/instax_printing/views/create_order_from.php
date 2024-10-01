<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'order-from-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        
                    </h4>
                    
                </div>
                <input type="hidden" name="id" value="<?php echo (isset($order_from) ? $order_from->id : ''); ?>">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($order_from) ? $order_from->name : ''); ?>
                        <?php $attrs = (isset($order_from) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('name', 'name', $value, 'text', $attrs); ?>
                       
                         
                       
                    </div>
                    
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">
                                <?php echo _l('submit'); ?>
                            </button>
                        </div>
                    
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {
      
        appValidateForm($('#order-from-form'), {
            name: 'required',
            
        });
    });
</script>
</body>

</html>