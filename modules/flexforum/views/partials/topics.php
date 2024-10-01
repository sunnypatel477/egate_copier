<div class="row <?php echo is_client_logged_in() ? 'tw-mt-10' : '' ?>">
    <div class="col-md-12">
        <div class="panel_s flexforum-categories">
            <div class="panel-heading" style="display: flex; flex-direction: row; justify-content: space-between;">
                <span class="tw-font-semibold tw-text-lg">
                    <?php echo flexforum_lang('topics') ?>
                </span>

                <?php if (is_client_logged_in() || is_staff_logged_in()) { ?>
                    <div>
                        <button onclick="new_flexforum_topic(); return false;" class="btn btn-primary">
                            <i class="fa-solid fa-plus tw-mr-1"></i>
                            <?php echo flexforum_lang('new_topic'); ?>
                        </button>
                    </div>
                <?php } ?>
            </div><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="category" id="category" class="form-control selectpicker">
                                <option value="">Select Category</option>
                                <?php foreach (flexforum_get_parent_categories() as $category) { ?>
                                    <option data-parent-id="<?php echo $category['name'] ?>" value="<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                        (<span><?php echo flexforum_count_topic_for_category($category['id']); ?></span>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="childcategory" id="childcategory" class="form-control selectpicker">
                                <option value="">Select Parent Category</option>
                                <!-- <?php //foreach (flexforum_get_categories() as $category) { 
                                        ?>
                                    <option value="<?php //echo $category['id']; 
                                                    ?>">
                                        <?php //echo $category['name']; 
                                        ?>
                                        (<span><?php //echo flexforum_count_topic_for_child_category_data($category['id']); 
                                                ?></span>)
                                    </option>
                                <?php //} 
                                ?> -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="panel-body">
                <div class="col-md-12">
                    <div class="panel-table-full">
                        <table class="table dt-table" id="topic-table">
                            <thead>
                                <th>
                                    <?php echo flexforum_lang('topic'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('parent_categories'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('child_categories'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('model_name'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('brand'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('code'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('error_code'); ?>
                                </th>

                                <th>
                                    <?php echo flexforum_lang('description'); ?>
                                </th>
                                <!-- <th>
                                    <?php //echo flexforum_lang('likes'); 
                                    ?>
                                </th> -->
                                <!-- <th>
                                    <?php //echo flexforum_lang('followers'); 
                                    ?>
                                </th> -->
                                <!-- <th>
                                    <?php //echo flexforum_lang('replies'); 
                                    ?>
                                </th> -->
                                <th>
                                    <?php echo flexforum_lang('l_f_r'); ?>
                                </th>

                                <?php if (is_client_logged_in() || is_staff_logged_in()) { ?>
                                    <th>
                                        <?php echo flexforum_lang('options'); ?>
                                    </th>
                                <?php } ?>
                            </thead>
                            <tbody>
                                <?php foreach ($topics as $topic) { ?>
                                    <tr class="priority">
                                        <td>
                                            <span>
                                                <a href="<?php echo flexforum_get_topic_url($topic['slug']) ?>">
                                                    <?php echo $topic['title']; ?>
                                                </a>

                                            </span>
                                        </td>
                                        <td data-parent-id="<?php echo $topic['category']; ?>">
                                            <?php echo flexforum_get_category_name($topic['category']) ?>
                                        </td>
                                        <td>
                                            <?php echo flexforum_get_category_name($topic['childcategory']) ?>
                                        </td>
                                        <td>
                                            <?php echo $topic['model_name'] ?>
                                        </td>
                                        <td>
                                            <?php echo $topic['brand'] ?>
                                        </td>
                                        <td>
                                            <?php echo $topic['code'] ?>
                                        </td>
                                        <td>
                                            <?php echo $topic['error_code'] ?>
                                        </td>

                                        <td>
                                            <?php echo $topic['description'] ? $topic['description'] : 0 ?>
                                        </td>
                                        <!-- <td>
                                            <?php //echo $topic['likes'] ? number_format($topic['likes']) : 0 
                                            ?>
                                        </td>
                                        <td>
                                            <?php //echo $topic['followers'] ? number_format($topic['followers']) : 0 
                                            ?>
                                        </td>
                                        <td>
                                            <?php //echo $topic['replies'] ? number_format($topic['replies']) : 0 
                                            ?>
                                        </td> -->
                                        <td>
                                            <span style="cursor: pointer;" title="<?php echo flexforum_lang('likes') ?>"><?php echo $topic['likes'] ? number_format($topic['likes']) : 0 ?> , </span>
                                            <span style="cursor: pointer;" title="<?php echo flexforum_lang('followers'); ?>"><?php echo $topic['followers'] ? number_format($topic['followers']) : 0 ?> , </span>
                                            <span style="cursor: pointer;" title="<?php echo flexforum_lang('replies'); ?>"><?php echo $topic['replies'] ? number_format($topic['replies']) : 0 ?></span>
                                        </td>
                                        <?php if (is_client_logged_in() || is_staff_logged_in()) { ?>
                                            <td>
                                                <div class="tw-flex tw-items-center tw-space-x-3">
                                                    <?php if (flexforum_user_can_edit_topic($topic['id'])) { ?>
                                                        <button class='btn btn-default ' onclick="edit_flexforum_topic(<?php echo $topic['id'] ?>); return false;" data-id="<?php echo $topic['id']; ?>"
                                                            data-brand="<?php echo $topic['brand']; ?>" data-code="<?php echo $topic['code']; ?>" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                                        </button>
                                                        <a class='btn btn-default' href="<?php echo flexforum_get_url('delete_topic/' . $topic['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-2">
        <div class="row">
            <div class="col-md-12">
                <h5>
                    <?php //echo flexforum_lang('filter_by_category') 
                    ?>
                </h5>
            </div>
            <?php //if (count($categories) > 0) { 
            ?>
                <?php //foreach ($categories as $category) : 
                ?>
                    <div class="col-md-12">
                        <div>
                            <a class="btn btn-link bold">
                                <span id="parent_category_filter" data-parent-name="<?php //echo $category['name'] 
                                                                                    ?>"><?php //echo $category['name'] 
                                                                                        ?></span>
                                <span class="badge mleft5 bold">
                                    <?php //echo flexforum_count_topic_for_category($category['id']) 
                                    ?>
                                </span>
                            </a>
                        </div>

                        <?php
                        // $child_categories = flexforum_count_topic_for_parent_category($category['id']);
                        // if (!empty($child_categories)) {
                        //     foreach ($child_categories as $child) :
                        // 
                        ?>
                                <div class="mleft15 text-center">
                                    <a class="btn btn-link bold">
                                        <span id="child_category_filter" data-child-name="<?php //echo $child['name'] 
                                                                                            ?>"><?php //echo $child['name'] 
                                                                                                ?></span>
                                        <span class="badge mleft5 bold">
                                            <?php //echo flexforum_count_topic_for_child_category_data($child['id']) 
                                            ?>
                                        </span>
                                    </a>
                                </div>
                        <?php
                        //endforeach;
                        // } else {
                        // echo '<div class="mleft15 text-center">No child categories found.</div>';
                        // }
                        ?>
                    </div>
                <?php //endforeach; 
                ?>

            <?php //} else { 
            ?>
                <div class="flexforum-no-categories text-center">
                    <?php //echo flexforum_lang('no_categories_found') 
                    ?>
                </div>
            <?php //} 
            ?>
        </div>
    </div> -->
</div>

<script src="<?php echo base_url(FLEXFORUM_MODULE_NAME . '/assets/js/publish.js'); ?>"></script>

<?php $this->load->view('partials/topic-modal'); ?>
<script>
    window.addEventListener('load', function() {

        // On hidden modal reset the values
        $('#flexforum_topic_modal').on("hidden.bs.modal", function(event) {
            $('#additional').html('');
            $('#category').val('');
            $('#childcategory').val('');
            $('#flexforum_topic_modal input').not('[type="hidden"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });


        // On hidden modal re-render select input
        $('#flexforum_topic_modal').on("shown.bs.modal", function(event) {
            $('#category').selectpicker('render');
            $('#childcategory').selectpicker('render');
        });

        flexforum_topic_description_tinymce();
    });



    function flexforum_topic_description_tinymce() {
        alert('1')
        init_editor(".flexforum-topic-description-client", flexforum_editor_config());
        init_editor(".flexforum-topic-description-admin");
    }

    function new_flexforum_topic() {

        $('#flexforum_topic_modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function flexforum_settings() {
        $('#flexforum_settings_modal').modal('show');
    }

    function edit_flexforum_topic(id) {
        $('#additional').append(hidden_input('id', id));
        let url = getBaseURL() + 'topic/' + id

        $.getJSON(url, function(data, textStatus, jqXHR) {
            if (data.success) {
                $('#flexforum_topic_modal input[name="title"]').val(data.data.title);

                $('#flexforum_topic_modal select[name="category"]').val(data.data.category);
                $('#flexforum_topic_modal select[name="childcategory"]').val(data.data.childcategory);

                $('#flexforum_topic_modal select[name="model_id"]').val(data.data.model_id);
                $('#flexforum_topic_modal input[name="error_code"]').val(data.data.error_code);

                // Clear existing options for brand_name and code
                $("#brand_name").empty();
                $("#code").empty();

                // Append brand option
                var brandOptions = "<option value='" + data.data.brand + "'>" + data.data.brand + "</option>";
                $("#brand_name").append(brandOptions);

                // Append code option
                var codeOptions = "<option value='" + data.data.code + "'>" + data.data.code + "</option>";
                $("#code").append(codeOptions);

                $('#flexforum_topic_modal select[name="brand_name"]').val(data.data.brand);
                $('#flexforum_topic_modal select[name="code"]').val(data.data.code);

                $("#brand_name").prop("disabled", true);
                $("#code").prop("disabled", true);

                // Refresh Selectpickers
                $('#flexforum_topic_modal select[name="category"]').selectpicker('refresh');
                $('#flexforum_topic_modal select[name="childcategory"]').selectpicker('refresh');
                $('#flexforum_topic_modal select[name="model_id"]').selectpicker('refresh');
                $('#flexforum_topic_modal select[name="brand_name"]').selectpicker('refresh');
                $('#flexforum_topic_modal select[name="code"]').selectpicker('refresh');

                if (data.data.closed == 1) {
                    $('#flexforum_topic_modal input[name="closed"]').prop('checked', true);
                }

                $('#flexforum_topic_modal textarea[name="description"]').val(data.data.description);
                $('#flexforum_topic_modal').modal('show');

                var descriptionContent = data.data.description;

                if (descriptionContent != null) {
                    tinyMCE.activeEditor.setContent(descriptionContent);
                } else {
                    tinyMCE.activeEditor.setContent('');
                }

                $('.add-title').addClass('hide'); // Assuming this hides an element with class 'add-title'
            } else {
                alert_float('danger', data.message); // Show error message
            }
        });


    }

    function getBaseURL() {
        return "<?php echo flexforum_get_url() ?>";
    }
</script>