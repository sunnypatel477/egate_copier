<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">

            <?php

            if (isset($element_data)) {
                $requestUrl = 'emailcanvas/create/'.$element_data->id;
            } else {
                $requestUrl = 'emailcanvas/create';
            }

            echo form_open(admin_url($requestUrl));
            ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo $title; ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="col-md-6">
                            <?php echo render_input('template_name', 'emailcanvas_template_name', $element_data->template_name ?? ''); ?>
                        </div>

                        <div class="col-md-6">
                            <?php echo render_input('template_description', 'emailcanvas_template_description', $element_data->template_description ?? ''); ?>
                        </div>

                        <div class="col-md-12">
                            <?php echo render_select('template_for', $email_templates_list, ['slug', ['name']], 'emailcanvas_template_for', $element_data->template_for ?? '', ['data-none-selected-text' => _l('emailcanvas_template_for_first_select')]); ?>
                        </div>


                        <div class="btn-bottom-toolbar text-right">
                            <?php
                            if (isset($element_data)) {
                            ?>
                                <a href="<?php echo admin_url('emailcanvas/email_builder/' . $element_data->id); ?>" type="submit" class="btn btn-success"><i class="fas fa-cogs fa-lg"></i> <?php echo _l('emailcanvas_open_template_builder'); ?></a>
                            <?php
                            }
                            ?>
                            <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($element_data) && !empty($element_data->template_html_css)) {
                ?>
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo _l('emailcanvas_template_preview'); ?>
                    </h4>
                    <div class="panel_s">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <?php echo json_decode($element_data->template_html_css); ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>

