<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Flexform_model extends App_Model
{
    protected $table = 'flexforms';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $conditions
     * @return array|array[]
     * get all models
     */
    public function all($conditions = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * @param $conditions
     * @return array
     * get model by id
     */
    public function get($conditions)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * @param $slug
     * @return array
     * get model by slug
     */
    public function get_by_slug_or_id($slug)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        $this->db->where('slug', $slug);
        $query = $this->db->get();
        $result = $query->row_array();
        if($result){
            return $result;
        }else{
            $this->db->where('id', $slug);
            $this->db->from(db_prefix() . $this->table);
            $query = $this->db->get();
            $result = $query->row_array();
        }
        return $result;
    }

    /**
     * @param $data
     * @return bool
     * add model
     */
    public function add($data)
    {
        $data['date_added'] = date('Y-m-d H:i:s');
        $data['date_updated'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . $this->table, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Form Added [ID:' . $insert_id . ', ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     * update model
     */
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     * delete model
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Form Deleted [ID:' . $id . ']');
            return true;
        }
        return false;
    }
}