<?php

use app\services\imap\Imap;
use app\services\LeadProfileBadges;
use app\services\leads\LeadsKanban;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\MailboxDoesNotExistException;

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('client_lead_payments_model');
        $this->load->model('payments_model');
    }

    /* List all leads */
    // public function index($id = '')
    // {
    //     close_setup_menu();

    //     if (!is_staff_member()) {
    //         access_denied('Leads');
    //     }

    //     $data['switch_kanban'] = true;

    //     if ($this->session->userdata('leads_kanban_view') == 'true') {
    //         $data['switch_kanban'] = false;
    //         $data['bodyclass']     = 'kan-ban-body';
    //     }

    //     $data['staff'] = $this->staff_model->get('', ['active' => 1]);
    //     if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
    //         $this->load->model('gdpr_model');
    //         $data['consent_purposes'] = $this->gdpr_model->get_consent_purposes();
    //     }
    //     $data['summary']  = get_leads_summary();
    //     $data['statuses'] = $this->leads_model->get_status();
    //     $data['sources']  = $this->leads_model->get_source();
    //     $data['title']    = _l('leads');
    //     // in case accesed the url leads/index/ directly with id - used in search
    //     $data['leadid']   = $id;
    //     $data['isKanBan'] = $this->session->has_userdata('leads_kanban_view') &&
    //     $this->session->userdata('leads_kanban_view') == 'true';
    //     $this->load->view('leads/manage_leads', $data);
    // }

      /* List all leads */
      public function index($id = '')
      {
          close_setup_menu();
  
          if (!is_staff_member()) {
              access_denied('Leads');
          }
  
          $data['switch_kanban'] = true;
  
          if ($this->session->userdata('leads_kanban_view') == 'true') {
              $data['switch_kanban'] = false;
              $data['bodyclass']     = 'kan-ban-body';
          }
  
          $data['staff'] = $this->staff_model->get('', ['active' => 1]);
          if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
              $this->load->model('gdpr_model');
              $data['consent_purposes'] = $this->gdpr_model->get_consent_purposes();
          }
          $data['summary']  = get_leads_summary();
          $data['statuses'] = $this->leads_model->get_status();
          $data['sources']  = $this->leads_model->get_source();
          $data['title']    = _l('leads');
          $data['table'] = App_table::find('table-client-leads');
          $data['leadid']   = $id;
          $data['isKanBan'] = $this->session->has_userdata('leads_kanban_view') && $this->session->userdata('leads_kanban_view') == 'true';
          
          $this->load->view('leads/manage_leads', $data);
      }

    public function custom_lead($id = "") {
        
        if (!is_staff_member() || ($id != '' && !$this->leads_model->staff_can_access_lead($id))) {
            ajax_access_denied();
        }

        if ($this->input->post()) {
            $id      = $this->leads_model->add($this->input->post());

            $client_id = $this->input->post('existing_client_id');

            if($id) {
                set_alert('success', _l('added_successfully', _l('lead')));
                redirect(admin_url('clients/client/'.$client_id.'?group=client_lead'));
            }
            redirect(admin_url('clients/client/'.$client_id.'?group=client_lead'));
        }
    }

    public function table()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }
        // $this->app->get_table_data(module_views_path('client_lead', 'tables/leads'));
        App_table::find('table-client-leads')->output();
    }

    public function kanban()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        $data['statuses']      = $this->leads_model->get_status();
        $data['base_currency'] = get_base_currency();
        $data['summary']       = get_leads_summary();

        echo $this->load->view('leads/kan-ban', $data, true);
    }

    /* Add or update lead */
    public function lead($id = '')
    {
        if (!is_staff_member() || ($id != '' && !$this->leads_model->staff_can_access_lead($id))) {
            ajax_access_denied();
        }

        if ($this->input->post()) {
            if ($id == '') {
                $id      = $this->leads_model->add($this->input->post());
                $message = $id ? _l('added_successfully', _l('lead')) : '';

                echo json_encode([
                    'success'  => $id ? true : false,
                    'id'       => $id,
                    'message'  => $message,
                    'leadView' => $id ? $this->_get_lead_data($id) : [],
                ]);
            } else {
                $emailOriginal   = $this->db->select('email')->where('id', $id)->get(db_prefix() . 'leads')->row()->email;
                $proposalWarning = false;
                $message         = '';
                $success         = $this->leads_model->update($this->input->post(), $id);

                if ($success) {
                    $emailNow = $this->db->select('email')->where('id', $id)->get(db_prefix() . 'leads')->row()->email;

                    $proposalWarning = (total_rows(db_prefix() . 'proposals', [
                        'rel_type' => 'lead',
                        'rel_id'   => $id, ]) > 0 && ($emailOriginal != $emailNow) && $emailNow != '') ? true : false;

                    $message = _l('updated_successfully', _l('lead'));
                }
                echo json_encode([
                    'success'          => $success,
                    'message'          => $message,
                    'id'               => $id,
                    'proposal_warning' => $proposalWarning,
                    'leadView'         => $this->_get_lead_data($id),
                ]);
            }
            die;
        }

        echo json_encode([
            'leadView' => $this->_get_lead_data($id),
        ]);
    }

    private function _get_lead_data($id = '')
    {
        $reminder_data         = '';
        $data['lead_locked']   = false;
        $data['openEdit']      = $this->input->get('edit') ? true : false;
        $data['members']       = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);
        $data['status_id']     = $this->input->get('status_id') ? $this->input->get('status_id') : get_option('leads_default_status');
        $data['base_currency'] = get_base_currency();

        if (is_numeric($id)) {
            $leadWhere = (has_permission('leads', '', 'view') ? [] : '(assigned = ' . get_staff_user_id() . ' OR addedfrom=' . get_staff_user_id() . ' OR is_public=1)');

            $lead = $this->leads_model->get($id, $leadWhere);

            if (!$lead) {
                header('HTTP/1.0 404 Not Found');
                echo _l('lead_not_found');
                die;
            }

            if (total_rows(db_prefix() . 'clients', ['leadid' => $id ]) > 0) {
                $data['lead_locked'] = ((!is_admin() && get_option('lead_lock_after_convert_to_customer') == 1) ? true : false);
            }

            $reminder_data = $this->load->view('includes/modals/reminder', [
                    'id'             => $lead->id,
                    'name'           => 'lead',
                    'members'        => $data['members'],
                    'reminder_title' => _l('lead_set_reminder_title'),
                ], true);

            $data['lead']          = $lead;
            $data['mail_activity'] = $this->leads_model->get_mail_activity($id);
            $data['notes']         = $this->misc_model->get_notes($id, 'lead');
            $data['activity_log']  = $this->leads_model->get_lead_activity_log($id);

            if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
                $this->load->model('gdpr_model');
                $data['purposes'] = $this->gdpr_model->get_consent_purposes($lead->id, 'lead');
                $data['consents'] = $this->gdpr_model->get_consents(['lead_id' => $lead->id]);
            }

            $leadProfileBadges         = new LeadProfileBadges($id);
            $data['total_reminders']   = $leadProfileBadges->getCount('reminders');
            $data['total_notes']       = $leadProfileBadges->getCount('notes');
            $data['total_attachments'] = $leadProfileBadges->getCount('attachments');
            $data['total_tasks']       = $leadProfileBadges->getCount('tasks');
            $data['total_proposals']   = $leadProfileBadges->getCount('proposals');
        }


        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();

        $data = hooks()->apply_filters('lead_view_data', $data);

        return [
            'data'          => $this->load->view('leads/lead', $data, true),
            'reminder_data' => $reminder_data,
        ];
    }

    public function leads_kanban_load_more()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        $status = $this->input->get('status');
        $page   = $this->input->get('page');

        $this->db->where('id', $status);
        $status = $this->db->get(db_prefix() . 'leads_status')->row_array();

        $leads = (new LeadsKanban($status['id']))
        ->search($this->input->get('search'))
        ->sortBy(
            $this->input->get('sort_by'),
            $this->input->get('sort')
        )
        ->page($page)->get();

        foreach ($leads as $lead) {
            $this->load->view('leads/_kan_ban_card', [
                'lead'   => $lead,
                'status' => $status,
            ]);
        }
    }

    public function switch_kanban($set = 0)
    {
        if ($set == 1) {
            $set = 'true';
        } else {
            $set = 'false';
        }
        $this->session->set_userdata([
            'leads_kanban_view' => $set,
        ]);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function export($id)
    {
        if (is_admin()) {
            $this->load->library('gdpr/gdpr_lead');
            $this->gdpr_lead->export($id);
        }
    }

    /* Delete lead from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('client_lead/leads'));
        }

        if (!has_permission('leads', '', 'delete')) {
            access_denied('Delete Lead');
        }

        $response = $this->leads_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_lowercase')));
        } elseif ($response === true) {
            set_alert('success', _l('deleted', _l('lead')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_lowercase')));
        }

        $ref = $_SERVER['HTTP_REFERER'];

        // if user access leads/inded/ID to prevent redirecting on the same url because will throw 404
        if (!$ref || strpos($ref, 'index/' . $id) !== false) {
            redirect(admin_url('client_lead/leads'));
        }

        redirect($ref);
    }

    public function mark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        $message = '';
        $success = $this->leads_model->mark_as_lost($id);
        if ($success) {
            $message = _l('lead_marked_as_lost');
        }
        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'leadView' => $this->_get_lead_data($id),
            'id'       => $id,
        ]);
    }

    public function unmark_as_lost($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_lost($id);
        if ($success) {
            $message = _l('lead_unmarked_as_lost');
        }
        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'leadView' => $this->_get_lead_data($id),
            'id'       => $id,
        ]);
    }

    public function mark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        $message = '';
        $success = $this->leads_model->mark_as_junk($id);
        if ($success) {
            $message = _l('lead_marked_as_junk');
        }
        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'leadView' => $this->_get_lead_data($id),
            'id'       => $id,
        ]);
    }

    public function unmark_as_junk($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        $message = '';
        $success = $this->leads_model->unmark_as_junk($id);
        if ($success) {
            $message = _l('lead_unmarked_as_junk');
        }
        echo json_encode([
            'success'  => $success,
            'message'  => $message,
            'leadView' => $this->_get_lead_data($id),
            'id'       => $id,
        ]);
    }

    public function add_activity()
    {
        $leadid = $this->input->post('leadid');
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($leadid)) {
            ajax_access_denied();
        }
        if ($this->input->post()) {
            $message = $this->input->post('activity');
            $aId     = $this->leads_model->log_lead_activity($leadid, $message);
            if ($aId) {
                $this->db->where('id', $aId);
                $this->db->update(db_prefix() . 'lead_activity_log', ['custom_activity' => 1]);
            }
            echo json_encode(['leadView' => $this->_get_lead_data($leadid), 'id' => $leadid]);
        }
    }

    public function get_convert_data($id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }
        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
            $this->load->model('gdpr_model');
            $data['purposes'] = $this->gdpr_model->get_consent_purposes($id, 'lead');
        }
        $data['lead'] = $this->leads_model->get($id);
        $this->load->view('leads/convert_to_customer', $data);
    }

    /**
     * Convert lead to client
     * @since  version 1.0.1
     * @return mixed
     */
    public function convert_to_customer()
    {
        if (!is_staff_member()) {
            access_denied('Lead Convert to Customer');
        }

        if ($this->input->post()) {
            $default_country  = get_option('customer_default_country');
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            $original_lead_email = $data['original_lead_email'];
            unset($data['original_lead_email']);

            if (isset($data['transfer_notes'])) {
                $notes = $this->misc_model->get_notes($data['leadid'], 'lead');
                unset($data['transfer_notes']);
            }

            if (isset($data['transfer_consent'])) {
                $this->load->model('gdpr_model');
                $consents = $this->gdpr_model->get_consents(['lead_id' => $data['leadid']]);
                unset($data['transfer_consent']);
            }

            if (isset($data['merge_db_fields'])) {
                $merge_db_fields = $data['merge_db_fields'];
                unset($data['merge_db_fields']);
            }

            if (isset($data['merge_db_contact_fields'])) {
                $merge_db_contact_fields = $data['merge_db_contact_fields'];
                unset($data['merge_db_contact_fields']);
            }

            if (isset($data['include_leads_custom_fields'])) {
                $include_leads_custom_fields = $data['include_leads_custom_fields'];
                unset($data['include_leads_custom_fields']);
            }

            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }

            $data['billing_street']  = $data['address'];
            $data['billing_city']    = $data['city'];
            $data['billing_state']   = $data['state'];
            $data['billing_zip']     = $data['zip'];
            $data['billing_country'] = $data['country'];

            $data['is_primary'] = 1;
            $id                 = $this->clients_model->add($data, true);
            if ($id) {
                $primary_contact_id = get_primary_contact_user_id($id);

                if (isset($notes)) {
                    foreach ($notes as $note) {
                        $this->db->insert(db_prefix() . 'notes', [
                            'rel_id'         => $id,
                            'rel_type'       => 'customer',
                            'dateadded'      => $note['dateadded'],
                            'addedfrom'      => $note['addedfrom'],
                            'description'    => $note['description'],
                            'date_contacted' => $note['date_contacted'],
                            ]);
                    }
                }
                if (isset($consents)) {
                    foreach ($consents as $consent) {
                        unset($consent['id']);
                        unset($consent['purpose_name']);
                        $consent['lead_id']    = 0;
                        $consent['contact_id'] = $primary_contact_id;
                        $this->gdpr_model->add_consent($consent);
                    }
                }
                if (!has_permission('customers', '', 'view') && get_option('auto_assign_customer_admin_after_lead_convert') == 1) {
                    $this->db->insert(db_prefix() . 'customer_admins', [
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id'   => $id,
                        'staff_id'      => get_staff_user_id(),
                    ]);
                }
                $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted', false, serialize([
                    get_staff_full_name(),
                ]));
                $default_status = $this->leads_model->get_status('', [
                    'isdefault' => 1,
                ]);
                $this->db->where('id', $data['leadid']);
                $this->db->update(db_prefix() . 'leads', [
                    'date_converted' => date('Y-m-d H:i:s'),
                    'status'         => $default_status[0]['id'],
                    'junk'           => 0,
                    'lost'           => 0,
                ]);
                // Check if lead email is different then client email
                $contact = $this->clients_model->get_contact(get_primary_contact_user_id($id));
                if ($contact->email != $original_lead_email) {
                    if ($original_lead_email != '') {
                        $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted_email', false, serialize([
                            $original_lead_email,
                            $contact->email,
                        ]));
                    }
                }
                if (isset($include_leads_custom_fields)) {
                    foreach ($include_leads_custom_fields as $fieldid => $value) {
                        // checked don't merge
                        if ($value == 5) {
                            continue;
                        }
                        // get the value of this leads custom fiel
                        $this->db->where('relid', $data['leadid']);
                        $this->db->where('fieldto', 'leads');
                        $this->db->where('fieldid', $fieldid);
                        $lead_custom_field_value = $this->db->get(db_prefix() . 'customfieldsvalues')->row()->value;
                        // Is custom field for contact ot customer
                        if ($value == 1 || $value == 4) {
                            if ($value == 4) {
                                $field_to = 'contacts';
                            } else {
                                $field_to = 'customers';
                            }
                            $this->db->where('id', $fieldid);
                            $field = $this->db->get(db_prefix() . 'customfields')->row();
                            // check if this field exists for custom fields
                            $this->db->where('fieldto', $field_to);
                            $this->db->where('name', $field->name);
                            $exists               = $this->db->get(db_prefix() . 'customfields')->row();
                            $copy_custom_field_id = null;
                            if ($exists) {
                                $copy_custom_field_id = $exists->id;
                            } else {
                                // there is no name with the same custom field for leads at the custom side create the custom field now
                                $this->db->insert(db_prefix() . 'customfields', [
                                    'fieldto'        => $field_to,
                                    'name'           => $field->name,
                                    'required'       => $field->required,
                                    'type'           => $field->type,
                                    'options'        => $field->options,
                                    'display_inline' => $field->display_inline,
                                    'field_order'    => $field->field_order,
                                    'slug'           => slug_it($field_to . '_' . $field->name, [
                                        'separator' => '_',
                                    ]),
                                    'active'        => $field->active,
                                    'only_admin'    => $field->only_admin,
                                    'show_on_table' => $field->show_on_table,
                                    'bs_column'     => $field->bs_column,
                                ]);
                                $new_customer_field_id = $this->db->insert_id();
                                if ($new_customer_field_id) {
                                    $copy_custom_field_id = $new_customer_field_id;
                                }
                            }
                            if ($copy_custom_field_id != null) {
                                $insert_to_custom_field_id = $id;
                                if ($value == 4) {
                                    $insert_to_custom_field_id = get_primary_contact_user_id($id);
                                }
                                $this->db->insert(db_prefix() . 'customfieldsvalues', [
                                    'relid'   => $insert_to_custom_field_id,
                                    'fieldid' => $copy_custom_field_id,
                                    'fieldto' => $field_to,
                                    'value'   => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 2) {
                            if (isset($merge_db_fields)) {
                                $db_field = $merge_db_fields[$fieldid];
                                // in case user don't select anything from the db fields
                                if ($db_field == '') {
                                    continue;
                                }
                                if ($db_field == 'country' || $db_field == 'shipping_country' || $db_field == 'billing_country') {
                                    $this->db->where('iso2', $lead_custom_field_value);
                                    $this->db->or_where('short_name', $lead_custom_field_value);
                                    $this->db->or_like('long_name', $lead_custom_field_value);
                                    $country = $this->db->get(db_prefix() . 'countries')->row();
                                    if ($country) {
                                        $lead_custom_field_value = $country->country_id;
                                    } else {
                                        $lead_custom_field_value = 0;
                                    }
                                }
                                $this->db->where('userid', $id);
                                $this->db->update(db_prefix() . 'clients', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 3) {
                            if (isset($merge_db_contact_fields)) {
                                $db_field = $merge_db_contact_fields[$fieldid];
                                if ($db_field == '') {
                                    continue;
                                }
                                $this->db->where('id', $primary_contact_id);
                                $this->db->update(db_prefix() . 'contacts', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        }
                    }
                }
                // set the lead to status client in case is not status client
                $this->db->where('isdefault', 1);
                $status_client_id = $this->db->get(db_prefix() . 'leads_status')->row()->id;
                $this->db->where('id', $data['leadid']);
                $this->db->update(db_prefix() . 'leads', [
                    'status' => $status_client_id,
                ]);

                set_alert('success', _l('lead_to_client_base_converted_success'));

                if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                    // When lead is deleted
                    // move all proposals to the actual customer record
                    $this->db->where('rel_id', $data['leadid']);
                    $this->db->where('rel_type', 'lead');
                    $this->db->update('proposals', [
                        'rel_id'   => $id,
                        'rel_type' => 'customer',
                    ]);

                    $this->leads_model->delete($data['leadid']);

                    $this->db->where('userid', $id);
                    $this->db->update(db_prefix() . 'clients', ['leadid' => null]);
                }

                log_activity('Created Lead Client Profile [LeadID: ' . $data['leadid'] . ', ClientID: ' . $id . ']');
                hooks()->do_action('lead_converted_to_customer', ['lead_id' => $data['leadid'], 'customer_id' => $id]);
                redirect(admin_url('clients/client/' . $id));
            }
        }
    }

    /* Used in kanban when dragging and mark as */
    public function update_lead_status()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->leads_model->update_lead_status($this->input->post());
        }
    }

    public function update_status_order()
    {
        if ($post_data = $this->input->post()) {
            $this->leads_model->update_status_order($post_data);
        }
    }

    public function add_lead_attachment()
    {
        $id       = $this->input->post('id');
        $lastFile = $this->input->post('last_file');

        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($id)) {
            ajax_access_denied();
        }

        handle_lead_attachments($id);
        echo json_encode(['leadView' => $lastFile ? $this->_get_lead_data($id) : [], 'id' => $id]);
    }

    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->leads_model->add_attachment_to_database(
                $this->input->post('lead_id'),
                $this->input->post('files'),
                $this->input->post('external')
            );
        }
    }

    public function delete_attachment($id, $lead_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($lead_id)) {
            ajax_access_denied();
        }
        echo json_encode([
            'success'  => $this->leads_model->delete_lead_attachment($id),
            'leadView' => $this->_get_lead_data($lead_id),
            'id'       => $lead_id,
        ]);
    }

    public function delete_note($id, $lead_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($lead_id)) {
            ajax_access_denied();
        }
        echo json_encode([
            'success'  => $this->misc_model->delete_note($id),
            'leadView' => $this->_get_lead_data($lead_id),
            'id'       => $lead_id,
        ]);
    }

    public function update_all_proposal_emails_linked_to_lead($id)
    {
        $success = false;
        $email   = '';
        if ($this->input->post('update')) {
            $this->load->model('proposals_model');

            $this->db->select('email');
            $this->db->where('id', $id);
            $email = $this->db->get(db_prefix() . 'leads')->row()->email;

            $proposals = $this->proposals_model->get('', [
                'rel_type' => 'lead',
                'rel_id'   => $id,
            ]);
            $affected_rows = 0;

            foreach ($proposals as $proposal) {
                $this->db->where('id', $proposal['id']);
                $this->db->update(db_prefix() . 'proposals', [
                    'email' => $email,
                ]);
                if ($this->db->affected_rows() > 0) {
                    $affected_rows++;
                }
            }

            if ($affected_rows > 0) {
                $success = true;
            }
        }

        echo json_encode([
            'success' => $success,
            'message' => _l('proposals_emails_updated', [
                _l('lead_lowercase'),
                $email,
            ]),
        ]);
    }

    public function save_form_data()
    {
        $data = $this->input->post();

        // form data should be always sent to the request and never should be empty
        // this code is added to prevent losing the old form in case any errors
        if (!isset($data['formData']) || isset($data['formData']) && !$data['formData']) {
            echo json_encode([
                'success' => false,
            ]);
            die;
        }

        // If user paste with styling eq from some editor word and the Codeigniter XSS feature remove and apply xss=remove, may break the json.
        $data['formData'] = preg_replace('/=\\\\/m', "=''", $data['formData']);

        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix() . 'web_to_lead', [
            'form_data' => $data['formData'],
        ]);
        if ($this->db->affected_rows() > 0) {
            echo json_encode([
                'success' => true,
                'message' => _l('updated_successfully', _l('web_to_lead_form')),
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

    public function form($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->leads_model->add_form($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('web_to_lead_form')));
                    redirect(admin_url('client_lead/leads/form/' . $id));
                }
            } else {
                $success = $this->leads_model->update_form($id, $this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('web_to_lead_form')));
                }
                redirect(admin_url('client_lead/leads/form/' . $id));
            }
        }

        $data['formData'] = [];
        $custom_fields    = get_custom_fields('leads', 'type != "link"');

        $cfields       = format_external_form_custom_fields($custom_fields);
        $data['title'] = _l('web_to_lead');

        if ($id != '') {
            $data['form'] = $this->leads_model->get_form([
                'id' => $id,
            ]);
            $data['title']    = $data['form']->name . ' - ' . _l('web_to_lead_form');
            $data['formData'] = $data['form']->form_data;
        }

        $this->load->model('roles_model');
        $data['roles']    = $this->roles_model->get();
        $data['sources']  = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();

        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);

        $data['languages'] = $this->app->get_available_languages();
        $data['cfields']   = $cfields;

        $db_fields = [];
        $fields    = [
            'name',
            'title',
            'email',
            'phonenumber',
            'lead_value',
            'company',
            'address',
            'city',
            'state',
            'country',
            'zip',
            'description',
            'website',
        ];

        $fields = hooks()->apply_filters('lead_form_available_database_fields', $fields);

        $className = 'form-control';

        foreach ($fields as $f) {
            $_field_object = new stdClass();
            $type          = 'text';
            $subtype       = '';
            if ($f == 'email') {
                $subtype = 'email';
            } elseif ($f == 'description' || $f == 'address') {
                $type = 'textarea';
            } elseif ($f == 'country') {
                $type = 'select';
            }

            if ($f == 'name') {
                $label = _l('lead_add_edit_name');
            } elseif ($f == 'email') {
                $label = _l('lead_add_edit_email');
            } elseif ($f == 'phonenumber') {
                $label = _l('lead_add_edit_phonenumber');
            } elseif ($f == 'lead_value') {
                $label = _l('lead_add_edit_lead_value');
                $type  = 'number';
            } else {
                $label = _l('lead_' . $f);
            }

            $field_array = [
                'subtype'   => $subtype,
                'type'      => $type,
                'label'     => $label,
                'className' => $className,
                'name'      => $f,
            ];

            if ($f == 'country') {
                $field_array['values'] = [];

                $field_array['values'][] = [
                    'label'    => '',
                    'value'    => '',
                    'selected' => false,
                ];

                $countries = get_all_countries();
                foreach ($countries as $country) {
                    $selected = false;
                    if (get_option('customer_default_country') == $country['country_id']) {
                        $selected = true;
                    }
                    array_push($field_array['values'], [
                        'label'    => $country['short_name'],
                        'value'    => (int) $country['country_id'],
                        'selected' => $selected,
                    ]);
                }
            }

            if ($f == 'name') {
                $field_array['required'] = true;
            }

            $_field_object->label    = $label;
            $_field_object->name     = $f;
            $_field_object->fields   = [];
            $_field_object->fields[] = $field_array;
            $db_fields[]             = $_field_object;
        }
        $data['bodyclass'] = 'web-to-lead-form';
        $data['db_fields'] = $db_fields;
        $this->load->view('leads/formbuilder', $data);
    }

    public function forms($id = '')
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('web_to_lead');
        }

        $data['title'] = _l('web_to_lead');
        $this->load->view('leads/forms', $data);
    }

    public function delete_form($id)
    {
        if (!is_admin()) {
            access_denied('Web To Lead Access');
        }

        $success = $this->leads_model->delete_form($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('web_to_lead_form')));
        }

        redirect(admin_url('client_lead/leads/forms'));
    }

    // Sources
    /* Manage leads sources */
    public function sources()
    {
        if (!is_admin()) {
            access_denied('Leads Sources');
        }
        $data['sources'] = $this->leads_model->get_source();
        $data['title']   = 'Leads sources';
        $this->load->view('leads/manage_sources', $data);
    }

    /* Add or update leads sources */
    public function source()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_source') == '0') {
            access_denied('Leads Sources');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }

                $id = $this->leads_model->add_source($data);

                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_source')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_source($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_source')));
                }
            }
        }
    }

    /* Delete leads source */
    public function delete_source($id)
    {
        if (!is_admin()) {
            access_denied('Delete Lead Source');
        }
        if (!$id) {
            redirect(admin_url('client_lead/leads/sources'));
        }
        $response = $this->leads_model->delete_source($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_source_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_source')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_source_lowercase')));
        }
        redirect(admin_url('client_lead/leads/sources'));
    }

    // Statuses
    /* View leads statuses */
    public function statuses()
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        $data['statuses'] = $this->leads_model->get_status();
        $data['title']    = 'Leads statuses';
        $this->load->view('leads/manage_statuses', $data);
    }

    /* Add or update leads status */
    public function status()
    {
        if (!is_admin() && get_option('staff_members_create_inline_lead_status') == '0') {
            access_denied('Leads Statuses');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $id = $this->leads_model->add_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('lead_status')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->leads_model->update_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('lead_status')));
                }
            }
        }
    }

    /* Delete leads status from databae */
    public function delete_status($id)
    {
        if (!is_admin()) {
            access_denied('Leads Statuses');
        }
        if (!$id) {
            redirect(admin_url('client_lead/leads/statuses'));
        }
        $response = $this->leads_model->delete_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('lead_status_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('lead_status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('lead_status_lowercase')));
        }
        redirect(admin_url('client_lead/leads/statuses'));
    }

    /* Add new lead note */
    public function add_note($rel_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($rel_id)) {
            ajax_access_denied();
        }

        if ($this->input->post()) {
            $data = $this->input->post();

            if ($data['contacted_indicator'] == 'yes') {
                $contacted_date         = to_sql_date($data['custom_contact_date'], true);
                $data['date_contacted'] = $contacted_date;
            }

            unset($data['contacted_indicator']);
            unset($data['custom_contact_date']);

            // Causing issues with duplicate ID or if my prefixed file for lead.php is used
            $data['description'] = isset($data['lead_note_description']) ? $data['lead_note_description'] : $data['description'];

            if (isset($data['lead_note_description'])) {
                unset($data['lead_note_description']);
            }

            $note_id = $this->misc_model->add_note($data, 'lead', $rel_id);

            if ($note_id) {
                if (isset($contacted_date)) {
                    $this->db->where('id', $rel_id);
                    $this->db->update(db_prefix() . 'leads', [
                        'lastcontact' => $contacted_date,
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        $this->leads_model->log_lead_activity($rel_id, 'not_lead_activity_contacted', false, serialize([
                            get_staff_full_name(get_staff_user_id()),
                            _dt($contacted_date),
                        ]));
                    }
                }
            }
        }
        echo json_encode(['leadView' => $this->_get_lead_data($rel_id), 'id' => $rel_id]);
    }

    public function email_integration_folders()
    {
        if (!is_admin()) {
            ajax_access_denied('Leads Test Email Integration');
        }

        app_check_imap_open_function();

        $imap = new Imap(
            $this->input->post('email'),
            $this->input->post('password', false),
            $this->input->post('imap_server'),
            $this->input->post('encryption')
        );

        try {
            echo json_encode($imap->getSelectableFolders());
        } catch (ConnectionErrorException $e) {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $e->getMessage(),
            ]);
        }
    }

    public function test_email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Test Email Integration');
        }

        app_check_imap_open_function(admin_url('client_lead/leads/email_integration'));

        $mail     = $this->leads_model->get_email_integration();
        $password = $mail->password;

        if (false == $this->encryption->decrypt($password)) {
            set_alert('danger', _l('failed_to_decrypt_password'));
            redirect(admin_url('client_leadleads/email_integration'));
        }

        $imap = new Imap(
            $mail->email,
            $this->encryption->decrypt($password),
            $mail->imap_server,
            $mail->encryption
        );

        try {
            $connection = $imap->testConnection();

            try {
                $connection->getMailbox($mail->folder);
                set_alert('success', _l('lead_email_connection_ok'));
            } catch (MailboxDoesNotExistException $e) {
                set_alert('danger', str_replace(["\n", 'Mailbox'], ['<br />', 'Folder'], addslashes($e->getMessage())));
            }
        } catch (ConnectionErrorException $e) {
            $error = str_replace("\n", '<br />', addslashes($e->getMessage()));
            set_alert('danger', _l('lead_email_connection_not_ok') . '<br /><br /><b>' . $error . '</b>');
        }

        redirect(admin_url('client_lead/leads/email_integration'));
    }

    public function email_integration()
    {
        if (!is_admin()) {
            access_denied('Leads Email Intregration');
        }
        if ($this->input->post()) {
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }

            $success = $this->leads_model->update_email_integration($data);
            if ($success) {
                set_alert('success', _l('leads_email_integration_updated'));
            }
            redirect(admin_url('client_lead/leads/email_integration'));
        }
        $data['roles']    = $this->roles_model->get();
        $data['sources']  = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();

        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);

        $data['title'] = _l('leads_email_integration');
        $data['mail']  = $this->leads_model->get_email_integration();

        $data['bodyclass'] = 'leads-email-integration';
        $this->load->view('leads/email_integration', $data);
    }

    public function change_status_color()
    {
        if ($this->input->post() && is_admin()) {
            $this->leads_model->change_status_color($this->input->post());
        }
    }

    public function import()
    {
        if (!is_admin() && get_option('allow_non_admin_members_to_import_leads') != '1') {
            access_denied('Leads Import');
        }

        $dbFields = $this->db->list_fields(db_prefix() . 'leads');
        array_push($dbFields, 'tags');

        $this->load->library('import/import_leads', [], 'import');
        $this->import->setDatabaseFields($dbFields)
        ->setCustomFields(get_custom_fields('leads'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
            $this->import->setSimulation($this->input->post('simulate'))
                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                          ->setFilename($_FILES['file_csv']['name'])
                          ->perform();

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();
        $data['members']  = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);

        $data['title'] = _l('import');
        $this->load->view('leads/import', $data);
    }

    public function validate_unique_field()
    {
        if ($this->input->post()) {

            // First we need to check if the field is the same
            $lead_id = $this->input->post('lead_id');
            $field   = $this->input->post('field');
            $value   = $this->input->post($field);

            if ($lead_id != '') {
                $this->db->select($field);
                $this->db->where('id', $lead_id);
                $row = $this->db->get(db_prefix() . 'leads')->row();
                if ($row->{$field} == $value) {
                    echo json_encode(true);
                    die();
                }
            }

            echo total_rows(db_prefix() . 'leads', [ $field => $value ]) > 0 ? 'false' : 'true';
        }
    }

    public function bulk_action()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        hooks()->do_action('before_do_bulk_action_for_leads');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $status                = $this->input->post('status');
            $source                = $this->input->post('source');
            $assigned              = $this->input->post('assigned');
            $visibility            = $this->input->post('visibility');
            $tags                  = $this->input->post('tags');
            $last_contact          = $this->input->post('last_contact');
            $lost                  = $this->input->post('lost');
            $has_permission_delete = has_permission('leads', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($has_permission_delete) {
                            if ($this->leads_model->delete($id)) {
                                $total_deleted++;
                            }
                        }
                    } else {
                        if ($status || $source || $assigned || $last_contact || $visibility) {
                            $update = [];
                            if ($status) {
                                // We will use the same function to update the status
                                $this->leads_model->update_lead_status([
                                    'status' => $status,
                                    'leadid' => $id,
                                ]);
                            }
                            if ($source) {
                                $update['source'] = $source;
                            }
                            if ($assigned) {
                                $update['assigned'] = $assigned;
                            }
                            if ($last_contact) {
                                $last_contact          = to_sql_date($last_contact, true);
                                $update['lastcontact'] = $last_contact;
                            }

                            if ($visibility) {
                                if ($visibility == 'public') {
                                    $update['is_public'] = 1;
                                } else {
                                    $update['is_public'] = 0;
                                }
                            }

                            if (count($update) > 0) {
                                $this->db->where('id', $id);
                                $this->db->update(db_prefix() . 'leads', $update);
                            }
                        }
                        if ($tags) {
                            handle_tags_save($tags, $id, 'lead');
                        }
                        if ($lost == 'true') {
                            $this->leads_model->mark_as_lost($id);
                        }
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_leads_deleted', $total_deleted));
        }
    }

    public function download_files($lead_id)
    {
        if (!is_staff_member() || !$this->leads_model->staff_can_access_lead($lead_id)) {
            ajax_access_denied();
        }

        $files = $this->leads_model->get_lead_attachments($lead_id);

        if (count($files) == 0) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $path = get_upload_path_by_type('lead') . $lead_id;

        $this->load->library('zip');

        foreach ($files as $file) {
            $this->zip->read_file($path . '/' . $file['file_name']);
        }

        $this->zip->download('files.zip');
        $this->zip->clear_data();
    }

    public function lead_table($id) {

        App_table::find('table-client-leads')->output([
            'userid' => $id,
            'type' => false,
       ]);
    }

    public function lead_table_tab($id) {
        $this->app->get_table_data(module_views_path('client_lead', 'tables/leads_tab'),[
            'userid' => $id,
            'type' => false,
        ]);
    }

    public function record_payment()
    {
        if (!has_permission('payments', '', 'create')) {
            access_denied('Record Payment');
        }
      
        if ($this->input->post()) {
            $invoicetmpDir = module_dir_path('client_lead/asset/invoice_hard_copy');
            $paymentproftmpDir = module_dir_path('client_lead/asset/payment_prof');
            $officialreceipttmpDir = module_dir_path('client_lead/asset/officialreceipt');
            $taxdeduction2307tmpDir = module_dir_path('client_lead/asset/taxdeduction2307');

            if($_FILES['invoice_hard_copy']['name'] != "") {
                $tmpFilePath = $_FILES['invoice_hard_copy']['tmp_name'];
                $path_parts = pathinfo($_FILES['invoice_hard_copy']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $invoice_hard_filename    = time().'_invoice_hard_copy' . '.' . $extension;
                $newFilePath =  $invoicetmpDir . $invoice_hard_filename;
               
                if (!is_dir($invoicetmpDir)) {
                    mkdir($invoicetmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('invoice_hard_copy', $invoice_hard_filename);
                }
            }

            

            if($_FILES['payment_prof']['name'] != "") {
                $tmpFilePath = $_FILES['payment_prof']['tmp_name'];
                $path_parts = pathinfo($_FILES['payment_prof']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $payment_filename    = time().'_payment_proof' . '.' . $extension;
                $newFilePath =  $paymentproftmpDir . $payment_filename;
               
                if (!is_dir($paymentproftmpDir)) {
                    mkdir($paymentproftmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('payment_prof', $payment_filename);
                }
            }


            if($_FILES['official_receipt']['name'] != "") {
                $tmpFilePath = $_FILES['official_receipt']['tmp_name'];
                $path_parts = pathinfo($_FILES['official_receipt']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $official_filename    = time().'_official_receipt' . '.' . $extension;
                $newFilePath =  $officialreceipttmpDir . $official_filename;

                if (!is_dir($officialreceipttmpDir)) {
                    mkdir($officialreceipttmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('officialreceipt', $official_filename);
                }
            }

         

            if($_FILES['tax_deduction_2307']['name'] != "") {
                $tmpFilePath = $_FILES['tax_deduction_2307']['tmp_name'];
                $path_parts = pathinfo($_FILES['tax_deduction_2307']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $tax_deduction_filename    = time().'_tax_deduction_2307' . '.' . $extension;
                $newFilePath =  $taxdeduction2307tmpDir . $tax_deduction_filename;

                if (!is_dir($taxdeduction2307tmpDir)) {
                    mkdir($taxdeduction2307tmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('taxdeduction2307', $tax_deduction_filename);
                }
            }

            $data = $this->input->post();
            $data['invoice_hard_copy'] = isset($invoice_hard_filename) ? $invoice_hard_filename : '';
            $data['payment_prof'] = isset($payment_filename) ? $payment_filename :'';
            $data['officialreceipt'] = isset($official_filename) ? $official_filename : '';
            $data['taxdeduction2307'] = isset($tax_deduction_filename) ?$tax_deduction_filename :'';

            $id = $this->client_lead_payments_model->process_payment($data, '');
            if ($id) {
                set_alert('success', _l('invoice_payment_recorded'));
                redirect(admin_url('client_lead/leads/payment/' . $id));
            } else {
                set_alert('danger', _l('invoice_payment_record_failed'));
            }
            redirect(admin_url('invoices/list_invoices/' . $this->input->post('invoiceid')));
        }
    }

    
 /* Update payment data */
    public function payment($id = '')
    {
        if (!has_permission('payments', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('payments');
        }


        if (!$id) {
            redirect(admin_url('payments'));
        }

        if ($this->input->post()) {
            if (!has_permission('payments', '', 'edit')) {
                access_denied('Update Payment');
            }

            $invoicetmpDir = module_dir_path('client_lead/asset/invoice_hard_copy');
            $paymentproftmpDir = module_dir_path('client_lead/asset/payment_prof');
            $officialreceipttmpDir = module_dir_path('client_lead/asset/officialreceipt');
            $taxdeduction2307tmpDir = module_dir_path('client_lead/asset/taxdeduction2307');
            $get_data_payment = $this->payments_model->get($id);

            if($_FILES['invoice_hard_copy']['name'] != "") {
                $tmpFilePath = $_FILES['invoice_hard_copy']['tmp_name'];
                $path_parts = pathinfo($_FILES['invoice_hard_copy']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $invoice_hard_filename    = time().'_invoice_hard_copy' . '.' . $extension;
                $newFilePath =  $invoicetmpDir . $invoice_hard_filename;
               
                if (!is_dir($invoicetmpDir)) {
                    mkdir($invoicetmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('invoice_hard_copy', $invoice_hard_filename);
                }

                $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/invoice_hard_copy/');

                $OldtargetDir = $removeoldfile . $get_data_payment->invoice_hard_copy;
               
                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }

            }

            if($_FILES['payment_prof']['name'] != "") {
                $tmpFilePath = $_FILES['payment_prof']['tmp_name'];
                $path_parts = pathinfo($_FILES['payment_prof']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $payment_filename    = time().'_payment_proof' . '.' . $extension;
                $newFilePath =  $paymentproftmpDir . $payment_filename;
               
                if (!is_dir($paymentproftmpDir)) {
                    mkdir($paymentproftmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('payment_prof', $payment_filename);
                }

                $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/payment_prof/');

                $OldtargetDir = $removeoldfile . $get_data_payment->payment_prof;
               
                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }
            }


            if($_FILES['official_receipt']['name'] != "") {
                $tmpFilePath = $_FILES['official_receipt']['tmp_name'];
                $path_parts = pathinfo($_FILES['official_receipt']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $official_filename    = time().'_official_receipt' . '.' . $extension;
                $newFilePath =  $officialreceipttmpDir . $official_filename;

                if (!is_dir($officialreceipttmpDir)) {
                    mkdir($officialreceipttmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('officialreceipt', $official_filename);
                }

                $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/officialreceipt/');

                $OldtargetDir = $removeoldfile . $get_data_payment->officialreceipt;
               
                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }
            }

            if($_FILES['tax_deduction_2307']['name'] != "") {
                $tmpFilePath = $_FILES['tax_deduction_2307']['tmp_name'];
                $path_parts = pathinfo($_FILES['tax_deduction_2307']['name']);
                $extension  = $path_parts['extension'];
                $extension  = strtolower($extension);
                $tax_deduction_filename    = time().'_tax_deduction_2307' . '.' . $extension;
                $newFilePath =  $taxdeduction2307tmpDir . $tax_deduction_filename;

                if (!is_dir($taxdeduction2307tmpDir)) {
                    mkdir($taxdeduction2307tmpDir, 0777, true);
                }

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option('taxdeduction2307', $tax_deduction_filename);
                }

                $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/taxdeduction2307/');

                $OldtargetDir = $removeoldfile . $get_data_payment->taxdeduction2307;
               
                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }
            }

            $data = $this->input->post();


            $data['invoice_hard_copy'] = isset($invoice_hard_filename) ? $invoice_hard_filename : $get_data_payment->invoice_hard_copy;
            $data['payment_prof'] = isset($payment_filename) ? $payment_filename : $get_data_payment->payment_prof;
            $data['officialreceipt'] = isset($official_filename) ? $official_filename : $get_data_payment->officialreceipt;
            $data['taxdeduction2307'] = isset($tax_deduction_filename) ?$tax_deduction_filename : $get_data_payment->taxdeduction2307;

            $success = $this->payments_model->update($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('payment')));
            }
            redirect(admin_url('client_lead/leads/payment/' . $id));
        }

        $payment = $this->payments_model->get($id);

        if (!$payment) {
            show_404();
        }

        $this->load->model('invoices_model');
        $payment->invoice = $this->invoices_model->get($payment->invoiceid);
        $template_name    = 'invoice_payment_recorded_to_customer';

        $data = prepare_mail_preview_data($template_name, $payment->invoice->clientid);

        $data['payment'] = $payment;
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [], true, true);

        $i = 0;
        foreach ($data['payment_modes'] as $mode) {
            if ($mode['active'] == 0 && $data['payment']->paymentmode != $mode['id']) {
                unset($data['payment_modes'][$i]);
            }
            $i++;
        }

        $data['title'] = _l('payment_receipt') . ' - ' . format_invoice_number($data['payment']->invoiceid);

        
        $this->load->view('payment/payment.php', $data);
    }

    public function proposal($id = '')
    {

        if ($this->input->post()) {
            $this->load->model('proposals_model');
            $proposal_data = $this->input->post();
            if ($id == '') {
                if (staff_cant('create', 'proposals')) {
                    access_denied('proposals');
                }
                unset($proposal_data['proposal_document']);
                unset($proposal_data['proposal_attachment']);

                $proposaldocumentDir = module_dir_path('client_lead/asset/proposaldocument');
                // $proposalattachmentDir = module_dir_path('client_lead/asset/proposalattachment');

                if($_FILES['proposal_document']['name'] != "") {
                    $tmpFilePath = $_FILES['proposal_document']['tmp_name'];
                    $path_parts = pathinfo($_FILES['proposal_document']['name']);
                    $extension  = $path_parts['extension'];
                    $proposal_documentname    = time().'_document' . '.' . $extension;

                    $newFilePath =  $proposaldocumentDir . $proposal_documentname;
                
                    if (!is_dir($proposaldocumentDir)) {
                        mkdir($proposaldocumentDir, 0777, true);
                    }

                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        update_option('proposal_document', $proposal_documentname);
                    }

                    $proposal_data['proposal_document'] = isset($proposal_documentname) ?$proposal_documentname :'';
                }

                // if($_FILES['proposal_attachment']['name'] != "") {
                //     $tmpFilePath = $_FILES['proposal_attachment']['tmp_name'];
                //     $path_parts = pathinfo($_FILES['proposal_attachment']['name']);
                //     $extension  = $path_parts['extension'];
                //     $proposal_attachmentname    = time().'_attachment' . '.' . $extension;

                //     $newFilePath =  $proposalattachmentDir . $proposal_attachmentname;
                
                //     if (!is_dir($proposalattachmentDir)) {
                //         mkdir($proposalattachmentDir, 0777, true);
                //     }

                //     if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                //         update_option('proposal_attachment', $proposal_attachmentname);
                //     }

                //     $proposal_data['proposal_attachment'] = isset($proposal_attachmentname) ?$proposal_attachmentname :'';
                // }

                $id = $this->proposals_model->add($proposal_data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('proposal')));
                    if ($this->set_proposal_pipeline_autoload($id)) {
                        redirect(admin_url('proposals'));
                    } else {
                        redirect(admin_url('proposals/list_proposals/' . $id));
                    }
                }
            } else {
                if (staff_cant('edit', 'proposals')) {
                    access_denied('proposals');
                }

                if($_FILES['proposal_document']['name'] != "") {
                    $proposaldocumentDir = module_dir_path('client_lead/asset/proposaldocument');
                    $tmpFilePath = $_FILES['proposal_document']['tmp_name'];
                    $path_parts = pathinfo($_FILES['proposal_document']['name']);
                    $extension  = $path_parts['extension'];
                    $proposal_document    = time().'_document' . '.' . $extension;

                    $newFilePath =  $proposaldocumentDir . $proposal_document;
                
                    if (!is_dir($proposaldocumentDir)) {
                        mkdir($proposaldocumentDir, 0777, true);
                    }

                    $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/proposaldocument');
                    $OldtargetDir = $removeoldfile . $proposal_data['proposal_document_hidden'];
                    if (file_exists($OldtargetDir)) {
                        unlink($OldtargetDir);
                    }
                    unset($proposal_data['proposal_document_hidden']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        update_option('proposal_document', $proposal_document);
                    }
                    $proposal_data['proposal_document'] = isset($proposal_document) ?$proposal_document :'';
                } else {
                    $proposal_data['proposal_document'] = $proposal_data['proposal_document_hidden'];
                    unset($proposal_data['proposal_document_hidden']);
                }


                // if($_FILES['proposal_attachment']['name'] != "") {
                //     $proposalattachmentDir = module_dir_path('client_lead/asset/proposalattachment');
                //     $tmpFilePath = $_FILES['proposal_attachment']['tmp_name'];
                //     $path_parts = pathinfo($_FILES['proposal_attachment']['name']);
                //     $extension  = $path_parts['extension'];
                //     $proposal_attachment    = time().'_attachment' . '.' . $extension;

                //     $newFilePath =  $proposalattachmentDir . $proposal_attachment;
                
                //     if (!is_dir($proposalattachmentDir)) {
                //         mkdir($proposalattachmentDir, 0777, true);
                //     }

                //     $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/proposalattachment');
                //     $OldtargetDir = $removeoldfile . $proposal_data['proposal_attachment_hidden'];
                //     if (file_exists($OldtargetDir)) {
                //         unlink($OldtargetDir);
                //     }
                //     unset($proposal_data['proposal_attachment_hidden']);
                //     if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                //         update_option('proposal_attachment', $proposal_attachment);
                //     }
                //     $proposal_data['proposal_attachment'] = isset($proposal_attachment) ?$proposal_attachment :'';
                // } else {
                //     $proposal_data['proposal_attachment'] = $proposal_data['proposal_attachment_hidden'];
                //     unset($proposal_data['proposal_attachment_hidden']);
                // }
                $success = $this->proposals_model->update($proposal_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('proposal')));
                }
                if ($this->set_proposal_pipeline_autoload($id)) {
                    redirect(admin_url('proposals'));
                } else {
                    redirect(admin_url('proposals/list_proposals/' . $id));
                }
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('proposal_lowercase'));
        } else {
            $data['proposal'] = $this->proposals_model->get($id);

            if (!$data['proposal'] || !user_can_view_proposal($id)) {
                blank_page(_l('proposal_not_found'));
            }

            $data['estimate']    = $data['proposal'];
            $data['is_proposal'] = true;
            $title               = _l('edit', _l('proposal_lowercase'));
        }

        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();
        $this->load->model('invoice_items_model');
        $data['ajaxItems'] = false;
        if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped();
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $data['statuses']      = $this->proposals_model->get_statuses();
        $data['staff']         = $this->staff_model->get('', ['active' => 1]);
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['title'] = $title;
        $this->load->view('admin/proposals/proposal', $data);
    }

    public function set_proposal_pipeline_autoload($id)
    {
        if ($id == '') {
            return false;
        }

        if ($this->session->has_userdata('proposals_pipeline') && $this->session->userdata('proposals_pipeline') == 'true') {
            $this->session->set_flashdata('proposalid', $id);

            return true;
        }

        return false;
    }

     /* Add new estimate or update existing */
     public function estimates($id = '')
     {
         if ($this->input->post()) {
            $this->load->model('estimates_model');
             $estimate_data = $this->input->post();
 
             $save_and_send_later = false;
             if (isset($estimate_data['save_and_send_later'])) {
                 unset($estimate_data['save_and_send_later']);
                 $save_and_send_later = true;
             }
 
             if ($id == '') {
                 if (staff_cant('create', 'estimates')) {
                     access_denied('estimates');
                 }


                 unset($estimate_data['estimate_document']);
                 unset($estimate_data['estimate_attachment']);

                 $estimatedocumentDir = module_dir_path('client_lead/asset/estimatedocument');
                 $estimateattachmentDir = module_dir_path('client_lead/asset/estimateattachment');


                 if($_FILES['estimate_document']['name'] != "") {
                    $tmpFilePath = $_FILES['estimate_document']['tmp_name'];
                    $path_parts = pathinfo($_FILES['estimate_document']['name']);
                    $extension  = $path_parts['extension'];
                    $estimate_documentname    = time().'_document' . '.' . $extension;

                    $newFilePath =  $estimatedocumentDir . $estimate_documentname;
                
                    if (!is_dir($estimatedocumentDir)) {
                        mkdir($estimatedocumentDir, 0777, true);
                    }

                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        update_option('estimate_document', $estimate_documentname);
                    }

                    $estimate_data['estimate_document'] = isset($estimate_documentname) ?$estimate_documentname :'';
                }

                // if($_FILES['estimate_attachment']['name'] != "") {
                //     $tmpFilePath = $_FILES['estimate_attachment']['tmp_name'];
                //     $path_parts = pathinfo($_FILES['estimate_attachment']['name']);
                //     $extension  = $path_parts['extension'];
                //     $estimate_attachmentname    = time().'_attachment' . '.' . $extension;

                //     $newFilePath =  $estimateattachmentDir . $estimate_attachmentname;
                
                //     if (!is_dir($estimateattachmentDir)) {
                //         mkdir($estimateattachmentDir, 0777, true);
                //     }

                //     if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                //         update_option('estimate_attachment', $estimate_attachmentname);
                //     }

                //     $estimate_data['estimate_attachment'] = isset($estimate_attachmentname) ?$estimate_attachmentname :'';
                // }

                
                 $id = $this->estimates_model->add($estimate_data);
 
                 if ($id) {
                     set_alert('success', _l('added_successfully', _l('estimate')));
 
                     $redUrl = admin_url('estimates/list_estimates/' . $id);
 
                     if ($save_and_send_later) {
                         $this->session->set_userdata('send_later', true);
                         // die(redirect($redUrl));
                     }
 
                     redirect(
                         !$this->set_estimate_pipeline_autoload($id) ? $redUrl : admin_url('estimates/list_estimates/')
                     );
                 }

             } else {
                 if (staff_cant('edit', 'estimates')) {
                     access_denied('estimates');
                 }

                 if($_FILES['estimate_document']['name'] != "") {
                    $estimatedocumentDir = module_dir_path('client_lead/asset/estimatedocument');
                    $tmpFilePath = $_FILES['estimate_document']['tmp_name'];
                    $path_parts = pathinfo($_FILES['estimate_document']['name']);
                    $extension  = $path_parts['extension'];
                    $estimate_document    = time().'_document' . '.' . $extension;

                    $newFilePath =  $estimatedocumentDir . $estimate_document;
                
                    if (!is_dir($estimatedocumentDir)) {
                        mkdir($estimatedocumentDir, 0777, true);
                    }

                    $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/estimatedocument');
                    $OldtargetDir = $removeoldfile . $estimate_data['estimate_document_hidden'];
                    if (file_exists($OldtargetDir)) {
                        unlink($OldtargetDir);
                    }
                    unset($estimate_data['estimate_document_hidden']);
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        update_option('estimate_document', $estimate_document);
                    }
                    $estimate_data['estimate_document'] = isset($estimate_document) ?$estimate_document :'';    
                } else {
                    $estimate_data['estimate_document'] = $estimate_data['estimate_document_hidden'];
                    unset($estimate_data['estimate_document_hidden']);
                   
                }

                // if($_FILES['estimate_attachment']['name'] != "") {
                //     $estimateattachmentDir = module_dir_path('client_lead/asset/estimateattachment');
                //     $tmpFilePath = $_FILES['estimate_attachment']['tmp_name'];
                //     $path_parts = pathinfo($_FILES['estimate_attachment']['name']);
                //     $extension  = $path_parts['extension'];
                //     $estimate_attachment    = time().'_attachment' . '.' . $extension;

                //     $newFilePath =  $estimateattachmentDir . $estimate_attachment;
                
                //     if (!is_dir($estimateattachmentDir)) {
                //         mkdir($estimateattachmentDir, 0777, true);
                //     }

                //     $removeoldfile = module_dir_path(CLIENT_LEAD_MODULE . '/asset/estimateattachment');
                //     $OldtargetDir = $removeoldfile . $estimate_data['estimate_attachment_hidden'];
                //     if (file_exists($OldtargetDir)) {
                //         unlink($OldtargetDir);
                //     }
                //     unset($estimate_data['estimate_attachment_hidden']);
                //     if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                //         update_option('estimate_attachment', $estimate_attachment);
                //     }
                //     $estimate_data['estimate_attachment'] = isset($estimate_attachment) ?$estimate_attachment :'';
                // } else {
                //     $estimate_data['estimate_attachment'] = $estimate_data['estimate_attachment_hidden'];
                //     unset($estimate_data['estimate_attachment_hidden']);
                // }

                 $success = $this->estimates_model->update($estimate_data, $id);
                 if ($success) {
                     set_alert('success', _l('updated_successfully', _l('estimate')));
                 }
                 if ($this->set_estimate_pipeline_autoload($id)) {
                     redirect(admin_url('estimates/list_estimates/'));
                 } else {
                     redirect(admin_url('estimates/list_estimates/' . $id));
                 }
             }
         }
         if ($id == '') {
             $title = _l('create_new_estimate');
         } else {
             $estimate = $this->estimates_model->get($id);
 
             if (!$estimate || !user_can_view_estimate($id)) {
                 blank_page(_l('estimate_not_found'));
             }
 
             $data['estimate'] = $estimate;
             $data['edit']     = true;
             $title            = _l('edit', _l('estimate_lowercase'));
         }
 
         if ($this->input->get('customer_id')) {
             $data['customer_id'] = $this->input->get('customer_id');
         }
 
         if ($this->input->get('estimate_request_id')) {
             $data['estimate_request_id'] = $this->input->get('estimate_request_id');
         }
 
         $this->load->model('taxes_model');
         $data['taxes'] = $this->taxes_model->get();
         $this->load->model('currencies_model');
         $data['currencies'] = $this->currencies_model->get();
 
         $data['base_currency'] = $this->currencies_model->get_base_currency();
 
         $this->load->model('invoice_items_model');
 
         $data['ajaxItems'] = false;
         if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
             $data['items'] = $this->invoice_items_model->get_grouped();
         } else {
             $data['items']     = [];
             $data['ajaxItems'] = true;
         }
         $data['items_groups'] = $this->invoice_items_model->get_groups();
 
         $data['staff']             = $this->staff_model->get('', ['active' => 1]);
         $data['estimate_statuses'] = $this->estimates_model->get_statuses();
         $data['title']             = $title;
         $this->load->view('admin/estimates/estimate', $data);
     }

    public function set_estimate_pipeline_autoload($id)
    {
        if ($id == '') {
            return false;
        }

        if ($this->session->has_userdata('estimate_pipeline')
                && $this->session->userdata('estimate_pipeline') == 'true') {
            $this->session->set_flashdata('estimateid', $id);

            return true;
        }

        return false;
    }

     public function printcheck($id) {
        if (empty($id)) {
            return false;
        }
        $expense = $this->expenses_model->get($id);
        try {
            $pdf = expense_note_pdf($expense);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        $type = 'I';
        $pdf_name = '_check';
        ob_end_clean();
        $pdf->Output($pdf_name . '.pdf', $type);
    }

}
