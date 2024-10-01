<?php

defined('BASEPATH') or exit('No direct script access allowed');
$route['knowledge-base']                 = 'knowledge_base/knowledge/index';
$route['knowledge-base/search']          = 'knowledge_base/knowledge/search';
$route['knowledge-base/article']         = 'knowledge_base/knowledge/index';
$route['knowledge-base/article/(:any)']  = 'knowledge_base/knowledge/article/$1';
$route['knowledge-base/category']        = 'knowledge_base/knowledge/index';
$route['knowledge-base/category/(:any)'] = 'knowledge_base/knowledge/category/$1';

$route['publish/(:any)'] = 'zillapage/publishlandingpage/index/$1';
$route['publish/thankyou/(:any)'] = 'zillapage/publishlandingpage/thankyou/$1';