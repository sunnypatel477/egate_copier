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
                                    <a href="<?php echo admin_url('instax_printing/create_background'); ?>" class="btn btn-info pull-left"><?php echo _l('instax_printing_add_background'); ?> <i class="fa fa-plus"></i>
                                    </a>
                                </span>
                            </div>

                            <div class="col-md-2">

                                <select name="type_filter" id="type_filter" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('filter_by_department'); ?>">
                                    <option value=""><?php echo 'All'; ?></option>
                                    <option value="whole"><?php echo 'Photo Book Style'; ?></option>
                                    <option value="individual"><?php echo 'Individual'; ?></option>

                                    
                                </select>

                            </div>
                            <div class="col-md-3">

                                <select name="category_filter" id="category_filter" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('filter_by_department'); ?>">
                                    <option value=""><?php echo 'All'; ?></option>

                                    <?php foreach ($category as $key => $c) { ?>
                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="col-md-3">

                                <select name="event_category_filter" id="event_category_filter" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('filter_by_department'); ?>">
                                    <option value=""><?php echo 'All'; ?></option>

                                    <?php foreach ($event_category as $key => $event_categor) { ?>
                                            <option value="<?php echo $event_categor['id']; ?>"><?php echo $event_categor['name']; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>


                        <hr class="hr-panel-heading">

                        <?php
                        $table_data = array(
                            _l('id'),
                            _l('name'),
                            _l('apply_to'),
                            _l('category'),
                            _l('event_category'),
                            _l('Image'),
                            _l('Raw File'),

                        );
                        array_push($table_data, _l('actions'));
                        render_datatable($table_data, 'instax_printing_admin_background_images')
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
    $(function() {
        var CampaignServerParams = {
            "type_filter": "[name='type_filter']",
            "category_filter": "[name='category_filter']",
            "event_category_filter": "[name='event_category_filter']"
        };
        var tabal = initDataTable('.table-instax_printing_admin_background_images', window.location.href, [], [], CampaignServerParams, [0, 'DESC']);
        $('select').on('change', function() {
            tabal.ajax.reload();
        });

    });
</script>
</body>

</html>