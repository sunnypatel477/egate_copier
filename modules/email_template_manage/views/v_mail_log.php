<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>

<div id="wrapper" >

    <div class="content">

        <div class="row">

            <div class="panel_s">

                <div class="panel-heading">

                    <div>

                        <strong style="font-size: 20px"> <?php echo _l('email_template_manage_email_log')?> </strong>

                    </div>

                </div>


                <div class="panel-body">


                    <div class="col-md-3">
                        <input type="hidden" name="from_date" id="from_date" value="">
                        <input type="hidden" name="to_date" id="to_date" value="">
                        <?php $this->load->view('_statement_period_select', ['onChange' => 'reload_the_table()']); ?>
                    </div>

                    <div class="col-md-3">
                        <?php echo render_select( 'template_id' , $templates , [ 'id' , [ 'template_name'] ] ,  '' , '' , ['onchange' => 'reload_the_table()' , 'data-none-selected-text' => _l('email_template_manage') ] ); ?>
                    </div>

                    <div class="col-md-3">
                        <?php echo render_select( 'staff_id' , $staff , [ 'staffid' , [ 'firstname' , 'lastname' ] ] ,  '' , '' , ['onchange' => 'reload_the_table()' , 'data-none-selected-text' => _l('staff') ] ); ?>
                    </div>

                    <div class="col-md-2">
                        <?php echo render_select( 'send_rel_type' , $related_types , [ 'id' , [ 'value' ] ] ,  '' , '' , ['onchange' => 'reload_the_table()' , 'data-none-selected-text' => _l('email_template_manage_related_type') ] ); ?>
                    </div>

                    <div class="col-md-1 text-right">

                        <a class="btn btn-danger _delete"

                           href="<?php echo admin_url('email_template_manage/mail_log_clear'); ?>"><?php echo _l('clear_activity_log'); ?></a>

                    </div>

                    <div class="col-md-12">

                        <div class="table-responsive ">

                            <table class="table table-mail-records">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id')?></th>
                                        <th><?php echo _l('staff')?></th>
                                        <th><?php echo _l("email_template_manage_log_table_head_from")?></th>
                                        <th><?php echo _l("email_template_manage_log_table_head_email")?></th>

                                        <th><?php echo _l('email_template_manage_use_smtp')?></th>
                                        <th><?php echo _l('email_template_manage_template_name')?></th>
                                        <th><?php echo _l('email_template_manage_related_type')?></th>

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

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="email_template_manage_mail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content" id="email_template_manage_mail_content">


        </div>

    </div>

</div>

<?php init_tail(); ?>

<script>

    $(document).ready(function (){

        //mail_log_load_table();

        initDataTable('.table-mail-records', admin_url + 'email_template_manage/mail_log_lists', [], [],
            {
                "from_date": '#from_date',
                "to_date": '#to_date',
                "template_id": '#template_id',
                "staff_id": '#staff_id',
                "send_rel_type": '#send_rel_type',
            } , [0,"desc"] );

    });

    function reload_the_table()
    {

        var $statementPeriod = $('#range');
        var value = $statementPeriod.selectpicker('val');
        var period = new Array();

        if (value != 'period')
        {
            period = JSON.parse(value);
        }
        else
        {
            period[0] = $('input[name="period-from"]').val();
            period[1] = $('input[name="period-to"]').val();

            if (period[0] == '' || period[1] == '') {
                return false;
            }
        }

        $('#from_date').val(period[0]);
        $('#to_date').val(period[1]);

        mail_log_load_table();

    }

    function mail_log_load_table() {

        $('.table-mail-records').DataTable().ajax.reload();

        /*
        if ($.fn.DataTable.isDataTable('.table-mail-records'))
        {
            $('.table-mail-records').DataTable().destroy();
        }

        initDataTable('.table-mail-records', admin_url + 'email_template_manage/mail_log_lists', [], [],
            {
                "from_date": '#from_date',
                "to_date": '#to_date',
                "template_id": '#template_id',
                "staff_id": '#staff_id',
            } , [0,"desc"] );

         */
    }

</script>
