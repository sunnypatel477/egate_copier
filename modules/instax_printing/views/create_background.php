<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'background-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>

                    </h4>

                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($background) ? $background->image_name : ''); ?>
                        <?php $attrs = (isset($background) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('image_name', 'image_name', $value, 'text', $attrs); ?>
                        <input type="hidden" name="id" value="<?php echo (isset($background) ? $background->id : ''); ?>">

                        <div class="form-group">
                            <label for="type" class="control-label" >Apply To : </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" id="type1" value="whole" <?php echo isset($background) && $background->type == 'whole' ? 'checked' : ( !isset($background) ? 'checked' : '')  ?> >Photo Book Style
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" id="type2" value="individual" <?php echo isset($background) && $background->type == 'individual' ? 'checked' : ''  ?>>Individual
                            </label>
                        </div>
                        <div class="form-group">
                            <select id="category" name="category" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_text'); ?>" data-live-search="true" data-style="btn-default">
                                <?php foreach ($category as $key => $category) { ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <select id="event_category" name="event_category" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_text'); ?>" data-live-search="true" data-style="btn-default">
                                <?php foreach ($event_category as $key => $event_categor) { ?>
                                    <option value="<?php echo $event_categor['id']; ?>" ><?php echo $event_categor['name']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <?php //if(!isset($id)){  ?>
                        <div id="new-task-attachments">
                            <hr class="-tw-mx-3.5" />
                            <div class="row attachments">
                                <div class="attachment">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment" class="control-label"><?php echo _l('upload_image'); ?></label>
                                            <div class="input-group">
                                                <input type="file" extension="<?php echo str_replace('.', '', get_option('allowed_files')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="image">
                                                <?php 
                                                    if(isset($background) && $background->background_url != ''){ ?>
                                                        <a href="<?php echo $background->background_url; ?>" target="_blank" class="input-group-addon"><i class="fa fa-download"></i></a>

                                                 <?php   }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="-tw-mx-3.5" />
                            <div class="row attachments">
                                <div class="attachment">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment" class="control-label">RAW File</label>
                                            <div class="input-group">
                                                <input type="file" extension="<?php echo str_replace('.', '', get_option('allowed_files')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="raw_file">
                                                <?php 
                                                    if(isset($background) && $background->raw_url != ''){ ?>
                                                        <a href="<?php echo $background->raw_url; ?>" target="_blank" class="input-group-addon"><i class="fa fa-download"></i></a>

                                                 <?php   }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <?php // } ?>
                    </div>

                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>

                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {

        appValidateForm($('#background-form'), {
            image_name: 'required',

        });
        <?php 
        if(isset($background)){ ?>
    $('#category').val('<?php echo $background->category; ?>').selectpicker('refresh');
    $('#event_category').val('<?php echo $background->event_category; ?>').selectpicker('refresh');
      <?php  }
        ?>
    });
</script>
</body>

</html>