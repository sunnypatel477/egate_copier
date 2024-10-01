<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emailcanvas_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $this->db->insert(db_prefix() . 'emailcanvas_templates', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'emailcanvas_templates')->row();
    }

    public function get_all()
    {
        return $this->db->get(db_prefix() . 'emailcanvas_templates')->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'emailcanvas_templates', $data);

        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'emailcanvas_templates');

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function changeTemplateStatus($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix().'emailcanvas_templates', [
            'is_enabled' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function getEmailTemplateToSend($templateSlug = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix().'emailcanvas_templates');
        $this->db->where('is_enabled', 1);
        $this->db->where('template_for', $templateSlug);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $template = $query->row_array();

        if (!$template) {
            $this->db->reset_query();
            $this->db->select('*');
            $this->db->from(db_prefix().'emailcanvas_templates');
            $this->db->where('is_enabled', 1);
            $this->db->where('template_for', '');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $fallback_query = $this->db->get();
            $template = $fallback_query->row_array();
        }

        return $template;

    }

}
