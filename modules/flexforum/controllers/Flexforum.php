<?php

use Carbon\Carbon;
use app\modules\flexforum\services\HasCommunity;

defined('BASEPATH') or exit('No direct script access allowed');

class Flexforum extends AdminController
{
    use HasCommunity;

    public function index()
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        $data['title'] = flexforum_lang();

        $this->app_scripts->add('flexforum-js', module_dir_url('flexforum', 'assets/js/flexforum.js'));

        $this->load->view('admin/index', $data);
    }
    
      
    
    public function bans()
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }
        $this->load->model('flexforum/flexforumban_model');

        $post_data = $this->input->post();

        if ($post_data) {
            if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'create')) {
                access_denied(FLEXFORUM_MODULE_NAME);
            }

            $this->load->model('flexforum/flexforumtopic_model');
            $this->load->model('flexforum/flexforumreply_model');
            $this->load->model('flexforum/flexforumlike_model');
            $this->load->model('flexforum/flexforumfollower_model');

            $this->db->trans_begin();

            try {
                $changed_at = to_sql_date(Carbon::now()->toDateTimeString(), true);

                $post_data = array_merge(
                    $post_data,
                    [
                        'date_updated' => $changed_at,
                        'date_added' => $changed_at,
                        'user_type' => FLEXFORUM_CLIENT_USER_TYPE,
                    ]
                );

                $conditions = [
                    'user_id' => $post_data['user_id'],
                    'user_type' => $post_data['user_type']
                ];

                $this->flexforumtopic_model->ban($conditions);
                $this->flexforumreply_model->ban($conditions);
                $this->flexforumlike_model->ban($conditions);
                $this->flexforumfollower_model->ban($conditions);

                $success = $this->flexforumban_model->add($post_data);
                $this->db->trans_commit();

                if ($success) {
                    set_alert('success', flexforum_lang('user_banned'));
                }

                redirect(flexforum_admin_url('bans'));
            } catch (\Throwable $th) {
                $this->db->trans_rollback();
                throw $th;
            }

        }

        $data['title'] = flexforum_lang('banned_users');
        $data['contacts'] = flexforum_get_contacts();
        $data['bans'] = $this->flexforumban_model->all();

        foreach ($data['bans'] as &$ban) {
            $ban = flexforum_enrich_ban($ban);
        }

        $this->load->view('admin/bans', $data);
    }
    
    public function delete_ban($ban_id = '')
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'edit')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if(empty($ban_id)){
            redirect(flexforum_admin_url('bans'));
        }

        $this->load->model('flexforum/flexforumban_model');
        $this->load->model('flexforum/flexforumtopic_model');
        $this->load->model('flexforum/flexforumreply_model');
        $this->load->model('flexforum/flexforumlike_model');
        $this->load->model('flexforum/flexforumfollower_model');

        $this->db->trans_begin();

        try {
            $ban = $this->flexforumban_model->get([
                'id' => $ban_id
            ]);

            $conditions = [
                'user_id' => $ban['user_id'],
                'user_type' => $ban['user_type']
            ];

            $this->flexforumtopic_model->ban($conditions, false);
            $this->flexforumreply_model->ban($conditions, false);
            $this->flexforumlike_model->ban($conditions, false);
            $this->flexforumfollower_model->ban($conditions, false);

            $success = $this->flexforumban_model->delete(['id' => $ban_id]);
            $this->db->trans_commit();

            if ($success) {
                set_alert('success', flexforum_lang('user_ban_lifted'));
            }

            redirect(flexforum_admin_url('bans'));
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

    public function categories()
    {
       
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }
        $this->load->model('flexforum/flexforumcategory_model');

        $post_data = $this->input->post();

        if ($post_data) {
            
            if (array_key_exists('id', $post_data)) {
                if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'edit')) {
                    access_denied(FLEXFORUM_MODULE_NAME);
                }

                $id = $post_data['id'];
                unset($post_data['id']);
               
                $post_data['slug'] = slug_it($post_data['name']);
                $success = $this->flexforumcategory_model->update($id, $post_data);

                if ($success) {
                    set_alert('success', flexforum_lang('category_updated_successfully'));
                }
            } else {
                if (!has_permission('flexforum', '', 'create')) {
                    access_denied('flexforum');
                }
                $post_data['slug'] = slug_it($post_data['name']);
               
                $success = $this->flexforumcategory_model->add($post_data);

                if ($success) {
                    set_alert('success', flexforum_lang('category_added_successfully'));
                }
            }

            echo json_encode([
                'success' => $success ? true : false,
            ]);
        }

        $data['title'] = flexforum_lang('categories');
        $data['categories'] = $this->flexforumcategory_model->parent_all();
        $data['child_categories'] = $this->flexforumcategory_model->all();

        $this->load->view('admin/categories/index', $data);
    }

    public function get_child_chatagory() {
        $parent_id = $this->input->post('parent_id');
        $this->load->model('flexforum/flexforumcategory_model');
        $data = $this->flexforumcategory_model->get_parent_id_by_child_cat($parent_id);
        echo json_encode($data); // Output JSON encoded data
      }

    public function get_model_details() {
        $modelId = $this->input->post('modelId');  
        $this->load->model('flexforum/Flexforummodellist_model');
        $data = $this->Flexforummodellist_model->get_model_id_by_list($modelId);
        echo json_encode($data);
    }

    public function get_replay_by_id() {
        $this->load->model('flexforum/Flexforumreply_model');
        $id = $this->input->post('type_id');
        $data = $this->Flexforumreply_model->get_replay_filename($id);
        echo json_encode($data);
    }

    public function delete_replay_file() {
        $this->load->model('flexforum/Flexforumreply_model');
        $id = $this->input->post('type_id');
        $data = $this->Flexforumreply_model->get_replay_filename($id);

        $filename = $data->replay_file;

        $success = $this->Flexforumreply_model->delete_replay_file($id);

        $removeoldfile = module_dir_path(FLEXFORUM_MODULE_NAME . '/uploads/replay_file');

        $OldtargetDir = $removeoldfile . $filename;

        if (file_exists($OldtargetDir)) {
            unlink($OldtargetDir);
        }
        echo json_encode($success);
    }


    public function delete_category($category_id = 0)
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'delete')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if (!$category_id) {
            redirect(flexforum_admin_url('categories'));
        }

        $this->db->trans_begin();

        try {
            // Will be needed when we create the QA model
            $this->load->model('flexforum/flexforumtopic_model');
            $this->load->model('flexforum/flexforumcategory_model');

            $this->flexforumtopic_model->delete_by_category($category_id);
            $deleted = $this->flexforumcategory_model->delete(['id' => $category_id]);

            if ($deleted) {
                set_alert('success', flexforum_lang('category_deleted_successfully'));

                $this->db->trans_commit();
            } else {
                set_alert('danger', flexforum_lang('category_deletion_failed'));

                $this->db->trans_rollback();
            }

            redirect(flexforum_admin_url('categories'));
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

    public function settings()
    {
        if ($_post = $this->input->post()) {
            
            $updated = update_option(FLEXFORUM_SEND_EMAIL_NOTIFICATION_OPTION, $_post['settings'][FLEXFORUM_SEND_EMAIL_NOTIFICATION_OPTION]);

            if ($updated) {
                set_alert('success', flexforum_lang('settings_updated'));
            }

            redirect(flexforum_admin_url('settings'));
        }

        $data['title'] = flexforum_lang('settings');

        $this->load->view('admin/settings', $data);
    }

    public function model_list()
    {
        $this->load->model('flexforum/flexforumcategory_model');

        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }
        $this->load->model('flexforum/Flexforummodellist_model');

        $post_data = $this->input->post();
        
        if ($post_data) {
            
            if (array_key_exists('id', $post_data)) {
                if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'edit')) {
                    access_denied(FLEXFORUM_MODULE_NAME);
                }

                $id = $post_data['id'];
                unset($post_data['id']);
               
                $post_data['model_name'] = $post_data['model_name'];
                $post_data['brand'] = $post_data['brand'];
                $post_data['code'] = $post_data['code'];

                $success = $this->Flexforummodellist_model->update($id, $post_data);

                if ($success) {
                    set_alert('success', flexforum_lang('modal_list_updated_successfully'));
                }
            } else {
                if (!has_permission('flexforum', '', 'create')) {
                    access_denied('flexforum');
                }
                
                $post_data['model_name'] = $post_data['model_name'];
                $post_data['brand'] = $post_data['brand'];
                $post_data['code'] = $post_data['code'];

                $success = $this->Flexforummodellist_model->add($post_data);

                if ($success) {
                    set_alert('success', flexforum_lang('modal_list_added_successfully'));
                }
            }

            echo json_encode([
                'success' => $success ? true : false,
            ]);
        }

        $data['title'] = flexforum_lang('model_list');
        $data['model_lists'] = $this->Flexforummodellist_model->get_all();
        $this->load->view('admin/model_list', $data);
    }



    public function delete_model_list($model_id = 0)
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'delete')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if (!$model_id) {
            redirect(flexforum_admin_url('model_list'));
        } else{
            $this->load->model('flexforum/Flexforummodellist_model');
            $deleted = $this->Flexforummodellist_model->delete(['id' => $model_id]);
            if ($deleted) {
                set_alert('success', flexforum_lang('model_list_deleted_successfully'));
            } else {
                set_alert('danger', flexforum_lang('model_list_deletion_failed'));
            }
        }
        
        redirect(flexforum_admin_url('model_list'));
    }
}