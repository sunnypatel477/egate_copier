<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php if (isset($article)) { ?>
                            <br />
                            <small>
                                <?php if ($article->staff_article == 1) { ?>
                                    <a href="<?php echo admin_url('knowledge_base/view/' . $article->slug); ?>" target="_blank"><?php echo admin_url('knowledge_base/view/' . $article->slug); ?></a>
                                <?php } else { ?>
                                    <a href="<?php echo site_url('knowledge-base/article/' . $article->slug); ?>" target="_blank"><?php echo site_url('knowledge-base/article/' . $article->slug); ?></a>
                                <?php } ?>
                            </small>
                            <br />
                            <small>
                                <?php echo _l('article_total_views'); ?>:
                                <?php echo total_rows(db_prefix() . 'views_tracking', ['rel_type' => 'kb_article', 'rel_id' => $article->articleid]);
                                ?>
                            </small>
                        <?php } ?>
                    </h4>
                    <div>
                        <?php if (isset($article)) { ?>
                            <p>

                                <?php if (has_permission('knowledge_base', '', 'create')) { ?>
                                    <a href="<?php echo admin_url('knowledge_base/article'); ?>" class="btn btn-success pull-right"><?php echo _l('kb_article_new_article'); ?></a>
                                <?php } ?>
                                <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                                    <a href="<?php echo admin_url('knowledge_base/delete_article/' . $article->articleid); ?>" class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
                                <?php } ?>
                            <div class="clearfix"></div>
                            </p>
                        <?php } ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php $value = (isset($article) ? $article->subject : ''); ?>
                        <?php $attrs = (isset($article) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('subject', 'kb_article_add_edit_subject', $value, 'text', $attrs); ?>
                        <?php if (isset($article)) {
                            echo render_input('slug', 'kb_article_slug', $article->slug, 'text');
                        } ?>
                        <?php $value = (isset($article) ? $article->articlegroup : ''); ?>
                        <?php if (has_permission('knowledge_base', '', 'create')) {
                            echo render_select_with_input_group('articlegroup', get_kb_groups(), ['groupid', 'name'], 'kb_article_add_edit_group', $value, '<div class="input-group-btn"><a href="#" class="btn btn-default" onclick="new_kb_group();return false;"><i class="fa fa-plus"></i></a></div>');
                        } else {
                            echo render_select('articlegroup', get_kb_groups(), ['groupid', 'name'], 'kb_article_add_edit_group', $value);
                        }
                        ?>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="staff_article" name="staff_article" <?php if (isset($article) && $article->staff_article == 1) {
                                                                                                echo 'checked';
                                                                                            } ?>>
                            <label for="staff_article"><?php echo _l('internal_article'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="disabled" name="disabled" <?php if (isset($article) && $article->active_article == 0) {
                                                                                        echo 'checked';
                                                                                    } ?>>
                            <label for="disabled"><?php echo _l('kb_article_disabled'); ?></label>
                        </div>
                        <p class="bold"><?php echo _l('kb_article_description'); ?></p>
                        <?php $contents = '';
                        if (isset($article)) {
                            $contents      = $article->description;
                        } ?>
                        <?php echo render_textarea('description', '', $contents, [], [], '', 'tinymce tinymce-manual'); ?>
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
                                                                <a href="#" class="pull-right" onclick="remove_knowledge_base_attachment(this,<?php echo $attachment['id']; ?>); return false;">
                                                                    <i class="fa fa fa-times"></i>
                                                                </a>
                                                            <?php }
                                                            $externalPreview = false;
                                                            $is_image        = false;
                                                            $path            = FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $article->articleid . '/' . $attachment['file_name'];
                                                            $href_url        = site_url('download/file/knowledgebaseattachment/' . $attachment['attachment_key']);
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
                                    <a href="<?php echo admin_url('knowledge_base/download_files/' . $article->articleid); ?>" class="bold">
                                        <?php echo _l('download_all'); ?> (.zip)
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ((has_permission('knowledge_base', '', 'create') && !isset($article)) || has_permission('knowledge_base', '', 'edit') && isset($article)) { ?>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary">
                                <?php echo _l('submit'); ?>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
<script>
    $(function() {
        init_editor('#description', {
            append_plugins: 'stickytoolbar'
        });
        appValidateForm($('#article-form'), {
            subject: 'required',
            articlegroup: 'required'
        });
    });
</script>
</body>

</html>