<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>

<div id="wrapper" >

    <div class="content">

        <div class="row">

            <div class="panel_s">

                <div class="panel-heading">

                    <strong style="font-size: 20px"> <?php echo _l('email_template_manage_system_templates')?> </strong>

                </div>


                <div class="panel-body">


                    <div class="text-info">
                        <h4> <?php echo _l('email_template_manage_system_templates_info');?> </h4>
                    </div>

                    <table class="table table-hover">
                        <thead>

                            <tr>

                                <th><?php echo _l('email_template_manage_type')?></th>
                                <th><?php echo _l('template_name')?></th>
                                <th>

                                    <a href="<?php echo admin_url('email_template_manage/system_template_all/0'); ?>"

                                       class="pull-right mleft5 mright25"><small><?php echo _l('disable_all'); ?></small>

                                    </a>

                                    <a href="<?php echo admin_url('email_template_manage/system_template_all/1'); ?>"

                                       class="pull-right"><small><?php echo _l('enable_all'); ?></small>

                                    </a>

                                </th>

                            </tr>

                        </thead>
                        <tbody>

                            <?php foreach ( $system_templates as $system_template ) { ?>

                                <?php $td_class = in_array( $system_template->slug , $active_templates ) ? '' : 'text-throught'; ?>

                                <tr>
                                    <td class="<?php echo $td_class?>"><?php echo $system_template->type;?></td>
                                    <td class="<?php echo $td_class?>"><?php echo $system_template->name;?></td>
                                    <td class="<?php echo $td_class?>">

                                        <?php if ( empty( $td_class ) ) { ?>

                                            <a href="<?php echo admin_url('email_template_manage/system_template_change/0?slug='.$system_template->slug )?>" class="pull-right">
                                                <small><?php echo _l('disable' ); ?></small>
                                            </a>

                                        <?php } else { ?>

                                            <a href="<?php echo admin_url('email_template_manage/system_template_change/1?slug='.$system_template->slug )?>" class="pull-right">
                                                <small><?php echo _l('enable' ); ?></small>
                                            </a>

                                        <?php } ?>

                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<?php init_tail(); ?>
