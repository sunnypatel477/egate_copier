<?php

use Carbon\Carbon;

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Discussion and Community Forum Module
Description: Join the conversation with Discussion and Community Forum Module for Perfex .
Version: 1.0.1
Requires at least: 2.3.*
*/

const FLEXFORUM_MODULE_NAME = 'flexforum';
const FLEXFORUM_CLIENT_PATH_PREFIX = 'community';
const FLEXFORUM_CLIENT_USER_TYPE = 'client';
const FLEXFORUM_STAFF_USER_TYPE = 'staff';
const FLEXFORUM_TOPIC_LIKE_TYPE = 'topic';
const FLEXFORUM_REPLY_LIKE_TYPE = 'reply';
const FLEXFORUM_TOPIC_REPLY_TYPE = 'topic';
const FLEXFORUM_REPLY_REPLY_TYPE = 'reply';
const FLEXFORUM_TOPIC_FOLLOWER_TYPE = 'topic';
const FLEXFORUM_TOPIC_MODULE_TYPE = 'topic';
const FLEXFORUM_REPLY_FOLLOWER_TYPE = 'reply';
const FLEXFORUM_LIKES_TABLE = 'flexforumlikes';
const FLEXFORUM_REPLIES_TABLE = 'flexforumreplies';
const FLEXFORUM_FOLLOWERS_TABLE = 'flexforumfollowers';
const FLEXFORUM_MODULES_TABLE = 'flexforummodellist';
const FLEXFORUM_MODULES_TYPE = 'topic';
const FLEXFORUM_IS_PRIMARY_CONTACT = 1;
const FLEXFORUM_REPLY_NOTIFICATION_SLUG = 'flexforum-reply-notification';
const FLEXFORUM_SEND_EMAIL_NOTIFICATION_OPTION = 'flexforum-send-email-notification';

hooks()->add_action('admin_init', FLEXFORUM_MODULE_NAME . '_permissions');
hooks()->add_action('admin_init', FLEXFORUM_MODULE_NAME . '_module_init_menu_items');
hooks()->add_action('before_delete_contact', FLEXFORUM_MODULE_NAME . '_contact_deleted');
hooks()->add_action('before_delete_staff_member', FLEXFORUM_MODULE_NAME . '_before_delete_staff_member');

/**
 * Register activation module hook
 */
register_activation_hook(FLEXFORUM_MODULE_NAME, FLEXFORUM_MODULE_NAME . '_module_activation_hook');
define('FLEXFORUM_MODULE_NAME_UPLOAD', module_dir_path(FLEXFORUM_MODULE_NAME, 'uploads'));
/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(FLEXFORUM_MODULE_NAME, [FLEXFORUM_MODULE_NAME]);

register_merge_fields("flexforum/merge_fields/flexforum_notification_merge_fields");

hooks()->add_action('clients_init', 'flexforum_clients_area_menu_items');

function flexforum_clients_area_menu_items()
{
    // Show menu item only if client contact is logged in
    // and not banned
    if (is_client_logged_in() && !flexforum_banned([
        'user_id' => flexforum_get_user_id(),
        'user_type' => FLEXFORUM_CLIENT_USER_TYPE
    ])) {
        add_theme_menu_item('flexforum', [
            'name' => _l('flexforum_community_forum'),
            'href' => flexforum_client_url(),
            'position' => 40,
        ]);
    }
}

function flexforum_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function flexforum_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities(FLEXFORUM_MODULE_NAME, $capabilities, _l(FLEXFORUM_MODULE_NAME));
}

/**
 * Init flexforum module menu items in setup in admin_init hook
 * @return null
 */
function flexforum_module_init_menu_items()
{
    $CI = &get_instance();
    if (has_permission(FLEXFORUM_MODULE_NAME, '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item(FLEXFORUM_MODULE_NAME, [
            'name' => _l(FLEXFORUM_MODULE_NAME),
            // The name if the item
            'href' => admin_url(FLEXFORUM_MODULE_NAME),
            // URL of the item
            'position' => 40,
            // The menu position, see below for default positions.
            'icon' => 'fa-solid fa-chalkboard',
            // Font awesome icon
        ]);
    }
}

/**
 * Get the admin url using the given path for the module
 *
 * @param string $path
 * @return string
 */
function flexforum_admin_url($path = '')
{
    return admin_url(FLEXFORUM_MODULE_NAME . '/' . $path);
}

/**
 * Get the admin url using the given path for the module
 *
 * @param string $path
 * @return string
 */
