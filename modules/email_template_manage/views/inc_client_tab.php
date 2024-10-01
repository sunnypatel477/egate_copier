<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ( !empty( $client->userid ) ) { ?>

    <h4 class="customer-profile-group-heading">
        <?php echo _l('email_template_manage_email_log')?>
    </h4>

    <div class="row">
        <div class="col-md-4 mbot20">
            <a class="btn btn-primary" onclick="email_template_manage_send_mail( 'customer' , <?php echo $client->userid ?> ); return false;">
                <span class="fa fa-envelope"></span>
                <?php echo _l('email_template_manage_send_mail')?>
            </a>
            <br />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">

        <div class="col-md-12">

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

    <input type="hidden" name="email_template_manage_rel_type" value="customer">
    <input type="hidden" name="email_template_manage_rel_id" value="<?php echo $client->userid?>">

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var mail_post_data = {};

            mail_post_data['rel_type']  = '[name="email_template_manage_rel_type"]';
            mail_post_data['rel_id']    = '[name="email_template_manage_rel_id"]';

            initDataTable('.table-mail-records', admin_url + 'email_template_manage/mail_log_lists', false, false, mail_post_data , [0,"desc"]);


        });

    </script>

<?php } ?>
