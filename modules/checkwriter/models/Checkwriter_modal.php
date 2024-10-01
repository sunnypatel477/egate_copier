<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Checkwriter_modal extends App_Model
{
    public function add_bank_details($data){
        if ($data != '') {

			$this->db->insert(db_prefix() . 'bank_details', [
				'bank_name' => $data['bank_name'],
				'account_no' => $data['account_no'],
				'account_notes' => $data['bank_name'],
				'account_notes' =>$data['account_notes'],
			]);
			return true;
		}
        return false;
    }

    public function get_bank_data($id = '' ) {
        if($id) {
            $this->db->where('id', $id);
		    return $this->db->get(db_prefix() . 'bank_details')->row_array();
        } else {
		    return $this->db->get(db_prefix() . 'bank_details')->result_array();
        }
    }

	public function get_history_data($id = '' ) {
        if($id) {
            $this->db->where('id', $id);
		    return $this->db->get(db_prefix() . 'expensive_history')->row_array();
        } else {
		    return $this->db->get(db_prefix() . 'expensive_history')->result_array();
        }
    }

    public function update_bank_details($data){

        $id = $data['bank_id'];
        unset($data['bank_id']);
        
        $this->db->where('id', $id);
		$success = $this->db->update(db_prefix() . 'bank_details', $data);

		if (!$success) {
			return true;
		}
		return true;
    }

	public function update_check_historys($data){

        $id = $data['history_id'];
		$data['vendor_payee']=$data['payee_to'];
        unset($data['payee_to']);
        unset($data['history_id']);
        $this->db->where('id', $id);
		$success = $this->db->update(db_prefix() . 'expensive_history', $data);
		if (!$success) {
			return true;
		}
		return true;
    }

	public function get_check_history_data($id)
	{
		$this->db->where('id', $id);
		return $this->db->get(db_prefix() . 'expensive_history')->row();
	}

    public function delete_bank_details($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('bank_details');

		if (!$delete) {
			return false;
		}
		return true;
	}

	public function update_expensive($data,$id) {
		$this->db->where('id', $id);
        $this->db->update(db_prefix() . 'expenses', $data);
	}

	public function add_expensive_history($data) {
		if(!empty($data['id'])){
			unset($data['id']);
		}
		$count_check_history = count_check_history($data['expensive_id']);
		if($count_check_history->count > 0) {
			$this->db->where('id', $count_check_history->id);
        	$this->db->update(db_prefix() . 'expensive_history', $data);
		} else {
			$this->db->insert(db_prefix() . 'expensive_history', $data);
		}
	}

	public function delete_check_history($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('expensive_history');

		if (!$delete) {
			return false;
		}
		return true;
	}

}
?>