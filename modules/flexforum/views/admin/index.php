<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
                    <span class="tw-font-bold tw-text-xl">
                        <?php echo $title ?>
                    </span>
                    <?php if (flexforum_has_permission('create')) { ?>
                        <a href="<?php echo flexforum_admin_url('categories') ?>" class="btn btn-link">
                            <?php echo flexforum_lang('topic_categories'); ?>
                        </a>
                        <a href="<?php echo flexforum_admin_url('bans') ?>" class="btn btn-link">
                            <?php echo flexforum_lang('banned_users'); ?>
                        </a>

                        <a href="<?php echo flexforum_admin_url('settings') ?>" class="btn btn-link">
                            <?php echo flexforum_lang('settings'); ?>
                        </a>
                        <a href="<?php echo flexforum_admin_url('model_list') ?>" class="btn btn-link">
                            <?php echo flexforum_lang('model_list'); ?>
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>

                <?php echo flexforum_get_topics_partial() ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>

</script>
</body>

</html>