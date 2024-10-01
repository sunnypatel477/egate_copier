<?php

namespace app\modules\flexforum\services;

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

trait HasCommunity
{
    public function topics()
    {
        $post = $this->input->post();

        unset($post['topic_file']);

        if ($post) {
            if (!$post['title']) {
                set_alert('danger', flexforum_lang('topic_title_required'));
                redirect(flexforum_get_redirect_url());
            }
            if (!$post['category']) {
                set_alert('danger', flexforum_lang('topic_category_required'));
                redirect(flexforum_get_redirect_url());
            }
            // if (!$post['childcategory']) {
            //     set_alert('danger', flexforum_lang('topic_childcategory_required'));
            //     redirect(flexforum_get_redirect_url());
            // }


            if (!$post['description']) {
                set_alert('danger', flexforum_lang('topic_description_required'));
                redirect(flexforum_get_redirect_url());
            }

            if (array_key_exists('closed', $post)) {
                $post['closed'] = $post['closed'] == 'on';
            } else {
                $post['closed'] = false;
            }

            $this->db->trans_begin();

            try {
                //code...
                $changed_at = to_sql_date(Carbon::now()->toDateTimeString(), true);

                $post = array_merge($post, [
                    'date_updated' => $changed_at,
                    'description' => html_purify($this->input->post('description', false))
                ]);

                if (array_key_exists('id', $post)) {
                    if (flexforum_has_updated_topic_title($post['id'], $post['title'])) {
                        $post = array_merge($post, [
                            // We append random character to avoid collision
                            'slug' => slug_it($post['title']) . '-' . flexforum_get_random()
                        ]);
                    }

                    if (!empty($_FILES['topic_file']['tmp_name']) && $_FILES['topic_file']['tmp_name'] != '') {

                        $this->load->model('flexforum/flexforumtopic_model');
                        $this->load->model('flexforum/flexforumreply_model');
                        $this->load->model('flexforum/flexforumlike_model');
                        $this->load->model('flexforum/flexforumfollower_model');

                        $get_topic_file = $this->flexforumreply_model->get_topic_filename($post['id']);

                        $topic_oldfilename = $get_topic_file->topic_file;

                        $removeoldfile = module_dir_path(FLEXFORUM_MODULE_NAME . '/uploads/topic_file');

                        $OldtargetDir = $removeoldfile . $topic_oldfilename;

                        if (file_exists($OldtargetDir)) {
                            unlink($OldtargetDir);
                        }

                        $topic_filefileDir = FLEXFORUM_MODULE_NAME_UPLOAD . '/topic_file/';
                        $tmpFilePath = $_FILES['topic_file']['tmp_name'];
                        _maybe_create_upload_path($topic_filefileDir);
                        $filename = unique_filename($topic_filefileDir, str_replace(" ", "", $_FILES['topic_file']['name']));
                        $newFilePath = $topic_filefileDir . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                    }

                    $post['topic_file'] = isset($filename) ? $filename : '';

                    if (flexforum_update_topic($post)) {
                        set_alert('success', flexforum_lang('topic_updated_successfully'));
                    } else {
                        set_alert('danger', flexforum_lang('topic_update_failed'));
                    }
                } else {
                    $post = array_merge($post, [
                        // We append random character to avoid collision
                        'slug' => slug_it($post['title']) . '-' . flexforum_get_random(),
                        'date_added' => $changed_at,
                        'user_type' => flexforum_get_user_type(),
                        'user_id' => flexforum_get_user_id()
                    ]);

                    if (!empty($_FILES['topic_file']['tmp_name']) && $_FILES['topic_file']['tmp_name'] != '') {

                        $topic_filefileDir = FLEXFORUM_MODULE_NAME_UPLOAD . '/topic_file/';
                        $tmpFilePath = $_FILES['topic_file']['tmp_name'];
                        _maybe_create_upload_path($topic_filefileDir);
                        $filename = unique_filename($topic_filefileDir, str_replace(" ", "", $_FILES['topic_file']['name']));
                        $newFilePath = $topic_filefileDir . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                    }
                    $post['topic_file'] = isset($filename) ? $filename : '';

                    if (flexforum_add_topic($post)) {
                        set_alert('success', flexforum_lang('topic_added_successfully'));
                    } else {
                        set_alert('danger', flexforum_lang('topic_addition_failed'));
                    }
                }

                $this->db->trans_commit();

                redirect(flexforum_get_redirect_url());
            } catch (\Throwable $th) {
                $this->db->trans_rollback();
                throw $th;
            }
        }
    }

