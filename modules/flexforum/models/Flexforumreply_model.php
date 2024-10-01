<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

class Flexforumreply_model extends App_Model
{
    protected $table = FLEXFORUM_REPLIES_TABLE;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $conditions
     * @param array $sortings
     * @return array|array[]
     * get all models
     */
    public function all($conditions = [], $sortings = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . $this->table);
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if (!empty($sortings)) {
            for ($i = 0; $i < count($sortings); $i++) {
                $this->db->order_by($sortings[$i]['field'], $sortings[$i]['order']);
            }
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function query_all($conditions = [], $sortings = [])
    {
        $reply_table = db_prefix() . $this->table;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $reply_type = FLEXFORUM_REPLY_REPLY_TYPE;
        $like_type = FLEXFORUM_REPLY_LIKE_TYPE;

        $this->db->select('r1.*, r.replies, l.likes');
        $this->db->from("$reply_table AS r1");
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS replies FROM $reply_table GROUP BY type_id, type, banned) r", "r1.id = r.type_id AND r.type = '$reply_type' AND r.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS likes FROM $like_table GROUP BY type_id, type, banned) l", "r1.id = l.type_id AND l.type = '$like_type' AND l.banned = 0", 'left');

        $conditions = array_merge($conditions, [
            'banned' => 0
        ]);
        
        foreach ($conditions as $field => $value) {
            if ($value !== null) {
                if ($field == 'title') {
                    $this->db->like("r1.$field", $value);
                } else {
                    $this->db->where("r1.$field", $value);
                }
            }
        }

        if (!empty($sortings)) {
            for ($i = 0; $i < count($sortings); $i++) {
                $this->db->order_by('r1.' . $sortings[$i]['field'], $sortings[$i]['order']);
            }
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

    public function query_get($conditions)
    {
        $reply_table = db_prefix() . $this->table;
        $like_table = db_prefix() . FLEXFORUM_LIKES_TABLE;
        $reply_type = FLEXFORUM_REPLY_REPLY_TYPE;
        $like_type = FLEXFORUM_REPLY_LIKE_TYPE;

        $this->db->select('r1.*, r.replies, l.likes');
        $this->db->from("$reply_table AS r1");
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS replies FROM $reply_table GROUP BY type_id, type, banned) r", "r1.id = r.type_id AND r.type = '$reply_type' AND r.banned = 0", 'left');
        $this->db->join("(SELECT type_id, type, banned, COUNT(*) AS likes FROM $like_table GROUP BY type_id, type, banned) l", "r1.id = l.type_id AND l.type = '$like_type' AND l.banned = 0", 'left');
        
        foreach ($conditions as $field => $value) {
            if ($value !== null) {
                if ($field == 'title') {
                    $this->db->like("r1.$field", $value);
                } else {
                    $this->db->where("r1.$field", $value);
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
            log_activity('New Reply Added [ID:' . $insert_id . ']');
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
            log_activity('Reply Updated [ID:' . $id . ']');
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
    
    public function delete_by_topic($topic_id)
    {
        $conditions = [
            'type_id' => $topic_id,
            'type' => FLEXFORUM_TOPIC_LIKE_TYPE
        ];

        $this->db->where($conditions);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Reply deleted by topic [TOPIC ID:' . $topic_id . ']');
            return true;
        }
        return false;
    }

    public function get_topic_filename($id = '')
	{
		$this->db->where('id', $id);
		$topic_file = $this->db->get(db_prefix() . 'flexforumtopics')->row();
       
		if (!$topic_file) {
			return false;
		}
		return $topic_file;
	}

    public function get_replay_filename($id = '')
	{
		$this->db->where('id', $id);
		$topic_file = $this->db->get(db_prefix() . 'flexforumreplies')->row();
       
		if (!$topic_file) {
			return false;
		}
		return $topic_file;
	}

    public function delete_replay_file($id = '')
	{
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'flexforumreplies', ['replay_file' => null]);
	}

    

    public function ban($conditions, $ban = true)
    {
        $data = [
            'banned' => $ban,
            'date_updated' => to_sql_date(Carbon::now()->toDateTimeString(), true)
        ];
        
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
