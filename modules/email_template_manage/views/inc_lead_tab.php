<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $email_template_lead_id = !empty( $lead->id ) ? $lead->id : 0 ; ?>

<div role="tabpanel" class="tab-pane" id="email_template_manage_mail_tab">

    <div class="row">
        <div class="col-md-4 mbot20">
            <a class="btn btn-primary" onclick="email_template_manage_send_mail( 'lead' , <?php echo $email_template_lead_id ?> ); return false;">
                <span class="fa fa-envelope"></span>
                <?php echo _l('email_template_manage_send_mail')?>
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">

        <div class="col-md-12" style="min-height: 350px">

            <div class="table-responsive s_table">

                <table class="table table-mail-records">
                    <thead>
                        <tr>
                            <th><?php echo _l('id')?></th>
                            <th><?php echo _l('staff')?></th>
                            <th><?php echo _l("email_template_manage_log_table_head_subject")?></th>
                            <th><?php echo _l("email_template_manage_log_table_head_date")?></th>
                            <th><?php echo _l("email_template_manage_log_table_head_status")?></th>
                            <th><?php echo _l("email_template_manage_opened")?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    <div class="modal fade" id="email_template_manage_mail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content" id="email_template_manage_mail_content">


            </div>

        </div>

    </div>

    <input type="hidden" name="email_template_manage_rel_type" value="lead">
    <input type="hidden" name="email_template_manage_rel_id" value="<?php echo $email_template_lead_id?>">

    <?php if ( !empty( $email_template_lead_id ) ) { ?>

        <script>

            $(document).ready(function (){

                var mail_post_data = {};

                mail_post_data['rel_type']  = '[name="email_template_manage_rel_type"]';
                mail_post_data['rel_id']    = '[name="email_template_manage_rel_id"]';

                initDataTable('.table-mail-records', admin_url + 'email_template_manage/mail_log_lists', false, false, mail_post_data , [0,"desc"]);


            })

        </script>

    <?php } ?>


</div>
