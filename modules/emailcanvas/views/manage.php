<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('emailcanvas', '', 'create')) { ?>
                    <div class="tw-mb-2 sm:tw-mb-4">
                        <a href="<?php echo admin_url('emailcanvas/create'); ?>" class="btn btn-primary">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('emailcanvas_create'); ?>
                        </a>
                    </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                            _l('emailcanvas_template_name'),
                            _l('emailcanvas_template_description'),
                            _l('emailcanvas_is_enabled'),
                            _l('emailcanvas_created_at'),
                            _l('options'),
                        ], 'emailcanvas-categories'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-emailcanvas-categories', window.location.href, [3], [3], [], [3, 'desc']);
    });
</script>
</body>

</html>