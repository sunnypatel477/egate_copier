<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>

<div id="wrapper" >

    <div class="content">


        <div class="row">

            <div class="col-md-12">
                <a href="#" class="btn btn-success btn-sm" onclick="slideToggle('.usernote'); return false;">

                    <i class="fa-regular fa-plus tw-mr-1"></i>

                    <?php echo _l('new_note'); ?>

                </a>
            </div>

        </div>

        <div class="">&nbsp;</div>

        <div class="clearfix"></div>

        <div class="usernote hide">

            <?php echo form_open(admin_url('notes/notes/add_note')); ?>

                <div class="panel_s">

                    <div class="panel-body">

                        <div class="row">


                            <div class="col-md-6">

                                <div class="form-group select-placeholder">

                                    <label class="control-label"><?php echo _l('lead_add_edit_source')?></label>

                                    <select class="selectpicker" name="rel_type" id="rel_type" data-width="100%" required >

                                        <?php
                                        if (!empty($rel_type))
                                        {

                                            foreach ($rel_type as $key => $type)
                                            {

                                                $selected = $key == 'customer' ? 'selected' : '';

                                                echo "<option $selected value='$key'> "._l($type)." </option>";

                                            }

                                        }
                                        ?>

                                        <option value='special_note'>Special</option>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group select-placeholder  " id="rel_id_wrapper">
                                    <label for="rel_id"><span class="rel_id_label"><?php echo _l('client')?></span></label>
                                    <div id="rel_id_select">
                                        <select name="rel_id" id="rel_id" class="ajax-search" data-width="100%"
                                                data-live-search="true" required
                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <?php echo render_textarea('description', 'note_description', '', [ 'rows' => 5 , 'required' => true ] ); ?>

                            </div>


                        </div>

                    </div>

                    <div class="panel-footer">

                        <button class="btn btn-primary ">

                            <?php echo _l('submit'); ?>

                        </button>

                    </div>

                </div>

            <?php echo form_close(); ?>

        </div>


        <div class="panel_s">

            <div class="panel-body">


                <div class="row">
                    <?php $this->load->view('filter' ); ?>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="table-responsive">

                            <table class="table table_notes">
                                <thead>
                                    <th>#</th>
                                    <th><?php echo _l('lead_add_edit_source')?></th>
                                    <th><?php echo _l('tasks_dt_name')?></th>
                                    <th><?php echo _l('client')?></th>
                                    <th><?php echo _l('clients_notes_table_description_heading')?></th>
                                    <th><?php echo _l('clients_notes_table_addedfrom_heading')?></th>
                                    <th><?php echo _l('clients_notes_table_dateadded_heading')?></th>
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

<div class="modal fade" id="note_module_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel">

                    <span class="edit-title"> <?php echo _l('contracts_notes_tab')?> </span>

                </h4>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12">

                        <div id="description_modal"></div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>

            </div>


        </div>

    </div>

</div>

<?php init_tail(); ?>

<script>


    (function($) {
        "use strict";

        $(function() {


            init_ajax_search('customer','#client_id.ajax-search');


            var serverParams = {};

            serverParams['from_date']   = '[name="from_date"]';
            serverParams['to_date']     = '[name="to_date"]';
            serverParams['source']      = '[name="source"]';
            serverParams['addedfrom']   = '[name="addedfrom"]';
            serverParams['client_id']   = '[name="client_id"]';

            initDataTable('.table_notes', admin_url + 'notes/notes/note_lists', false, false , serverParams , [ 0 , 'desc' ] );

            note_rel_id_select();

            $('#rel_type').on('change', function() {


                if ( $('#rel_type').val() == 'special_note' )
                {

                    $('#rel_id_wrapper').addClass('hide');

                    $('#rel_id').find('option:first').after('<option value="1">special</option>');

                    $('#rel_id').selectpicker('val',1);

                    $('#rel_id').selectpicker('refresh');


                }
                else
                {

                    $('#rel_id_wrapper').removeClass('hide');


                    var clonedSelect = $('#rel_id').html('').clone();

                    $('#rel_id').selectpicker('destroy').remove();

                    $('#rel_id_select').append(clonedSelect);

                    note_rel_id_select();

                    if ( $('#rel_type').val() != '')
                    {
                        $('#rel_id_wrapper').removeClass('hide');
                    }
                    else
                    {
                        $('#rel_id_wrapper').addClass('hide');
                    }

                    $('.rel_id_label').html($('#rel_type').find('option:selected').text());


                }



            });


        });


    })(jQuery);


    function note_rel_id_select()
    {
        var serverData = {};

        serverData.rel_id =  $('#rel_id').val();
        serverData.type = $('#rel_type').val();

        init_ajax_search($('#rel_type').val(), '#rel_id' , serverData);

    }

    function reload_the_table()
    {

        var $statementPeriod = $('#range');
        var value = $statementPeriod.selectpicker('val');
        var period = new Array();
        if (value != 'period') {
            period = JSON.parse(value);
        } else {
            period[0] = $('input[name="period-from"]').val();
            period[1] = $('input[name="period-to"]').val();

            if (period[0] == '' || period[1] == '') {
                return false;
            }
        }

        $('#from_date').val(period[0]);
        $('#to_date').val(period[1]);


        $('.table_notes').DataTable().ajax.reload();


    }


    function note_module_model_get_detail( record_id = 0 )
    {


        $.post( admin_url + "notes/note_module_model_get_detail" , { record_id : record_id } ).done(function ( response ){

            response = JSON.parse( response );

            if( response.detail )
            {

                $('#description_modal').html(  response.detail.description );

            }

            $('#note_module_modal').modal();

        })

    }

</script>


</body>

</html>
