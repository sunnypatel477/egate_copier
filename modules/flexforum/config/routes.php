<?php
defined('BASEPATH') or exit('No direct script access allowed');
$route['flexforum/community/follow'] = 'flexforum_client/follow';
$route['flexforum/community/like'] = 'flexforum_client/like';
$route['flexforum/community/topics'] = 'flexforum_client/topics';
$route['flexforum/community/replies'] = 'flexforum_client/replies';
$route['flexforum/community/delete_topic/(:any)'] = 'flexforum_client/delete_topic/$1';
$route['flexforum/community/delete_reply/(:any)'] = 'flexforum_client/delete_reply/$1';
$route['flexforum/community/topic/(:any)'] = 'flexforum_client/topic/$1';
$route['flexforum/community'] = 'flexforum_client/index';