function flexforum_client_url($path = '', $path_prefix = '')
{
    $path_prefix = empty($path_prefix) ? FLEXFORUM_CLIENT_PATH_PREFIX : $path_prefix;
    $path = empty($path_prefix) ? $path : $path_prefix . '/' . $path;

    return site_url(FLEXFORUM_MODULE_NAME . '/' . $path);
}

/**
 * Get the translation for a given line in a module
 *
 * @param string $line
 * @param string $module
 * @return string
 */
function flexforum_lang($line = '', $module = '', $translate = true)
{
    $line = empty($line) ? $line : '_' . $line;

    if ($module) {
        $line = $module . $line;
        return $translate ? _l($line) : $line;
    }

    $line = FLEXFORUM_MODULE_NAME . $line;
    return $translate ? _l($line) : $line;
}

/**
 * Prefix string with module name
 *
 * @param string $string
 * @return string
 */
function flexforum_module_prefix($string)
{
    return FLEXFORUM_MODULE_NAME . '_' . $string;
}

function flexforum_get_categories()
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumcategory_model');

    return $CI->flexforumcategory_model->all();
}

function flexforum_get_model_list()
{
    $CI = &get_instance();
    $CI->load->model('flexforum/Flexforummodellist_model');

    return $CI->Flexforummodellist_model->get_model_list();
}

function flexforum_get_parent_categories()
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumcategory_model');

    return $CI->flexforumcategory_model->parent_all();
}

function flexforum_get_category($category_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumcategory_model');

    return $CI->flexforumcategory_model->get([
        'id' => $category_id
    ]);
}

function flexforum_get_child_category($category_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumcategory_model');

    return $CI->flexforumcategory_model->get([
        'id' => $category_id
    ]);
}

function flexforum_get_category_name($category_id)
{
    $category = flexforum_get_category($category_id);

    if ($category) {
        return $category['name'];
    }

    return '';
}


function flexforum_get_child_category_name($parent_id)
{
    $child_category = flexforum_get_child_category($parent_id);
    $CI = &get_instance();
    $CI->db->select('name');
    $CI->db->from(db_prefix() . 'flexforumcategories');
    if (!empty($conditions)) {
        $CI->db->where('parent_id', $parent_id);
    }
    $query = $CI->db->get();
    $child_category = $query->result_array();

    if ($child_category) {
        return $child_category['name'];
    }

    return '';
}


function flexforum_get_child_category_name_main()
{
    $CI = &get_instance();
    $CI->db->select('a.id, b.name as parent_name, a.name as child_name, a.slug, a.parent_id');
    $CI->db->from('tblflexforumcategories as a');
    $CI->db->join('tblflexforumcategories as b', 'b.id = a.parent_id', 'left');
    $query = $CI->db->get();
    return $query->result_array();
}

function flexforum_get_topics($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->all($conditions);
}

function flexforum_get_followers($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumfollower_model');

    return $CI->flexforumfollower_model->all($conditions);
}

function flexforum_get_follower($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumfollower_model');

    return $CI->flexforumfollower_model->get($conditions);
}

function flexforum_get_likes($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumlike_model');

    return $CI->flexforumlike_model->all($conditions);
}

function flexforum_get_like($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumlike_model');

    return $CI->flexforumlike_model->get($conditions);
}

function flexforum_get_topics_with_relations($conditions = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->query_all($conditions);
}

function flexforum_get_topic($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->get($conditions);
}

function flexforum_get_topic_with_relations($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->query_get($conditions);
}

function flexforum_get_topics_partial()
{
    $CI = &get_instance();

    $conditions = [];
    $category_id = $CI->input->get('category-id');
    $search_query = $CI->input->get('q');

    if ($category_id) {
        $conditions = array_merge($conditions, [
            'category = ' => $category_id
        ]);
    }

    if ($search_query) {
        $conditions = array_merge($conditions, [
            'title' => $search_query
        ]);
    }

    $conditions = array_merge($conditions, ['banned' => 0]);

    $data['categories'] = flexforum_get_parent_categories();
    $data['topics'] = flexforum_get_topics_with_relations($conditions);
   
    return $CI->load->view('partials/topics', $data, true);
}

function flexforum_get_topic_partial()
{
    $CI = &get_instance();
    return $CI->load->view('partials/topic', [], true);
}

function flexforum_has_permission($ability)
{
    return has_permission(FLEXFORUM_MODULE_NAME, '', $ability);
}

function flexforum_add_topic($post)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');
    $CI->load->model('flexforum/flexforumfollower_model');


    $id = $CI->flexforumtopic_model->add($post);

    if ($id) {
        $post = [
            'user_type' => flexforum_get_user_type(),
            'user_id' => flexforum_get_user_id(),
            'type' => FLEXFORUM_TOPIC_FOLLOWER_TYPE,
            'type_id' => $id
        ];
        

        $CI->flexforumfollower_model->add($post);
    }

    return $id;
}

