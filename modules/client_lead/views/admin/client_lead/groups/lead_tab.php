<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<a href="#" data-toggle="modal" data-target="#lead_asiign_modal" class="btn btn-primary mbot20 pull-left">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?php echo _l('new_lead'); ?>
</a>

<style>
     .lead-count {
        display: inline-block;
        width: 15px;
        height: 15px;
        line-height: 15px;
        text-align: center;
        border-radius: 50%;
        color: white;
        background-color: palevioletred;
        font-size: 10px;
    }
    .lead-count-note {
        display: inline-block;
        width: 15px;
        height: 15px;
        line-height: 15px;
        text-align: center;
        border-radius: 50%;
        color: white;
        background-color: #2563EB;
        font-size: 10px;
    }
</style>

<?php
$CI = get_instance();
$statuses = $CI->leads_model->get_status();
$sources  = $CI->leads_model->get_source();
?>

<div class="modal fade lead-modal in" id="lead_asiign_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('new_lead'); ?></h4>
            </div>
            <div class="modal-body">
            <?php echo form_open(admin_url('client_lead/leads/custom_lead'), ['id' => 'lead_client_form']); ?>
            <div class="row">
                <div class="lead-view<?php if (!isset($lead)) {
                                            echo ' hide';
                                        } ?>" id="leadViewWrapper">
                    <div class="col-md-4 col-xs-12 lead-information-col">
                        <div class="lead-info-heading">
                            <h4>
                                <?php echo _l('lead_info'); ?>
                            </h4>
                        </div>

                        <dl>

                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_add_edit_name'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1 lead-name">
                                <?php echo (isset($lead) && $lead->name != '' ? $lead->name : '-') ?></dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_title'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->title != '' ? $lead->title : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_add_edit_email'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->email != '' ? '<a href="mailto:' . $lead->email . '">' . $lead->email . '</a>' : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_website'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->website != '' ? '<a href="' . maybe_add_http($lead->website) . '" target="_blank">' . $lead->website . '</a>' : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_add_edit_phonenumber'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->phonenumber != '' ? '<a href="tel:' . $lead->phonenumber . '">' . $lead->phonenumber . '</a>' : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_value'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->lead_value != 0 ? app_format_money($lead->lead_value, $base_currency->id) : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_company'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->company != '' ? $lead->company : '-') ?></dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_address'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->address != '' ? $lead->address : '-') ?></dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_city'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->city != '' ? $lead->city : '-') ?></dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_state'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->state != '' ? $lead->state : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_country'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->country != 0 ? get_country($lead->country)->short_name : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_zip'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->zip != '' ? $lead->zip : '-') ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4 col-xs-12 lead-information-col">
                        <div class="lead-info-heading">
                            <h4>
                                <?php echo _l('lead_general_info'); ?>
                            </h4>
                        </div>
                        <dl>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500 no-mtop">
                                <?php echo _l('lead_add_edit_status'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-2 mbot15">
                                <?php
                                if (isset($lead)) {
                                    echo $lead->status_name != '' ? ('<span class="lead-status-' . $lead->status . ' label' . (empty($lead->color) ? ' label-default' : '') . '" style="color:' . $lead->color . ';border:1px solid ' . adjust_hex_brightness($lead->color, 0.4) . ';background: ' . adjust_hex_brightness($lead->color, 0.04) . ';">' . $lead->status_name . '</span>') : '-';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_add_edit_source'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                                <?php echo (isset($lead) && $lead->source_name != '' ? $lead->source_name : '-') ?></dd>
                            <?php if (!is_language_disabled()) { ?>
                                <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('localization_default_language'); ?>
                                </dt>
                                <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                                    <?php echo (isset($lead) && $lead->default_language != '' ? ucfirst($lead->default_language) : _l('system_default_string')) ?>
                                </dd>
                            <?php } ?>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_add_edit_assigned'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                                <?php echo (isset($lead) && $lead->assigned != 0 ? get_staff_full_name($lead->assigned) : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('tags'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1 mbot10">
                                <?php
                                if (isset($lead)) {
                                    $tags = get_tags_in($lead->id, 'lead');
                                    if (count($tags) > 0) {
                                        echo render_tags($tags);
                                        echo '<div class="clearfix"></div>';
                                    } else {
                                        echo '-';
                                    }
                                }
                                ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('leads_dt_datecreated'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->dateadded != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . _dt($lead->dateadded) . '">' . time_ago($lead->dateadded) . '</span>' : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('leads_dt_last_contact'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->lastcontact != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . _dt($lead->lastcontact) . '">' . time_ago($lead->lastcontact) . '</span>' : '-') ?>
                            </dd>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_public'); ?>
                            </dt>
                            <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                                <?php if (isset($lead)) {
                                    if ($lead->is_public == 1) {
                                        echo _l('lead_is_public_yes');
                                    } else {
                                        echo _l('lead_is_public_no');
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </dd>
                            <?php if (isset($lead) && $lead->from_form_id != 0) { ?>
                                <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                    <?php echo _l('web_to_lead_form'); ?></dt>
                                <dd class="tw-text-neutral-900 tw-mt-1 mbot15"><?php echo $lead->form_data->name; ?></dd>
                            <?php } ?>
                        </dl>
                    </div>
                    <div class="col-md-4 col-xs-12 lead-information-col">
                        <?php if (total_rows(db_prefix() . 'customfields', ['fieldto' => 'leads', 'active' => 1]) > 0 && isset($lead)) { ?>
                            <div class="lead-info-heading">
                                <h4>
                                    <?php echo _l('custom_fields'); ?>
                                </h4>
                            </div>
                            <dl>
                                <?php
                                $custom_fields = get_custom_fields('leads');
                                foreach ($custom_fields as $field) {
                                    $value = get_custom_field_value($lead->id, $field['id'], 'leads'); ?>
                                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500 no-mtop">
                                        <?php echo $field['name']; ?></dt>
                                    <dd class="tw-text-neutral-900 tw-mt-1 tw-break-words"><?php echo ($value != '' ? $value : '-') ?>
                                    </dd>
                                <?php
                                } ?>
                            <?php } ?>
                            </dl>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <dl>
                            <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                                <?php echo _l('lead_description'); ?></dt>
                            <dd class="tw-text-neutral-900 tw-mt-1">
                                <?php echo (isset($lead) && $lead->description != '' ? $lead->description : '-') ?></dd>
                        </dl>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="lead-edit<?php if (isset($lead)) {
                                            echo ' hide';
                                        } ?>">
                    <div class="col-md-4">
                        <?php
                        $selected = '';
                        if (isset($lead)) {
                            $selected = $lead->status;
                        } elseif (isset($status_id)) {
                            $selected = $status_id;
                        }
                        echo render_leads_status_select($statuses, $selected, 'lead_add_edit_status');
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?php
                        $selected = (isset($lead) ? $lead->source : get_option('leads_default_source'));
                        echo render_leads_source_select($sources, $selected, 'lead_add_edit_source');
                        ?>
                    </div>

                    <div class="col-md-4">
                        <?php
                        $assigned_attrs = [];
                        $selected       = (isset($lead) ? $lead->assigned : get_staff_user_id());
                        if (
                            isset($lead)
                            && $lead->assigned == get_staff_user_id()
                            && $lead->addedfrom != get_staff_user_id()
                            && !is_admin($lead->assigned)
                            && !has_permission('leads', '', 'view')
                        ) {
                            $assigned_attrs['disabled'] = true;
                        }
                        echo render_select('assigned', $members, ['staffid', ['firstname', 'lastname']], 'lead_add_edit_assigned', $selected, $assigned_attrs); ?>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="mtop5 mbot10" />

                    <div class="col-md-12">
                        <div class="form-group no-mbot" id="inputTagsWrapper">
                            <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                <?php echo _l('tags'); ?></label>
                            <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($lead) ? prep_tags_input(get_tags_in($lead->id, 'lead')) : ''); ?>" data-role="tagsinput">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="no-mtop mbot15" />

                    <div class="col-md-12">
                        <?php $value = (isset($lead) ? $lead->inquiry_about : ''); ?>
                        <?php echo render_input('inquiry_about', 'inquiry_about', $value); ?>
                    </div>

                    <div class="col-md-6">
                        <?php $value = (isset($lead) ? $lead->name : ''); ?>
                        <?php echo render_input('name', 'lead_add_edit_name', $value); ?>
                        <?php $value = (isset($lead) ? $lead->title : ''); ?>
                        <?php echo render_input('title', 'lead_title', $value); ?>
                        <?php $value = (isset($lead) ? $lead->email : ''); ?>
                        <?php echo render_input('email', 'lead_add_edit_email', $value); ?>
                        <?php if ((isset($lead) && empty($lead->website)) || !isset($lead)) {
                            $value = (isset($lead) ? $lead->website : '');
                            echo render_input('website', 'lead_website', $value);
                        } else { ?>
                            <div class="form-group">
                                <label for="website"><?php echo _l('lead_website'); ?></label>
                                <div class="input-group">
                                    <input type="text" name="website" id="website" value="<?php echo $lead->website; ?>" class="form-control">
                                    <div class="input-group-addon">
                                        <span>
                                            <a href="<?php echo maybe_add_http($lead->website); ?>" target="_blank" tabindex="-1">
                                                <i class="fa fa-globe"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        $value = (isset($lead) ? $lead->phonenumber : ''); ?>
                        <?php echo render_input('phonenumber', 'lead_add_edit_phonenumber', $value); ?>
                        <div class="form-group">
                            <label for="lead_value"><?php echo _l('lead_value'); ?></label>
                            <div data-toggle="tooltip" title="<?php echo _l('lead_value_tooltip'); ?>">
                                <input type="number" class="form-control" name="lead_value" value="">

                            </div>
                            </label>
                        </div>
                        <?php $value = (isset($lead) ? $lead->company : ''); ?>
                        <?php echo render_input('company', 'lead_company', $value, '', ['readonly' => true]); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = (isset($lead) ? $lead->address : ''); ?>
                        <?php echo render_textarea('address', 'lead_address', $value, ['rows' => 1, 'style' => 'height:36px;font-size:100%;']); ?>
                        <?php $value = (isset($lead) ? $lead->city : ''); ?>
                        <?php echo render_input('city', 'lead_city', $value); ?>
                        <?php $value = (isset($lead) ? $lead->state : ''); ?>
                        <?php echo render_input('state', 'lead_state', $value); ?>
                        <?php
                        $countries                = get_all_countries();
                        $customer_default_country = get_option('customer_default_country');
                        $selected                 = (isset($lead) ? $lead->country : $customer_default_country);
                        echo render_select('country', $countries, ['country_id', ['short_name']], 'lead_country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
                        ?>
                        <?php $value = (isset($lead) ? $lead->zip : ''); ?>
                        <?php echo render_input('zip', 'lead_zip', $value); ?>
                        <?php if (!is_language_disabled()) { ?>
                            <div class="form-group">
                                <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?></label>
                                <select name="default_language" data-live-search="true" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""><?php echo _l('system_default_string'); ?></option>
                                    <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                                        $selected = '';
                                        if (isset($lead)) {
                                            if ($lead->default_language == $availableLanguage) {
                                                $selected = 'selected';
                                            }
                                        } ?>
                                        <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>>
                                            <?php echo ucfirst($availableLanguage); ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $CI = &get_instance();
                        $customer = $CI->db->get(db_prefix() . 'clients')->result_array();
                        // $selected =  isset($lead->existing_client_id) ? $lead->existing_client_id : '';
                        echo render_select('existing_client_id', $customer, ['userid', 'company'], 'existing_client_id', $client->userid); ?>
                    </div>
                    <div class="col-md-12">
                        <?php $value = (isset($lead) ? $lead->description : ''); ?>
                        <?php echo render_textarea('description', 'lead_description', $value); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (!isset($lead)) { ?>
                                    <div class="lead-select-date-contacted hide">
                                        <?php echo render_datetime_input('custom_contact_date', 'lead_add_edit_datecontacted', '', ['data-date-end-date' => date('Y-m-d')]); ?>
                                    </div>
                                <?php } else { ?>
                                    <?php echo render_datetime_input('lastcontact', 'leads_dt_last_contact', _dt($lead->lastcontact), ['data-date-end-date' => date('Y-m-d')]); ?>
                                <?php } ?>
                                <div class="checkbox-inline checkbox checkbox-primary<?php if (isset($lead)) {
                                                                                            echo ' hide';
                                                                                        } ?><?php if (isset($lead) && (is_lead_creator($lead->id) || has_permission('leads', '', 'edit'))) {
                                                                                                echo ' lead-edit';
                                                                                            } ?>">
                                    <input type="checkbox" name="is_public" <?php if (isset($lead)) {
                                                                                if ($lead->is_public == 1) {
                                                                                    echo 'checked';
                                                                                }
                                                                            }; ?> id="lead_public">
                                    <label for="lead_public"><?php echo _l('lead_public'); ?></label>
                                </div>
                                <?php if (!isset($lead)) { ?>
                                    <div class="checkbox-inline checkbox checkbox-primary">
                                        <input type="checkbox" name="contacted_today" id="contacted_today" checked>
                                        <label for="contacted_today"><?php echo _l('lead_add_edit_contacted_today'); ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mtop15">
                        <?php $rel_id = (isset($lead) ? $lead->id : false); ?>
                        <?php echo render_custom_fields('leads', $rel_id); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php if (isset($lead)) { ?>
                <div class="lead-latest-activity tw-mb-3 lead-view">
                    <div class="lead-info-heading">
                        <h4><?php echo _l('lead_latest_activity'); ?></h4>
                    </div>
                    <div id="lead-latest-activity" class="pleft5"></div>
                </div>
            <?php } ?>
            <div class="lead-edit<?php echo isset($lead) ? ' hide' : ''; ?>">
                <hr class="-tw-mx-4 tw-border-neutral-200" />
                <button type="submit" class="btn btn-primary pull-right lead-save-btn" id="lead-form-submit">
                    <?php echo _l('submit'); ?>
                </button>
                <button type=" button" class="btn btn-default pull-right mright5" data-dismiss="modal">
                    <?php echo _l('close'); ?>
                </button>
            </div>
            <div class="clearfix"></div>
            <?php echo form_close(); ?>
        </div>
        </div>
    </div>
</div>


<div class="col-md-12">

    <?php
    $table_data = [];
    $_table_data = [
        '<span class="hide"> - </span>
                                ',
        [
            'name' => _l('the_number_sign'),
            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
        ],
        [
            'name' => _l('leads_dt_name'),
            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-name'],
        ],
    ];
    if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
        $_table_data[] = [
            'name' => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
            'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
        ];
    }
    $_table_data[] = [
        'name' => _l('lead_inquiry_about'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-inquiry_about'],
    ];
    
    $_table_data[] = [
        'name' => _l('leads_dt_last_note'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-leads_dt_last_note'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_last_note_activity'),
        'th_attrs' => ['class' => 'not_visible', 'id' => 'th-leads_dt_last_note_activity'],
    ];
     $_table_data[] = [
        'name' => _l('leads_dt_last_contact'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-last-contact'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_last_description_activity'),
        'th_attrs' => ['class' => 'not_visible', 'id' => 'th-leads_dt_last_description_activity'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_email'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-email'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_phonenumber'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_lead_value'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-lead-value'],
    ];
    $_table_data[] = [
        'name' => _l('tags'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-tags'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_assigned'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-assigned'],
    ];
    $_table_data[] = [
        'name' => _l('leads_dt_status'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-status'],
    ];
    $_table_data[] = [
        'name' => _l('leads_source'),
        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-source'],
    ];
   
    $_table_data[] = [
        'name' => _l('leads_dt_datecreated'),
        'th_attrs' => ['class' => 'date-created toggleable', 'id' => 'th-date-created'],
    ];
    foreach ($_table_data as $_t) {
        array_push($table_data, $_t);
    }
    $custom_fields = get_custom_fields('leads', ['show_on_table' => 1]);
    foreach ($custom_fields as $field) {
        array_push($table_data, [
            'name' => $field['name'],
            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
        ]);
    }
    $table_data = hooks()->apply_filters('leads_table_columns', $table_data);
    ?>
    <div class="panel-table-full">
        <?php
        render_datatable(
            $table_data,
            'leads_client_tab',
            ['customizable-table number-index-2'],
            [
                'id'                         => 'table_leads_client_tab',
                'data-last-order-identifier' => 'leads',
                'data-default-order'         => get_table_last_order('leads'),
            ]
        );
        ?>
    </div>
</div>
