<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Google_sheet_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get google sheet
     * @param  mixed $id        google sheet id
     * @return object
     */
    public function get($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
            $google_sheet = $this->db->get(db_prefix() . 'google_sheets')->row();
    
            return $google_sheet;
        } else {
            $google_sheets = $this->db->get(db_prefix() . 'google_sheets')->result_array();
            return $google_sheets;
        }
    }

    /**
     * Get google sheet
     * @param  mixed $id        google sheet id
     * @return object
     */
    public function get_by_sheetid($id = '')
    {
        $this->db->where('sheetid', $id);
        $google_sheet = $this->db->get(db_prefix() . 'google_sheets')->row();

        return $google_sheet;
    }

    /**
     * Get and google sheets
     * @return object
     */
    public function get_all()
    {
        if (!is_admin()) {
            $this->db->where('staffid', get_staff_user_id());
        }
        $google_sheets = $this->db->get(db_prefix() . 'google_sheets')->result_array();
        return $google_sheets;
    }

    /**
     * Update google sheet
     * @param  array $data      google sheet $_POST data
     * @param  mixed $id        google sheet id
     * @return boolean
     */
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $update_data = [];
        $update_data['title'] = $data['title'];
        if (isset($data['description'])) {
            $update_data['description'] = $data['description'];
        }
        $this->db->update(db_prefix() . 'google_sheets', $update_data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Google Sheet Updated [ID: ' . $id . ', Title: ' . $data['title'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Add new google sheet
     * @param array $data google sheet $_POST data
     * @return mixed
     */
    public function add($data)
    {
        $date_created = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'google_sheets', [
            'staffid'        => $data['staffid'],
            'sheetid'        => $data['sheetid'],
            'title'           => $data['title'],
            'description'     => '',
            'date'            => $date_created
        ]);
        $google_sheet_id = $this->db->insert_id();
        if (!$google_sheet_id) {
            log_activity('New Google Sheet Added [ID: ' . $google_sheet_id . ', Title: ' . $data['title'] . ']');
        }

        return $google_sheet_id;
    }

    /**
     * Delete google sheet
     * @param  mixed $id google sheet id
     * @return boolean
     */
    public function delete($id)
    {
        $affectedRows = 0;
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'google_sheets');
        if ($affectedRows > 0) {
            log_activity('Google Sheet Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }
}