function flexforum_update_topic($post)
{

    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    $id = $post['id'];
    unset($post['id']);
    return $CI->flexforumtopic_model->update($id, $post);
}

function flexforum_add_reply($post)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');
    $CI->load->model('flexforum/flexforumfollower_model');

    $id = $CI->flexforumreply_model->add($post);

    if ($id) {
        $follower_data = [
            'user_id' => flexforum_get_user_id(),
            'user_type' => flexforum_get_user_type(),
            'type' => FLEXFORUM_REPLY_FOLLOWER_TYPE,
            'type_id' => $id
        ];

        $CI->flexforumfollower_model->add($follower_data);
    }

    return $id;
}

function flexforum_update_reply($post)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');

    $id = $post['id'];
    unset($post['id']);
    return $CI->flexforumreply_model->update($id, $post);
}


/**
 * Get the user type of the logged in user
 * 
 * @return string
 */
function flexforum_get_user_type()
{
    return is_client_logged_in() ? FLEXFORUM_CLIENT_USER_TYPE : FLEXFORUM_STAFF_USER_TYPE;
}

/**
 * Get the user id of the logged in user
 * 
 * @return string
 */
function flexforum_get_user_id()
{
    // flexforum_get_client_contact()
    return is_client_logged_in() ? get_contact_user_id() : get_staff_user_id();
}

/**
 * Get random hexadecimal code
 *
 * @param integer $length
 * @return string
 */
function flexforum_get_random($length = 3)
{
    return bin2hex(random_bytes($length));
}

function flexforum_has_updated_topic_title($topic_id, $new_title)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    $topic = $CI->flexforumtopic_model->get([
        'id' => $topic_id
    ]);

    return $topic['title'] != $new_title;
}

function flexforum_get_url($path = '', $prefix = '')
{
    if(is_client_logged_in()){
        return flexforum_client_url($path, $prefix);
    } elseif (is_staff_logged_in()) {
       return flexforum_admin_url($path);
    } else {
        return site_url('authentication/login');
    }
}

function flexforum_get_redirect_url($path = '')
{
    return flexforum_get_url($path);
}

function flexforum_count_topic_for_category($category_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->count([
        'category' => $category_id
    ]);
}

function flexforum_count_topic_for_child_category_data($category_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    return $CI->flexforumtopic_model->count([
        'childcategory' => $category_id
    ]);
}

function flexforum_count_topic_for_parent_category($parent_id)
{
    $CI = &get_instance();

    $CI->load->model('flexforum/flexforumtopic_model');

    $CI->db->select('id, name');
    $CI->db->where('parent_id', $parent_id);
    $query = $CI->db->get(db_prefix() . 'flexforumcategories');

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return array();
    }
}

function flexforum_count_topic_for_child_category($parent_id)
{
    $CI = &get_instance();

    $CI->load->model('flexforum/flexforumtopic_model');

    $CI->db->select('id, name');
    $CI->db->where('id', $parent_id);
    $query = $CI->db->get(db_prefix() . 'flexforumcategories');

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return array();
    }
}



function flexforum_user_can_edit_topic($topic_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumtopic_model');

    if (is_client_logged_in()) {
        $user_type = FLEXFORUM_CLIENT_USER_TYPE;
        $user_id = flexforum_get_user_id();
        $conditions = [
            'id' => $topic_id,
            'user_id' => $user_id,
            'user_type' => $user_type
        ];

        return $CI->flexforumtopic_model->get($conditions);
    }

    return true;
}

function flexforum_get_category_filter_url($category_id)
{
    return current_url() . '?category-id=' . $category_id;
}

function flexforum_get_topic_url($topic_slug)
{
    return flexforum_get_url('topic/' . $topic_slug);
}

function flexforum_get_user_name($user_id, $user_type)
{
    if ($user_type == FLEXFORUM_STAFF_USER_TYPE) {
        return get_staff_full_name($user_id);
    } else {

        return get_contact_full_name($user_id);
    }
}

function flexforum_get_user_email($user_id, $user_type)
{
    if ($user_type == FLEXFORUM_STAFF_USER_TYPE) {
        $staff = get_staff($user_id);

        return $staff ? $staff->email : false;
    } else {
        $contact = flexforum_get_contact([
            'userid' => $user_id,
            'is_primary' => FLEXFORUM_IS_PRIMARY_CONTACT
        ]);

        return $contact ? $contact['email'] : false;
    }
}

