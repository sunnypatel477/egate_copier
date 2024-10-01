<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$unlink_files = array(
        APPPATH . 'views/admin/expenses/my_expense.php',
);

foreach ($unlink_files as $file) {
    if (file_exists($file)) {
        unlink($file);
    }
}