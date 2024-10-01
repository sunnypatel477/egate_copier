<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$canViewPurchaseOrders = (staff_can('view', 'purchase_orders') || staff_can('view_own', 'purchase_orders') || (get_option('allow_staff_view_purchase_orders_assigned') == 1 && staff_has_assigned_purchase_orders()));
if (!$canViewPurchaseOrders || (int)get_option('show_purchase_order_status_widget_on_dashboard') !== 1) return;
?>
<template id="purhcase_orders_widget_wrapper">
    <div class="col-md-6 col-sm-6" id="purhcase_orders_widget">
        <div class="row">
            <div class="col-md-12 text-stats-wrapper">
                <p
                    class="text-neutral-700 tw-mb-8 tw-inline-flex tw-items-center tw-space-x-1.5 rtl:tw-space-x-reverse -tw-mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="tw-6 tw-h-6 tw-text-neutral-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span class="tw-font-medium">
                        <?php echo _l('home_purchase_order_overview'); ?>
                    </span>
                </p>

            </div>
            <?php foreach ($this->purchase_orders_model->get_statuses() as $status) {
                $url          = admin_url('purchase_orders/list_purchase_orders?status=' . $status);
                $percent_data = get_purchase_orders_percent_by_status($status); ?>
            <div class="col-md-12 text-stats-wrapper">
                <a href="<?php echo e($url); ?>"
                    class="text-<?php echo purchase_order_status_color_class($status, true); ?> mbot15 inline-block">
                    <span class="_total bold"><?php echo e($percent_data['total_by_status']); ?></span>
                    <?php echo format_purchase_order_status($status, '', false); ?>
                </a>
            </div>
            <div class="col-md-12 text-right progress-finance-status tw-text-neutral-400">
                <?php echo e($percent_data['percent']); ?>%
                <div class="progress no-margin progress-bar-mini">
                    <div class="progress-bar progress-bar-<?php echo purchase_order_status_color_class($status); ?> no-percent-text not-dynamic"
                        role="progressbar" aria-valuenow="<?php echo e($percent_data['percent']); ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width: 0%" data-percent="<?php echo e($percent_data['percent']); ?>">
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</template>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let financeWidgetsSelector = $("#widget-finance_overview .home-summary");
    if (financeWidgetsSelector.length) {
        financeWidgetsSelector.append($("#purhcase_orders_widget_wrapper").html());
        let financeWidgetsSelectorChildren = financeWidgetsSelector.children();
        financeWidgetsSelectorChildren.removeClass().addClass(
            `col-sm-6 col-md-4 col-lg-${Math.ceil(12/financeWidgetsSelectorChildren.length)} tw-mb-8`
        );
        // Convert to flex
        financeWidgetsSelector.addClass('tw-flex tw-flex-wrap');
    }
})
</script>