function flexforum_get_poster_image($user_id, $user_type)
{
    if ($user_type == FLEXFORUM_STAFF_USER_TYPE) {
        return staff_profile_image_url($user_id);
    } else {
        return contact_profile_image_url(get_primary_contact_user_id($user_id));
    }
}

function flexforum_get_topic_liked($topic_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumlike_model');
    $conditions = [
        'type_id' => $topic_id,
    ];

    if (is_client_logged_in()) {
        $conditions = array_merge($conditions, [
            'user_id' => get_client_user_id(),
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE,
            'type' => FLEXFORUM_TOPIC_LIKE_TYPE
        ]);

        return $CI->flexforumlike_model->get($conditions);
    }

    $conditions = array_merge($conditions, [
        'user_id' => get_staff_user_id(),
        'user_type' => FLEXFORUM_STAFF_USER_TYPE,
        'type' => FLEXFORUM_TOPIC_LIKE_TYPE
    ]);

    return !empty($CI->flexforumlike_model->get($conditions));
}

function flexforum_get_topic_followed($topic_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumfollower_model');
    $conditions = [
        'type_id' => $topic_id,
    ];

    if (is_client_logged_in()) {
        $conditions = array_merge($conditions, [
            'user_id' => get_client_user_id(),
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE,
            'type' => FLEXFORUM_TOPIC_LIKE_TYPE
        ]);

        return $CI->flexforumfollower_model->get($conditions);
    }

    $conditions = array_merge($conditions, [
        'user_id' => get_staff_user_id(),
        'user_type' => FLEXFORUM_STAFF_USER_TYPE,
        'type' => FLEXFORUM_TOPIC_LIKE_TYPE
    ]);

    return !empty($CI->flexforumfollower_model->get($conditions));
}

function flexforum_get_reply_liked($reply_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumlike_model');
    $conditions = [
        'type_id' => $reply_id,
    ];

    if (is_client_logged_in()) {
        $conditions = array_merge($conditions, [
            'user_id' => get_client_user_id(),
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE,
            'type' => FLEXFORUM_REPLY_LIKE_TYPE
        ]);

        return $CI->flexforumlike_model->get($conditions);
    }

    $conditions = array_merge($conditions, [
        'user_id' => get_staff_user_id(),
        'user_type' => FLEXFORUM_STAFF_USER_TYPE,
        'type' => FLEXFORUM_REPLY_LIKE_TYPE
    ]);

    return !empty($CI->flexforumlike_model->get($conditions));
}

function flexforum_get_reply_followed($reply_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumfollower_model');
    $conditions = [
        'type_id' => $reply_id,
    ];

    if (is_client_logged_in()) {
        $conditions = array_merge($conditions, [
            'user_id' => get_client_user_id(),
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE,
            'type' => FLEXFORUM_REPLY_FOLLOWER_TYPE
        ]);

        return $CI->flexforumfollower_model->get($conditions);
    }

    $conditions = array_merge($conditions, [
        'user_id' => get_staff_user_id(),
        'user_type' => FLEXFORUM_STAFF_USER_TYPE,
        'type' => FLEXFORUM_REPLY_FOLLOWER_TYPE
    ]);

    return !empty($CI->flexforumfollower_model->get($conditions));
}

function flexforum_get_contact($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumcontact_model');

    return $CI->flexforumcontact_model->get($conditions);
}

function flexforum_get_reply($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');

    return $CI->flexforumreply_model->get($conditions);
}

function flexforum_get_reply_with_relations($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');

    return $CI->flexforumreply_model->query_get($conditions);
}

function flexforum_get_replies($conditions = [], $sortings = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');

    return $CI->flexforumreply_model->all($conditions, $sortings);
}

function flexforum_get_replies_with_relations($conditions = [], $sortings = [])
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumreply_model');
    $final_array = [];
    $topics =  $CI->flexforumreply_model->query_all($conditions, $sortings);
    
    
    foreach($topics as $b){
        $conditions = [
            'type' => 'reply',
            'type_id' => $b['id']
        ]; 
        $sortings=  [
            [
                'field' => 'date_added',
                'order' => 'desc'
            ]
            ];
            $replies =  $CI->flexforumreply_model->query_all($conditions, $sortings);
            $temp = $b;
            if(!empty($replies)){
                // $topics = array_merge($topics, $replies);
                $temp['repaly'] = $replies;
            }
            
            $final_array[] = $temp;   
            
    }
    
    return $final_array;
    
}

