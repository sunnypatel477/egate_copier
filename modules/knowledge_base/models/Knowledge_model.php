<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Knowledge_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all knowledge_base attachments
     * @param  mixed $knowledge_base_id knowledge_base_id
     * @return array
     */
    public function get_knowledge_base_attachments($knowledge_base_id, $where = [])
    {
        $this->db->select(implode(', ', prefixed_table_fields_array(db_prefix() . 'files')));
        $this->db->where(db_prefix() . 'files.rel_id', $knowledge_base_id);
        $this->db->where(db_prefix() . 'files.rel_type', 'knowledge_base');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        return $this->db->get(db_prefix() . 'files')->result_array();
    }

    /**
     * Remove knowledge_base attachment from server and database
     * @param  mixed $id attachmentid
     * @return boolean
     */
    public function remove_knowledge_base_attachment($id)
    {
        $deleted         = false;
        // Get the attachment
        $this->db->where('id', $id);
        $attachment = $this->db->get(db_prefix() . 'files')->row();

        if ($attachment) {
            if (empty($attachment->external)) {
                $relPath  = FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id . '/';
                $fullPath = $relPath . $attachment->file_name;
                unlink($fullPath);
                $fname     = pathinfo($fullPath, PATHINFO_FILENAME);
                $fext      = pathinfo($fullPath, PATHINFO_EXTENSION);
                $thumbPath = $relPath . $fname . '_thumb.' . $fext;
                if (file_exists($thumbPath)) {
                    unlink($thumbPath);
                }
            }

            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('Knowledge Base Attachment Deleted [KnowledgeBaseID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id);
                }
            }
        }

        if ($deleted) {
            return ['success' => $deleted];
        }
    }
    public function remove_knowledge_base_all($id){
        $deleted         = false;
        // Get the attachment
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type',KNOWLEDGE_BASE_MODULE_NAME);
        $attachment = $this->db->get(db_prefix() . 'files')->row();

        if ($attachment) {
            delete_dir(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id);

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', KNOWLEDGE_BASE_MODULE_NAME);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('Attachment Deleted [ID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id);
                }
            }
        }

        if ($deleted) {
            return ['success' => $deleted];
        }

    }
}
