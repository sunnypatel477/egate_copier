<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

class FlexforumTopic_model extends App_Model
{
    protected $table = 'flexforumtopics';

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
        $topic_table = db_prefix() . $this->table;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $follower_table = db_prefix() . FLEXFORUM_FOLLOWERS_TABLE;
        $this->db->select("DISTINCT $topic_table.*, count($like_table.id) as likes, count($follower_table.id) as followers", FALSE);

        $this->db->from($topic_table);
        $this->db->join($like_table, "ON $like_table.type_id = $topic_table.id
        AND $like_table.type = 'topic' AND $like_table.banned = 0", 'left');
        $this->db->join($follower_table, "ON $follower_table.type_id = $topic_table.id
        AND $follower_table.type = 'topic' AND $follower_table.banned = 0", 'left');
        $this->db->group_by("$topic_table.id");

        if (!empty($conditions)) {
            $new_conditions = [];

            array_map(function ($key, $value) use (&$new_conditions, $topic_table) {
                $new_conditions[$topic_table . '.' . $key] = $value;
            }, array_keys($conditions), array_values($conditions));

            $this->db->where($new_conditions);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function query_all($conditions = [])
    {
        $topic_table = db_prefix() . $this->table;
        $follower_table = db_prefix() . FLEXFORUM_FOLLOWERS_TABLE;
        $modal_table = db_prefix() . FLEXFORUM_MODULES_TABLE;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $reply_table = db_prefix() . FLEXFORUM_REPLIES_TABLE;
        $reply_type = FLEXFORUM_TOPIC_REPLY_TYPE;
        $like_type = FLEXFORUM_TOPIC_LIKE_TYPE;
        $module_type = FLEXFORUM_MODULES_TYPE;
        $follower_type = FLEXFORUM_TOPIC_FOLLOWER_TYPE;

        $this->db->select('t.*, r.replies, l.likes, f.followers, m.model_name, m.brand, m.code');
        $this->db->from("$topic_table AS t");
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS replies FROM $reply_table GROUP BY type_id, type, banned) r", "t.id = r.type_id AND r.type = '$reply_type' AND r.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS likes FROM $like_table GROUP BY type_id, type, banned) l", "t.id = l.type_id AND l.type = '$like_type' AND l.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS followers FROM $follower_table GROUP BY type_id, type, banned) f", "t.id = f.type_id AND f.type = '$follower_type' AND f.banned = 0", 'left');

        $this->db->join("(SELECT id, model_name, brand, code FROM $modal_table) m", "t.model_id = m.id", 'left');

        foreach ($conditions as $field => $value) {
            if ($value !== null) {
                if ($field == 'title') {
                    $this->db->like("t.$field", $value);
                } else {
                    $this->db->where("t.$field", $value);
                }
            }
        }

        $query = $this->db->get();
        return $query->result_array();
    }


    /**
     * @param $conditions
     * @return array
     * get model by conditions
     */
    public function get($conditions)
    {
        $topic_table = db_prefix() . $this->table;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $follower_table = db_prefix() . FLEXFORUM_FOLLOWERS_TABLE;
        $model_table = db_prefix() . FLEXFORUM_MODULES_TABLE;

        $this->db->select("DISTINCT $topic_table.*, 
                   COUNT(DISTINCT $like_table.id) AS likes, 
                   COUNT(DISTINCT $follower_table.id) AS followers,
                   $model_table.brand, 
                   $model_table.code", FALSE);
        $this->db->from($topic_table);
        $this->db->join($like_table, "$like_table.type_id = $topic_table.id AND $like_table.type = 'topic' AND $like_table.banned = 0", 'left');
        $this->db->join($model_table, "$model_table.id = $topic_table.model_id", 'left');
        $this->db->join($follower_table, "$follower_table.type_id = $topic_table.id AND $follower_table.type = 'topic' AND $follower_table.banned = 0", 'left');
        $this->db->group_by("$topic_table.id");


        if (!empty($conditions)) {
            $new_conditions = [];

            array_map(function ($key, $value) use (&$new_conditions, $topic_table) {
                $new_conditions[$topic_table . '.' . $key] = $value;
            }, array_keys($conditions), array_values($conditions));

            $this->db->where($new_conditions);
        }

        $query = $this->db->get();
        return $query->row_array();
    }

    public function query_get($conditions)
    {
        $topic_table = db_prefix() . $this->table;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $reply_table = db_prefix() . FLEXFORUM_REPLIES_TABLE;
        $follower_table = db_prefix() . FLEXFORUM_FOLLOWERS_TABLE;
        $reply_type = FLEXFORUM_TOPIC_REPLY_TYPE;
        $like_type = FLEXFORUM_TOPIC_LIKE_TYPE;
        $follower_type = FLEXFORUM_TOPIC_FOLLOWER_TYPE;

        $this->db->select('t.*, r.replies, l.likes, f.followers');
        $this->db->from("$topic_table AS t");
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS replies FROM $reply_table GROUP BY type_id, type, banned) r", "t.id = r.type_id AND r.type = '$reply_type' AND r.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS likes FROM $like_table GROUP BY type_id, type, banned) l", "t.id = l.type_id AND l.type = '$like_type' AND l.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS followers FROM $follower_table GROUP BY type_id, type, banned) f", "t.id = f.type_id AND f.type = '$follower_type' AND f.banned = 0", 'left');

        foreach ($conditions as $field => $value) {
            if ($value !== null) {
                if ($field == 'title') {
                    $this->db->like("t.$field", $value);
                } else {
                    $this->db->where("t.$field", $value);
                }
            }
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
            log_activity('New Forum Topic Added [ID:' . $insert_id . ', ' . $data['title'] . ']');
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
            log_activity('Forum Topic Updated [ID:' . $id . ', ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    public function ban($conditions, $ban = true)
    {
        $data = [
            'banned' => $ban,
            'date_updated' => to_sql_date(Carbon::now()->toDateTimeString(), true)
        ];

        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
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
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete_by_category($category_id)
    {
        $this->db->where('id', $category_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Forum Topic Deleted by Category [Category ID:' . $category_id . ']');
            return true;
        }
        return false;
    }

    public function count($conditions = [])
    {

        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
}
