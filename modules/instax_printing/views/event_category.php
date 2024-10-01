<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                   
                        <div class="row">
                            <div class="col-md-4">
                                <span class="header-btn-new">
                                    <a href="<?php echo admin_url('instax_printing/create_event_category'); ?>"
                                       class="btn btn-info pull-left"><?php echo _l('instax_printing_add_event_category');?> <i class="fa fa-plus"></i>
                                    </a>
                                </span>
                            </div>
                            
                        </div>
                        
                        
                        <hr class="hr-panel-heading">

                        <?php 
                        $table_data = array(
                            _l('id'),
                            _l('name'),
                          
                            
                        ); 
                        array_push($table_data,_l('actions'));
                        render_datatable($table_data,'instax_printing_admin_event_category')
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
    $(function () {
        var CampaignServerParams = {
          
      };
       var tabal = initDataTable('.table-instax_printing_admin_event_category', window.location.href, [], [], CampaignServerParams, [0, 'DESC']);
   

    });
</script>
</body>
</html>
