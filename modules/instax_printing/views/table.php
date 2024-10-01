<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    '1', // bulk actions
    db_prefix() . 'instax_printing_inquery.id as id',
    db_prefix() . 'instax_printing_inquery.frame_type as frame_type',
    'status',
    db_prefix() . 'instax_printing_inquery.name as name',
    'contact',
    'email',
    'order_number_image',
    db_prefix() . 'instax_printing_order_from.name as order_from_name',
    'order_number',
    'address',
    'shipping_image',
    'amount',
    'paymentdate',
    'attachment_url',
    'created_date'
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'instax_printing_inquery';
$CI = &get_instance();
$where = array();
$join = array();
$join = [
    
    'LEFT JOIN ' . db_prefix() . 'instax_printing_order_from ON ', db_prefix() . 'instax_printing_order_from.id = ' . db_prefix() . 'instax_printing_inquery.order_from'
];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    $row[] = $aRow['id'];
    
    $row[] = $aRow['status'] == 1 ? 'On Hold' : 'Completed';
    $row[] = $aRow['frame_type'];
    $row[] = $aRow['name'];
    $row[] = $aRow['contact'];
    $row[] = $aRow['email'];
    $row[] = $aRow['order_number_image'] != '' ? '<img src="'.$aRow['order_number_image'].'" width="100px" height="100px" alt="No Image">' : '';
    $row[] = $aRow['order_from_name'];
    $row[] = $aRow['order_number'];
    $row[] = $aRow['address'];
    $row[] = $aRow['shipping_image'] != '' ? '<a href="'.$aRow['shipping_image'].'" target="_blank">Open File</a>' : '';
    $row[] = $aRow['amount'];
    $row[] = $aRow['paymentdate'] != '' ? date("Y-m-d",strtotime($aRow['paymentdate'])) : '';
    $row[] = $aRow['attachment_url'] != '' ? '<a href="'.$aRow['attachment_url'].'" target="_blank">Open File</a>' : '';
    $row[] = date("Y-m-d",strtotime($aRow['created_date']));
    $options = "";
    

    $options .= icon_btn(admin_url('instax_printing/print_page_view/' . $aRow['id']), 'fa-regular fa-file-pdf', 'btn-warning', array('target' => '_blank'));
    // $options .= icon_btn(admin_url('instax_printing/delete_print_page/' . $aRow['id']), 'fa-regular fa-edit', 'btn-info');
    $options .= icon_btn(admin_url('instax_printing/delete_print_page/' . $aRow['id']), 'fa-regular fa-trash-can', 'btn-danger _delete');
    // $options .= icon_btn(admin_url('instax_printing/delete_print_page/' . $aRow['id']), 'fa-regular fa-money', 'btn-danger');
    if($aRow['attachment_url'] == '' || $aRow['paymentdate'] == '' || $aRow['amount'] == ''){
        $options .= '<button class="btn btn-primary btn-icon btn-payment" data-id = "' . $aRow['id'] . '"><i class="fa fa-dollar-sign"></i></button>';
    }else{
        $options .= '<button class="btn btn-primary btn-icon btn-payment-edit" data-id = "' . $aRow['id'] . '"  data-amount = "' . $aRow['amount'] . '" data-paymentdate = "' . $aRow['paymentdate'] . '" data-attachment_url = "' . $aRow['attachment_url'] . '" ><i class="fa fa-edit"></i><i class="fa fa-dollar-sign"></i></button>';
    }
    
   


    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
