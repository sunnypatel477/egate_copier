<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="tw-divide-y tw-divide-neutral-200 tw-divide-solid">
        <?php foreach ($articles as $category) { ?>
            <div class="tw-pt-5 first:tw-pt-0 tw-block">
                <ul class="list-unstyled articles_list tw-space-y-5">
                    <?php foreach ($category['articles'] as $article) { ?>
                        <li>
                            <div class="sm:tw-flex sm:tw-justify-between">
                                <h4 class="tw-text-lg tw-font-medium tw-my-0">
                                    <a href="<?php echo site_url('knowledge-base/article/' . $article['slug']); ?>" class="tw-text-neutral-600 hover:tw-text-neutral-800 active:tw-text-neutral-800">
                                        <?php echo $article['subject']; ?>
                                    </a>
                                </h4>
                                <span class="tw-text-neutral-500 tw-text-xs tw-self-start">
                                    <?php echo _dt($article['datecreated']); ?>
                                </span>
                            </div>
                            <div class="tw-text-neutral-500 tw-mt-4 sm:tw-mt-0">
                                <?php echo strip_tags(mb_substr($article['description'], 0, 250)); ?>...
                            </div>
                            <?php 
                            $attachments = get_knowledge_base_attachments_by_id($article['articleid']);

                            if (isset($attachments) && count($attachments) > 0) { ?>
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
                                                            <div class="pull-right task-attachment-user">
                                                                <?php
                                                                $externalPreview = false;
                                                                $is_image        = false;
                                                                $path            = FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $article['articleid'] . '/' . $attachment['file_name'];
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
                                        <a href="<?php echo admin_url('knowledge_base/download_files/' . $article['articleid']); ?>" class="bold">
                                            <?php echo _l('download_all'); ?> (.zip)
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>