function flexforum_enrich_reply($reply)
{
    $reply['poster_name'] = flexforum_get_user_name($reply['user_id'], $reply['user_type']);
    $reply['poster_image'] = flexforum_get_poster_image($reply['user_id'], $reply['user_type']);
    $reply['last_modified'] = Carbon::parse(_dt($reply['date_updated']))->diffForHumans();
    $reply['reply_liked'] = flexforum_get_reply_liked($reply['id']);
    $reply['reply_followed'] = flexforum_get_reply_followed($reply['id']);
    $reply['is_reply_owner'] = (flexforum_get_user_id() === $reply['user_id'] && flexforum_get_user_type() == $reply['user_type']);
    return $reply;
}

function flexforum_get_client_contact($client_user_id)
{
    $CI = &get_instance();
    $CI->load->model('flexforumcontact_model');

    return $CI->flexforumcontact_model->get([
        'userid' => $client_user_id
    ]);
}

function flexforum_enrich_ban($ban)
{
    $CI = &get_instance();
    $CI->load->model('flexforumcontact_model');

    $ban['name'] = flexforum_get_user_name($ban['user_id'], $ban['user_type']);
    return $ban;
}

function flexforum_get_contacts()
{
    $CI = &get_instance();
    $CI->load->model('clients_model');

    return $CI->clients_model->get_contacts();
}

function flexforum_banned($conditions)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumban_model');

    return $CI->flexforumban_model->banned($conditions);
}

function flexforum_contact_deleted($id)
{
    $CI = &get_instance();
    $CI->load->model('clients_model');
    $CI->load->model('flexforum/flexforumban_model');
    $CI->load->model('flexforum/flexforumtopic_model');
    $CI->load->model('flexforum/flexforumreply_model');
    $CI->load->model('flexforum/flexforumlike_model');

    $contact = $CI->clients_model->get_contact($id);

    if ($contact) {
        $conditions = [
            'user_id' => $contact->userid,
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE
        ];

        $CI->flexforumban_model->delete($conditions);
        $CI->flexforumlike_model->delete($conditions);
        $CI->flexforumreply_model->delete($conditions);
        $CI->flexforumtopic_model->delete($conditions);
    }
}

function flexforum_before_delete_staff_member($payload)
{
    $CI = &get_instance();
    $CI->load->model('flexforum/flexforumban_model');
    $CI->load->model('flexforum/flexforumtopic_model');
    $CI->load->model('flexforum/flexforumreply_model');
    $CI->load->model('flexforum/flexforumlike_model');

    if ($payload) {
        $conditions = [
            'user_id' => $payload['id'],
            'user_type' => FLEXFORUM_STAFF_USER_TYPE
        ];

        $CI->flexforumban_model->delete($conditions);
        $CI->flexforumlike_model->delete($conditions);
        $CI->flexforumreply_model->delete($conditions);
        $CI->flexforumtopic_model->delete($conditions);
    }
}

function flexforum_get_notification_link($type_id, $type)
{
    if ($type == FLEXFORUM_TOPIC_REPLY_TYPE) {
        $topic = flexforum_get_topic([
            'id' => $type_id
        ]);
    } else {
        $reply = flexforum_get_reply([
            'id' => $type_id
        ]);
        $topic = flexforum_get_topic([
            'id' => $reply['type_id']
        ]);
    }

    return flexforum_get_topic_url($topic['slug']);
}

function flexforum_send_reply_notification($type_id, $type)
{
    if (get_option(FLEXFORUM_SEND_EMAIL_NOTIFICATION_OPTION)) {
        $CI = &get_instance();
        $CI->load->library('flexforum/notifications_module');
        $CI->notifications_module->send($type_id, $type);
    }
}

function flexforum_get_topic_from_reply($reply_id)
{
    $reply = flexforum_get_reply([
        'id' => $reply_id
    ]);

    $type = $reply['type'];
    $type_id = $reply['type_id'];

    if ($type == FLEXFORUM_TOPIC_REPLY_TYPE) {
        $topic = flexforum_get_topic([
            'id' => $type_id
        ]);
    } else {
        $reply = flexforum_get_reply([
            'id' => $type_id
        ]);
        $topic = flexforum_get_topic([
            'id' => $reply['type_id']
        ]);
    }

    return $topic;
}

if (!is_client_logged_in()) {
    hooks()->add_action('customers_navigation_start', 'flexforum_client_show_client_menu');
    function flexforum_client_show_client_menu()
    {
        echo '<li class="">
      <a href="' . site_url('flexforum/Flexforum_client_view') . '">
         ' . _l('community_forum') . '
      </a>
   </li>';
    }
}
