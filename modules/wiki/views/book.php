<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(WIKI_ASSETS_PATH.'/css/wiki_styles.css'); ?>">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open_multipart($this->uri->uri_string(), array('id'=>'form_main')); ?>
                        <?php if(isset($back_url)){ ?>
                            <input type="hidden" name="back_url" value="<?php echo $back_url; ?>">
                        <?php } ?>
                        <?php $attrs = (isset($book) ? array() : array('autofocus'=>true)); ?>
                        <?php $value = (isset($book) ? $book->name : ''); ?>
                        <?php echo render_input('name', 'book_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($book) ? $book->short_description : ''); ?>
                        <?php echo render_textarea('short_description', 'book_short_description', $value); ?>
                        <label for="specific_staff"><?php echo _l('permisson_for_views'); ?></label>
                        <div class="select-notification-settings">
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="assign_type" value="specific_staff" id="specific_staff" <?php if (isset($book) && $book->assign_type ==  'specific_staff' || !isset($book)) { echo 'checked'; } ?>>
                                <label for="specific_staff"><?php echo _l('specific_staff_members'); ?></label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="assign_type" id="roles" value="roles" <?php if (isset($book) && $book->assign_type == 'roles') {
                                echo 'checked';
                                } ?>>
                                <label for="roles"><?php echo _l('staff_with_roles'); ?></label>
                            </div>
                            <div class="clearfix mtop15"></div>
                            <div id="specific_staff_assign" class="types-assign <?php if (isset($book) && $book->assign_type != 'specific_staff') { echo 'hide'; } ?>">
                                <?php
                                    $selected = array();
                                    if (isset($book) && $book->assign_type == 'specific_staff') {
                                        $selected = wiki_unserialize($book->assign_ids, 'staff_');
                                    }
                                ?>
                                <?php echo render_select('assign_ids_staff[]', $members, array('staffid', array('firstname', 'lastname')), 'book_assign_specific_staff', $selected, array('multiple'=>true)); ?>
                            </div>
                            <div id="roles_assign" class="types-assign <?php if (isset($book) && $book->assign_type != 'roles' || !isset($book)) {
                                echo 'hide';} ?>">
                                <?php
                                    $selected = array();
                                    if (isset($book) && $book->assign_type == 'roles') {
                                        $selected = wiki_unserialize($book->assign_ids, 'role_');
                                    }
                                ?>
                                <?php echo render_select('assign_ids_roles[]', $roles, array('roleid', array('name')), 'book_assign_roles', $selected, array('multiple'=>true)); ?>
                            </div>
                            <div id="new-task-attachments">
                            <hr class="-tw-mx-3.5" />
                            <div class="row attachments">
                                <div class="attachment">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment" class="control-label"><?php echo _l('add_knowledge_base_attachments'); ?></label>
                                            <div class="input-group">
                                                <input type="file" extension="<?php echo str_replace('.', '', get_option('allowed_files')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default add_more_attachments" type="button"><i class="fa fa-plus"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($attachments) && count($attachments) > 0) { ?>
                            <div class="row task_attachments_wrapper">
                                <div class="col-md-12" id="attachments">
                                    <hr />
                                    <h4 class="th tw-font-semibold tw-text-lg mbot15"><?php echo _l('add_knowledge_base_attachments'); ?></h4>
                                    <div class="row">
                                        <?php
                                        $i = 1;
                                        // Store all url related data here
                                        $comments_attachments            = [];
                                        $attachments_data                = [];
                                        foreach ($attachments as $attachment) { ?>
                                            <?php ob_start(); ?>
                                            <div style="width: unset;" data-num="<?php echo $i; ?>" class="task-attachment-col col-md-6">
                                                <ul class="list-unstyled task-attachment-wrapper" data-placement="right" data-toggle="tooltip" data-title="<?php echo $attachment['file_name']; ?>">
                                                    <li style="max-height: unset;min-height: unset;" class="mbot10 task-attachment<?php if (strtotime($attachment['dateadded']) >= strtotime('-16 hours')) {
                                                                                            echo ' highlight-bg';
                                                                                        } ?>">
                                                        <div class="mbot10 pull-right task-attachment-user">
                                                            <?php if ($attachment['staffid'] == get_staff_user_id() || is_admin()) { ?>
                                                                <a href="#" class="pull-right" onclick="remove_wiki_book_attachment(this,<?php echo $attachment['id']; ?>); return false;">
                                                                    <i class="fa fa fa-times"></i>
                                                                </a>
                                                            <?php }
                                                            $externalPreview = false;
                                                            $is_image        = false;
                                                            $path            = FCPATH . 'uploads/' . WIKI_MODULE_NAME . '/' . $book->id . '/' . $attachment['file_name'];
                                                            $href_url        = site_url('download/file/wiki/' . $attachment['attachment_key']);
                                                            $isHtml5Video    = is_html5_video($path);
                                                            if (empty($attachment['external'])) {
                                                                $is_image = is_image($path);
                                                                $img_url  = site_url('download/preview_image?path=' . protected_file_url_by_path($path, true) . '&type=' . $attachment['filetype']);
                                                            } elseif ((!empty($attachment['thumbnail_link']) || !empty($attachment['external']))
                                                                && !empty($attachment['thumbnail_link'])
                                                            ) {
                                                                $is_image        = true;
                                                                $img_url         = optimize_dropbox_thumbnail($attachment['thumbnail_link']);
                                                                $externalPreview = $img_url;
                                                                $href_url        = $attachment['external_link'];
                                                            } elseif (!empty($attachment['external']) && empty($attachment['thumbnail_link'])) {
                                                                $href_url = $attachment['external_link'];
                                                            }
                                                            if (!empty($attachment['external']) && $attachment['external'] == 'dropbox' && $is_image) { ?>
                                                                <a href="<?php echo $href_url; ?>" target="_blank" class="" data-toggle="tooltip" data-title="<?php echo _l('open_in_dropbox'); ?>"><i class="fa fa-dropbox" aria-hidden="true"></i></a>
                                                            <?php } elseif (!empty($attachment['external']) && $attachment['external'] == 'gdrive') { ?>
                                                                <a href="<?php echo $href_url; ?>" target="_blank" class="" data-toggle="tooltip" data-title="<?php echo _l('open_in_google'); ?>"><i class="fa-brands fa-google" aria-hidden="true"></i></a>
                                                            <?php }
                                                            if ($attachment['staffid'] != 0) {
                                                                echo '<a href="' . admin_url('profile/' . $attachment['staffid']) . '" target="_blank">' . get_staff_full_name($attachment['staffid']) . '</a> - ';
                                                            } elseif ($attachment['contact_id'] != 0) {
                                                                echo '<a href="' . admin_url('clients/client/' . get_user_id_by_contact_id($attachment['contact_id']) . '?contactid=' . $attachment['contact_id']) . '" target="_blank">' . get_contact_full_name($attachment['contact_id']) . '</a> - ';
                                                            }
                                                            echo '<span class="text-has-action tw-text-sm" data-toggle="tooltip" data-title="' . _dt($attachment['dateadded']) . '">' . time_ago($attachment['dateadded']) . '</span>';
                                                            ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="<?php if ($is_image) {
                                                                        echo 'preview-image';
                                                                    } elseif (!$isHtml5Video) {
                                                                        echo 'task-attachment-no-preview';
                                                                    } ?>">
                                                            <?php
                                                            // Not link on video previews because on click on the video is opening new tab
                                                            if (!$isHtml5Video) { ?>
                                                                <a href="<?php echo (!$externalPreview ? $href_url : $externalPreview); ?>" target="_blank" <?php if ($is_image) { ?> data-lightbox="task-attachment" <?php } ?> class="<?php if ($isHtml5Video) {
                                                                                                                                                                                                                                            echo 'video-preview';
                                                                                                                                                                                                                                        } ?>">
                                                                <?php } ?>
                                                                <?php if ($is_image) { ?>
                                                                    <img src="<?php echo $img_url; ?>" class="img img-responsive">
                                                                <?php } elseif ($isHtml5Video) { ?>
                                                                    <video width="100%" height="100%" src="<?php echo site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>" controls>
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                <?php } else { ?>
                                                                    <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                                                    <?php echo $attachment['file_name']; ?>
                                                                <?php } ?>
                                                                <?php if (!$isHtml5Video) { ?>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php
                                            $attachments_data[$attachment['id']] = ob_get_contents();
                                            if ($attachment['task_comment_id'] != 0) {
                                                $comments_attachments[$attachment['task_comment_id']][$attachment['id']] = $attachments_data[$attachment['id']];
                                            }
                                            ob_end_clean();
                                            echo $attachments_data[$attachment['id']];
                                            ?>
                                        <?php
                                            $i++;
                                        } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12 text-center">
                                    <hr />
                                    <a href="<?php echo admin_url('wiki/books/download_files/' . $book->id); ?>" class="bold">
                                        <?php echo _l('download_all'); ?> (.zip)
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                                        <hr />
                        <div class="wiki-form-buttons">
                            <a href="<?php  echo isset($back_url) ? $back_url : admin_url('wiki/books'); ?>" class="btn btn-primary"><?php echo _l('back'); ?></a>
                            <?php if(isset($book) && has_permission('wiki_books','','delete')){ ?>
                                <a href="<?php echo admin_url('wiki/books/delete/' . $book->id); ?>" class="btn btn-danger btn-remove" data-lang="<?php echo _l('wiki_confirm_delete'); ?>"><?php echo _l('delete'); ?></a>
                            <?php } ?>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(WIKI_ASSETS_PATH.'/js/book.js'); ?>"></script>
</body>
</html>