    public function delete_topic($topic_id = '')
    {
        $conditions = [
            'user_id' => flexforum_get_user_id(),
            'user_type' => flexforum_get_user_type()
        ];

        $is_topic_owner = flexforum_get_topic($conditions);

        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'delete') && !$is_topic_owner) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if (!$topic_id) {
            redirect(flexforum_get_redirect_url());
        }

        $this->db->trans_begin();

        try {
            // Will be needed when we create the QA model
            $this->load->model('flexforum/flexforumtopic_model');
            $this->load->model('flexforum/flexforumreply_model');
            $this->load->model('flexforum/flexforumlike_model');
            $this->load->model('flexforum/flexforumfollower_model');

            $get_topic_file = $this->flexforumreply_model->get_topic_filename($topic_id);

            $topic_oldfilename = $get_topic_file->topic_file;

            unset($get_topic_file);

            $this->flexforumfollower_model->delete_by_topic($topic_id);
            $this->flexforumlike_model->delete_by_topic($topic_id);
            $this->flexforumreply_model->delete_by_topic($topic_id);

            $deleted = $this->flexforumtopic_model->delete([
                'id' => $topic_id
            ]);

            if ($deleted) {

                $topicoldfile = module_dir_path(FLEXFORUM_MODULE_NAME . '/uploads/topic_file');

                $OldtargetDir = $topicoldfile . $topic_oldfilename;

                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }

                set_alert('success', flexforum_lang('topic_deleted_successfully'));

                $this->db->trans_commit();
            } else {
                set_alert('danger', flexforum_lang('topic_deletion_failed'));

                $this->db->trans_rollback();
            }

            redirect(flexforum_get_redirect_url());
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

    public function topic($topic_id = '')
    {


        if (is_staff_logged_in() || is_admin()) {
            $this->app_css->add('flexforum-css', module_dir_url('flexforum', 'assets/css/flexforum.css'), 'admin', ['app-css']);
            $this->app_scripts->add('flexforum-js', module_dir_url('flexforum', 'assets/js/flexforum.js'));
        } else {
            $this->app_css->theme('flexforum-css', module_dir_url('flexforum', 'assets/css/flexforum.css'));
            $this->app_scripts->theme('tinymce-js', 'assets/plugins/tinymce/tinymce.min.js');
            $this->app_scripts->theme('flexforum-js', module_dir_url('flexforum', 'assets/js/flexforum.js'));
        }

        if ($this->input->is_ajax_request()) {
            $failure = false;

            if (!$topic_id) {
                echo json_encode([
                    'success' => $failure,
                    'message' => flexforum_lang('topic_not_found'),
                    'data' => []
                ]);
                die;
            }

            $topic = flexforum_get_topic([
                'id' => $topic_id
            ]);

            echo json_encode([
                'success' => !$failure,
                'message' => flexforum_lang('topic_found'),
                'data' => $topic
            ]);
            die;
        }




        $data['topic'] = flexforum_get_topic_with_relations([
            'slug' => $topic_id
        ]);

        if (!$data['topic']) {
            show_404();
        }



        $data['title'] = $data['topic']['title'];
        $data['category'] = flexforum_get_parent_categories($data['topic']['category']);
        $data['poster_name'] = flexforum_get_user_name($data['topic']['user_id'], $data['topic']['user_type']);
        $data['poster_image'] = flexforum_get_poster_image($data['topic']['user_id'], $data['topic']['user_type']);
        $data['last_modified'] = Carbon::parse(_dt($data['topic']['date_updated']))->diffForHumans();
        $data['topic_liked'] = flexforum_get_topic_liked($data['topic']['id']);
        $data['topic_followed'] = flexforum_get_topic_followed($data['topic']['id']);
        $data['replies'] = flexforum_get_replies_with_relations([
            'type' => FLEXFORUM_TOPIC_REPLY_TYPE,
            'type_id' => $data['topic']['id']
        ], [
            [
                'field' => 'date_added',
                'order' => 'desc'
            ]
        ]);

        foreach ($data['replies'] as &$reply) {
            $reply = flexforum_enrich_reply($reply);
        }

        if (is_client_logged_in()) {
            $this->app_css->theme('flexforum-css', module_dir_url('flexforum', 'assets/css/flexforum.css'));
            $this->app_scripts->theme('tinymce-js', 'assets/plugins/tinymce/tinymce.min.js');
            $this->app_scripts->theme('flexforum-js', module_dir_url('flexforum', 'assets/js/flexforum.js'));
            $this->data($data);
            $this->view('client/topic');
            $this->layout();
        } else {

            $this->load->view('admin/topic', $data);
        }
    }

    public function like()
    {
        $post = $this->input->post();

        if ($post) {
            $this->db->trans_begin();

            try {
                $like_data = [
                    'type' => $post['type'],
                    'type_id' => $post['typeId']
                ];
                $likes_count = count(flexforum_get_likes($like_data));

                $like_data = array_merge($like_data, [
                    'user_type' => flexforum_get_user_type(),
                    'user_id' => flexforum_get_user_id(),
                ]);
                $failure = false;
                $response = [
                    'success' => $failure,
                    'message' => flexforum_lang('topic_liked_failed'),
                    'data' => []
                ];

                if ($like = flexforum_get_like($like_data)) {
                    if ($this->flexforumlike_model->delete(['id' => $like['id']])) {
                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang($like_data['type'] == FLEXFORUM_TOPIC_LIKE_TYPE ? 'topic_unliked' : 'reply_unliked'),
                            'data' => [
                                'count' => --$likes_count
                            ]
                        ]);
                    }
                } else {
                    $like_data = array_merge($like_data, [
                        'date_added' => to_sql_date(Carbon::now()->toDateTimeString(), true)
                    ]);

                    if ($this->flexforumlike_model->add($like_data)) {
                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang($like_data['type'] == FLEXFORUM_TOPIC_LIKE_TYPE ? 'topic_liked' : 'reply_liked'),
                            'data' => [
                                'count' => ++$likes_count
                            ]
                        ]);
                    }
                }

                $this->db->trans_commit();

                echo json_encode($response);
                die;
            } catch (\Throwable $th) {
                $this->db->trans_rollback();
                throw $th;
            }
        }
    }

    public function follow()
    {
        $post = $this->input->post();

        if ($post) {
            $this->db->trans_begin();

            try {
                $follower_data = [
                    'type' => $post['type'],
                    'type_id' => $post['typeId']
                ];
                $followers_count = count(flexforum_get_followers($follower_data));

                $follower_data = array_merge($follower_data, [
                    'user_type' => flexforum_get_user_type(),
                    'user_id' => flexforum_get_user_id(),
                ]);
                $failure = false;
                $response = [
                    'success' => $failure,
                    'message' => flexforum_lang('following_topic_failed'),
                    'data' => []
                ];

                if ($follower = flexforum_get_follower($follower_data)) {
                    if ($this->flexforumfollower_model->delete(['id' => $follower['id']])) {
                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang($follower_data['type'] == FLEXFORUM_TOPIC_FOLLOWER_TYPE ? 'topic_unfollowed' : 'reply_unfollowed'),
                            'data' => [
                                'count' => --$followers_count
                            ]
                        ]);
                    }
                } else {
                    $follower_data = array_merge($follower_data, [
                        'date_added' => to_sql_date(Carbon::now()->toDateTimeString(), true)
                    ]);

                    if ($this->flexforumfollower_model->add($follower_data)) {
                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang($follower_data['type'] == FLEXFORUM_TOPIC_FOLLOWER_TYPE ? 'topic_followed' : 'reply_followed'),
                            'data' => [
                                'count' => ++$followers_count
                            ]
                        ]);
                    }
                }

                $this->db->trans_commit();

                echo json_encode($response);
                die;
            } catch (\Throwable $th) {
                $this->db->trans_rollback();
                throw $th;
            }
        }
    }

    public function replies()
    {
        $post = $this->input->post();



        if (array_key_exists('hidden_id', $post)) {

            $replayfailure = false;
            $replayresponse = [
                'success' => $replayfailure,
                'message' => '',
                'data' => []
            ];


            if (!empty($_FILES['replay_file']['tmp_name']) && $_FILES['replay_file']['tmp_name'] != '') {

                $replay_filefileDir = FLEXFORUM_MODULE_NAME_UPLOAD . '/replay_file/';
                $tmpFilePath = $_FILES['replay_file']['tmp_name'];
                _maybe_create_upload_path($replay_filefileDir);
                $filename = unique_filename($replay_filefileDir, str_replace(" ", "", $_FILES['replay_file']['name']));
                $newFilePath = $replay_filefileDir . $filename;
                move_uploaded_file($tmpFilePath, $newFilePath);
            }

            $post['replay_file'] = isset($filename) ? $filename : '';

            $updated['id'] = $post['hidden_id'];
            $updated['reply'] = $post['reply'];

            unset($post['hidden_id']);
            

            if (flexforum_update_reply($updated)) {
                set_alert('success', flexforum_lang('reply_updated_successfully'));
            } else {
                set_alert('danger', flexforum_lang('reply_update_failed'));
            }

            $topic = flexforum_get_topic_from_reply($updated['id']);

            redirect(flexforum_get_topic_url($topic['slug']));


        }

        if ($post) {
            $this->db->trans_begin();

            try {
                $failure = false;
                $response = [
                    'success' => $failure,
                    'message' => '',
                    'data' => []
                ];

                if (!$post['reply']) {
                    $response = array_merge($response, [
                        'message' => flexforum_lang('reply_required')
                    ]);

                    echo json_encode($response);
                    die;
                }

                $changed_at = to_sql_date(Carbon::now()->toDateTimeString(), true);

                $post = array_merge($post, [
                    'date_updated' => $changed_at,
                    'reply' => html_purify($this->input->post('reply', false))
                ]);



                if (array_key_exists('id', $post)) {

                    if (flexforum_update_reply($post)) {
                        $insert_id = $post['id'];
                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang('reply_updated_successfully')
                        ]);
                    } else {
                        $response = array_merge($response, [
                            'message' => flexforum_lang('reply_update_failed')
                        ]);
                    }
                } else {
                    $post = array_merge($post, [
                        'date_added' => $changed_at,
                        'user_type' => flexforum_get_user_type(),
                        'user_id' => flexforum_get_user_id()
                    ]);

                    if (!empty($_FILES['replay_file']['tmp_name']) && $_FILES['replay_file']['tmp_name'] != '') {

                        $replay_filefileDir = FLEXFORUM_MODULE_NAME_UPLOAD . '/replay_file/';
                        $tmpFilePath = $_FILES['replay_file']['tmp_name'];
                        _maybe_create_upload_path($replay_filefileDir);
                        $filename = unique_filename($replay_filefileDir, str_replace(" ", "", $_FILES['replay_file']['name']));
                        $newFilePath = $replay_filefileDir . $filename;
                        move_uploaded_file($tmpFilePath, $newFilePath);
                    }

                    $post['replay_file'] = isset($filename) ? $filename : '';

                    $insert_id = flexforum_add_reply($post);
                    if ($insert_id) {
                        flexforum_send_reply_notification($post['type_id'], $post['type']);

                        $response = array_merge($response, [
                            'success' => !$failure,
                            'message' => flexforum_lang('reply_added_successfully')
                        ]);
                    } else {
                        $response = array_merge($response, [
                            'message' => flexforum_lang('reply_addition_failed')
                        ]);
                    }
                }

                if ($insert_id) {
                    $reply = flexforum_get_reply_with_relations([
                        'id' => $insert_id
                    ]);

                    $reply = flexforum_enrich_reply($reply);
                    $content_data = [
                        'reply' => $reply,
                        'like_type' => FLEXFORUM_REPLY_LIKE_TYPE,
                        'follower_type' => FLEXFORUM_REPLY_FOLLOWER_TYPE,
                        'has_reply_form' => true
                    ];
                    $response['data'] = $this->load->view('partials/reply-content', $content_data, true);
                }

                $this->db->trans_commit();
                echo json_encode($response);
                die;
            } catch (\Throwable $th) {
                $this->db->trans_rollback();
                throw $th;
            }
        }

        $type_id = $this->input->get('type_id');
        $type = $this->input->get('type');
        $closed = $this->input->get('closed');


        if ($type_id && $type) {
            $this->load->model('flexforum/flexforumreply_model');

            $replies = $this->flexforumreply_model->query_all([
                'type_id' => $type_id,
                'type' => $type
            ], [
                [
                    'field' => 'date_added',
                    'order' => 'desc'
                ]
            ]);

            $html = '';
            foreach ($replies as &$reply) {
                $reply = flexforum_enrich_reply($reply);
                $data = [
                    'reply' => $reply,
                    'like_type' => FLEXFORUM_REPLY_REPLY_TYPE,
                    'is_secondary_reply' => $reply['type'] == FLEXFORUM_REPLY_REPLY_TYPE,
                    'has_reply_form' => $reply['type'] == FLEXFORUM_TOPIC_REPLY_TYPE,
                    'closed' => $closed
                ];
                $html .= $this->load->view('partials/reply-content', $data, true);
            }

            $response = [
                'success' => true,
                'data' => $html
            ];

        } else {
            $response = [
                'success' => false,
                'message' => flexforum_lang('missing_query_parameters')
            ];
        }

        echo json_encode($response);
        die;
    }

    public function delete_reply($reply_id = '')
    {
        $conditions = [
            'user_id' => flexforum_get_user_id(),
            'user_type' => flexforum_get_user_type()
        ];
        $is_reply_owner = flexforum_get_reply($conditions);

        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'delete') && !$is_reply_owner) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if (!$reply_id) {
            redirect(flexforum_get_redirect_url());
        }

        $this->db->trans_begin();

        try {
            $this->load->model('flexforum/flexforumreply_model');

            $topic = flexforum_get_topic_from_reply($reply_id);

            $get_replay_file = $this->flexforumreply_model->get_replay_filename($reply_id);

            $replay_oldfilename = $get_replay_file->replay_file;


            unset($get_replay_file);

            // Delete secondary replies
            $this->flexforumreply_model->delete([
                'type_id' => $reply_id,
                'type' => FLEXFORUM_REPLY_REPLY_TYPE
            ]);
            $deleted = $this->flexforumreply_model->delete([
                'id' => $reply_id
            ]);

            if ($deleted) {

                $replayoldfile = module_dir_path(FLEXFORUM_MODULE_NAME . '/uploads/replay_file');

                $OldtargetDir = $replayoldfile . $replay_oldfilename;

                if (file_exists($OldtargetDir)) {
                    unlink($OldtargetDir);
                }

                set_alert('success', flexforum_lang('reply_deleted_successfully'));

                $this->db->trans_commit();
            } else {
                set_alert('danger', flexforum_lang('reply_deletion_failed'));

                $this->db->trans_rollback();
            }

            redirect(flexforum_get_topic_url($topic['slug']));
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

    public function reply($reply_id = '')
    {
        if (!has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
            access_denied(FLEXFORUM_MODULE_NAME);
        }

        if ($this->input->is_ajax_request()) {
            $failure = false;
            $response = [
                'success' => $failure,
                'message' => flexforum_lang('reply_not_found'),
                'data' => []
            ];

            if (!$reply_id) {
                echo json_encode($response);
                die;
            }

            $reply = flexforum_get_reply([
                'id' => $reply_id
            ]);
            if (!$reply) {
                echo json_encode($response);
                die;
            }
            echo json_encode([
                'success' => !$failure,
                'message' => flexforum_lang('reply_found'),
                'data' => $reply
            ]);
            die;
        }
    }
}
