<input type="hidden" name="from_date" id="from_date" value="<?php echo _d(date('Y-m-d', strtotime('monday this week')));?>">
<input type="hidden" name="to_date" id="to_date" value="<?php echo _d(date('Y-m-d', strtotime('sunday this week')));?>">

<div class="col-md-3">
    <div class="form-group select-placeholder">
        <select
            class="selectpicker"
            name="range"
            id="range"
            data-width="100%" onchange="reload_the_table()" >
            <?php $current_year = date('Y')+10?>
            <option value='<?php echo json_encode(
                array(
                    _d(date('1950-m-d')),
                    _d(date($current_year.'-m-d'))
                )); ?>' > <?php echo _l('all')?></option>


            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-d')),
                    _d(date('Y-m-d'))
                )); ?>'>
                <?php echo _l('today'); ?>
            </option>
            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-d', strtotime('monday this week'))),
                    _d(date('Y-m-d', strtotime('sunday this week')))
                )); ?>' selected>
                <?php echo _l('this_week'); ?>
            </option>
            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-01')),
                    _d(date('Y-m-t'))
                )); ?>'>
                <?php echo _l('this_month'); ?>
            </option>
            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-01', strtotime("-1 MONTH"))),
                    _d(date('Y-m-t', strtotime('-1 MONTH')))
                )); ?>'>
                <?php echo _l('last_month'); ?>
            </option>
            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-d',strtotime(date('Y-01-01')))),
                    _d(date('Y-m-d',strtotime(date('Y-12-31'))))
                )); ?>'>
                <?php echo _l('this_year'); ?>
            </option>
            <option value='<?php echo json_encode(
                array(
                    _d(date('Y-m-d',strtotime(date(date('Y',strtotime('last year')).'-01-01')))),
                    _d(date('Y-m-d',strtotime(date(date('Y',strtotime('last year')). '-12-31'))))
                )); ?>'>
                <?php echo _l('last_year'); ?>
            </option>
            <option value="period"><?php echo _l('period_datepicker'); ?></option>
        </select>
    </div>
</div>


<div class="col-md-3">
    <div class="form-group select-placeholder">
        <select class="selectpicker" name="source" id="source" data-width="100%"  onchange="reload_the_table()" >

            <option value=''><?php echo _l('lead_add_edit_source')?></option>

            <?php if (!empty($rel_type)){
                foreach ($rel_type as $key => $type){
                    echo "<option value='$key'> "._l($type)." </option>";
                }
            }

            ?>

            <option value='special_note'>Special Note</option>

        </select>
    </div>
</div>


<div class="col-md-3">

    <select class="selectpicker" name="addedfrom" id="addedfrom" data-width="100%" onchange="reload_the_table()"
            data-live-search="true"
            data-none-selected-text="<?php echo _l('clients_notes_table_addedfrom_heading'); ?>">

        <option></option>
        <?php if (!empty($staffs)){
            foreach ($staffs as $key => $staff){
                echo "<option value='$staff->staffid'>$staff->fullname</option>";
            }
        }

        ?>

    </select>


</div>


<div class="col-md-3">

    <div class="form-group select-placeholder">

        <select id="client_id" name="client_id" required data-live-search="true" data-width="100%"

                class="ajax-search"  onchange="reload_the_table()"

                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

        </select>

    </div>

</div>


<div class="row mtop15">
    <div class="col-md-12 period hide">
        <?php echo render_date_input('period-from','','',array('onchange'=>isset($onChange) ? $onChange : '')); ?>
    </div>
    <div class="col-md-12 period hide">
        <?php echo render_date_input('period-to','','',array('onchange'=>isset($onChange) ? $onChange : '')); ?>
    </div>
</div>
