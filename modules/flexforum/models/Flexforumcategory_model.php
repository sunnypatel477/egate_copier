<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Flexforumcategory_model extends App_Model
{
    protected $table = 'flexforumcategories';

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
        $this->db->where('parent_id !=', 0);
        $this->db->from(db_prefix() . $this->table);
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

        /**
     * @param array $conditions
     * @return array|array[]
     * get all models
     */
    public function parent_all($conditions = [])
    {
        $this->db->select('*');
        $this->db->where('parent_id', 0);
        $this->db->from(db_prefix() . $this->table);
        if(!empty($conditions)){
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
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        $query = $this->db->get();
        return $query->row_array();
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
            log_activity('New Forum Category Added [ID:' . $insert_id . ', ' . $data['name'] . ']');
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
            log_activity('Forum Category Updated [ID:' . $id . ', ' . $data['name'] . ']');
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

    public function get_parent_id_by_child_cat($id) {
        $CI =& get_instance();
    
        if (!isset($CI->flexforumtopic_model)) {
            $CI->load->model('flexforumtopic_model');
        }
    
        $this->db->select('id, name');
        $this->db->where('parent_id', $id);
        $query = $this->db->get(db_prefix() . 'flexforumcategories');
        $categories = $query->result_array();
    
        foreach ($categories as &$category) {
            $category_id = $category['id'];
            $category['count'] = $CI->flexforumtopic_model->count([
                'childcategory' => $category_id
            ]);
        }

        return $categories;
    }
    
      
}
