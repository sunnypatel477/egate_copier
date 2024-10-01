<div class="modal fade" id="flexforum_topic_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open_multipart(is_client_logged_in() ? flexforum_client_url('topics') : flexforum_admin_url('topics'), ['id' => 'flexforum_topic_form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">
                        <?php echo flexforum_lang('edit_topic'); ?>
                    </span>
                    <span class="add-title">
                        <?php echo flexforum_lang('new_topic'); ?>
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('title', flexforum_lang('title', '', false), '', 'text', ['placeholder' => flexforum_lang('title_placeholder')]); ?>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="category">
                                <?php echo flexforum_lang('parent_categories'); ?>
                            </label>
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="category" id="category" class="form-control selectpicker">
                                <option value=""></option>
                                <?php foreach (flexforum_get_parent_categories() as $category) { ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="childcategory">
                                <?php echo flexforum_lang('child_categories'); ?>
                            </label>
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="childcategory" id="childcategory" class="form-control selectpicker">
                                <option value=""></option> <!-- Placeholder option -->
                                <?php foreach (flexforum_get_categories() as $category) { ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="model_id">
                                <?php echo flexforum_lang('model_list'); ?>
                            </label>
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="model_id" id="model_id" class="form-control selectpicker">
                                <option value=""></option>
                                <?php foreach (flexforum_get_model_list() as $model_list) { ?>
                                    <option value="<?php echo $model_list['id']; ?>">
                                        <?php echo $model_list['model_name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="brand_name">
                                <?php echo flexforum_lang('brand_name'); ?>
                            </label>
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="brand_name" id="brand_name" class="form-control selectpicker" readonly>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="code">
                                <?php echo flexforum_lang('code'); ?>
                            </label>
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="code" id="code" class="form-control selectpicker" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <?php echo render_input('topic_file', flexforum_lang('topic_file', '', false), '', 'file'); ?>
                    </div>

                    <div class="col-md-12">
                        <?php echo render_input('error_code', flexforum_lang('error_code', '', false), '', 'text', ['placeholder' => flexforum_lang('error_code')]); ?>
                    </div>

                    <div class="col-md-12">
                        <?php echo render_textarea('description', flexforum_lang('description', '', false), '', ['placeholder' => flexforum_lang('description_placeholder')], [], '', is_admin() || is_staff_logged_in() ? 'flexforum-topic-description-admin' : 'flexforum-topic-description-client'); ?>
                    </div>
                    <div class="col-md-12">
                        <div class='checkbox checkbox-primary'>
                            <input type='checkbox' name='closed' id='closed'>
                            <label for='closed'>
                                <?php echo flexforum_lang('close_topic'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo flexforum_lang('close'); ?>
                </button>
                <button type="submit" class="btn btn-primary">
                    <?php echo flexforum_lang('submit'); ?>
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>