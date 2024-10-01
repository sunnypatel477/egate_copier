<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();


//============================================= my_expense.php
$my_expense = APPPATH . 'views/admin/expenses/my_expense.php';
$module_my_expense = module_dir_path(CHECKWRITER_MODULE) . 'system_changes/my_expense.php';
if (!file_exists($my_expense)) {
    copy($module_my_expense, $my_expense);
} // Done

if (!$CI->db->table_exists(db_prefix() . 'bank_details')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "bank_details` (
        `id` int(200) UNSIGNED NOT NULL AUTO_INCREMENT,
        `bank_name` varchar(200) NOT NULL,
        `account_no` varchar(200) NOT NULL,
        `account_notes` varchar(200) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->field_exists('bank', db_prefix() . 'expenses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
      ADD COLUMN `bank` varchar(200) NOT NULL AFTER `dateadded`
    ;");
}

if (!$CI->db->field_exists('check_no', db_prefix() . 'expenses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
      ADD COLUMN `check_no` int(11) NOT NULL AFTER `bank`
    ;");
}

if (!$CI->db->field_exists('check_date', db_prefix() . 'expenses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
      ADD COLUMN `check_date` DATE NULL AFTER `check_no`
    ;");
}

if (!$CI->db->field_exists('check_payee', db_prefix() . 'expenses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
      ADD COLUMN `check_payee` int(11) NOT NULL AFTER `check_date`
    ;");
}

if (!$CI->db->field_exists('issue_payment_check', db_prefix() . 'expenses')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
    ADD COLUMN `issue_payment_check` int(11) NOT NULL AFTER `check_date`
  ;");
}

if (!$CI->db->field_exists('vendor_payee', db_prefix() . 'expenses')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "expenses`
      ADD COLUMN `vendor_payee` varchar(200) NOT NULL AFTER `issue_payment_check`
    ;");
}

if (!$CI->db->table_exists(db_prefix() . 'expensive_history')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "expensive_history` (
      `id` int(200) UNSIGNED NOT NULL AUTO_INCREMENT,
      `expensive_id` int(11) NOT NULL,
      `bank` varchar(200) NOT NULL,
      `amount` varchar(200) NOT NULL,
      `date`  DATE NULL,
      `check_no` varchar(200) NOT NULL,
      `check_date`  DATE NULL,
      `check_payee` varchar(200) NOT NULL,
      `vendor_payee` varchar(200) NOT NULL,
      `note` varchar(200) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('check_note', db_prefix() . 'expensive_history')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "expensive_history`
    ADD COLUMN `check_note` varchar(200) NOT NULL AFTER `note`
  ;");
}

add_option('right_side_pdf_margin', '20', 0);
add_option('left_side_pdf_margin', '20', 0);

