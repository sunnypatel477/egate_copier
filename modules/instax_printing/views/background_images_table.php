<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'instax_printing_background_images.id as id',
    'image_name',
    'type',
    db_prefix() . 'instax_printing_background_category.name as category_name',
    db_prefix() . 'instax_printing_event_category.name as event_category_name',
    // 'category',
    'background_url',
    'raw_url',
   
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'instax_printing_background_images ';
$CI = &get_instance();
$where = [];
$join = array();
$join = [
    'LEFT JOIN ' . db_prefix() . 'instax_printing_background_category ON ', db_prefix() . 'instax_printing_background_category.id = ' . db_prefix() . 'instax_printing_background_images.category',
    'LEFT JOIN ' . db_prefix() . 'instax_printing_event_category ON ', db_prefix() . 'instax_printing_event_category.id = ' . db_prefix() . 'instax_printing_background_images.event_category'
];
if($this->ci->input->post('category_filter')){
    $category_filter = $this->ci->input->post('category_filter');
   if($category_filter != ''){
    // $where[] = 'category = '.$category_filter;
    array_push($where,' AND category = "'.$category_filter.'"');
   }
}
if($this->ci->input->post('type_filter')){
    $type_filter = $this->ci->input->post('type_filter');
   if($type_filter != ''){
    // $where[] = 'category = '.$category_filter;
    array_push($where,' AND type = "'.$type_filter.'"');
   }
}
if($this->ci->input->post('event_category_filter')){
    $event_category_filter = $this->ci->input->post('event_category_filter');
   if($event_category_filter != ''){
    // $where[] = 'category = '.$category_filter;
    array_push($where,' AND event_category = '.$event_category_filter.'');
   }
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['id'];
    $row[] = $aRow['image_name'];
    $row[] = $aRow['type'] == 'whole' ? 'Photo Book Style' : 'Individual';
//    $category = $aRow['category'];
//    switch ($category) {
//     case '1':
//         $category_name = 'Instax Mini';
//         break;
//     case '2':
//         $category_name = 'Instax Square';
//         break;
//     case '3':
//         $category_name = 'Instax Wide';
//         break;
//         default:
//         $category_name = 'Other';
//         break;
// }

    $row[] = $aRow['category_name'];
    $row[] = $aRow['event_category_name'];
    $rowName = '<img src="' . $aRow['background_url'] . '"  style="width: 150px;">';
    $row[] = $rowName;
    $row[] = $aRow['raw_url']!= '' ?  '<a href="' . $aRow['raw_url'] . '" target="_blank">View</a>' : '';
  
    $options = "";
    

    if ( is_admin()) {
        $options .= icon_btn(admin_url('instax_printing/delete_background/' . $aRow['id']), 'fa-regular fa-trash-can', 'btn-danger _delete');
        $options .= icon_btn(admin_url('instax_printing/create_background/' . $aRow['id']), 'fa-regular fa-edit', 'btn-info');
    }


    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
