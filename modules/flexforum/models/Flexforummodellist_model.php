<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Flexforummodellist_model extends App_Model
{
    protected $table = 'flexforummodellist';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $conditions
     * @return array
     * get model by id
     */
    public function get_all()
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_model_list() {
        $this->db->select('id,model_name');
        $this->db->from(db_prefix() . $this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * @param $data
     * @return bool
     * add model
     */
    public function add($data)
    {
        $this->db->insert(db_prefix() . $this->table, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Forum Category Added [ID:' . $insert_id . ', ' . $data['model_name'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * update model
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Forum Category Updated [ID:' . $id . ', ' . $data['model_name'] . ']');
            return true;
        }
        return false;
    }

    /**
     * @param $conditions
     * @return bool
     * delete model
     */
    public function delete($conditions = [])
    {
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    public function get_model_id_by_list($id) {
        $CI =& get_instance();
    
        if (!isset($CI->flexforumtopic_model)) {
            $CI->load->model('flexforumtopic_model');
        }
    
        $this->db->select('id, model_name, brand, code');
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . $this->table);
        $modellist = $query->result_array();
    
        return $modellist;
    }
}
