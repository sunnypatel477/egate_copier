<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(WIKI_ASSETS_PATH.'/css/wiki_styles.css'); ?>">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="d-flex">
                            <h4 class="no-margin"><?php echo $title; ?></h4>
                            
                        </div>

                        <hr class="hr-panel-heading" />
                        <?php echo form_open_multipart($this->uri->uri_string(), array('id'=>'form_main')); ?>
                        <?php if(isset($article)){ ?>
                            <input type="hidden" name="article_id" value="<?php echo $article->id; ?>">
                        <?php } ?>
                        <?php if(isset($clone_id)){ ?>
                            <input type="hidden" name="clone_id" value="<?php echo $clone_id; ?>">
                        <?php } ?>
                        <?php if(isset($back_url)){ ?>
                            <input type="hidden" name="back_url" value="<?php echo $back_url; ?>">
                        <?php } ?>
                        <?php
                            if (isset($article)) {
                                $selected_type = $article->type;
                            }else{
                                $selected_type = 'document';
                            }
                        ?>
                        <div class="roww">
                            <div class="col-md-4">
                                <div>
                                    <?php
                                        $selected = array();
                                        if (isset($article)) {
                                            $selected = $article->book_id;
                                        }
                                    ?>
                                    <?php echo render_select('book_id', $books, array('id', array('name')), 'wiki_book', $selected, []); ?>
                                </div>
                                <?php $attrs = (isset($article) ? array() : array('autofocus'=>true)); ?>
                                
                                <?php $value = (isset($article) ? $article->title : ''); ?>
                                <?php echo render_input('title', 'wiki_title', $value, 'text', $attrs); ?>
                            </div>
                           
                            <div class="col-md-4">
                                <?php $value = (isset($article) ? $article->description : ''); ?>
                                <?php echo render_textarea('description', 'wiki_description', $value,['rows' => 6]); ?>
                                <?php $value = (isset($article) ? $article->content : ''); ?>
                            </div>

                            <div class="col-md-4">
                                <?php if(isset($article)){ ?>
                                    <div class="checkbox checkbox-primary">
                                        <small><?php echo _l('get_link_help'); ?></small><br>
                                        <input type="checkbox" name="is_publish" id="is_publish" <?php if(isset($article)){if($article->is_publish == 1){echo 'checked';} } else {echo 'checked';} ?>>
                                        <label for="is_publish"><?php echo _l('get_link'); ?></label>
                                    </div>
                                    <div class="wiki-input-slug-wrap">
                                        <?php $tmp_publish_link = site_url('wiki/' . $article->slug); ?>
                                        <p>
                                            <a href="<?php echo $tmp_publish_link; ?>" target="_blank"><?php echo $tmp_publish_link; ?></a>
                                            <span class="wiki-btn-copy" data-copy="<?php echo $tmp_publish_link; ?>" data-lang="<?php echo _l('wiki_copy_success'); ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-copy"></i></button></span>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div>
                                            <?php echo render_select('type', [['id' => 'document', 'name' => _l('wiki_document'),],['id' => 'mindmap', 'name' => _l('wiki_mindmap'),],], array('id', array('name')), 'wiki_type', $selected_type, []); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 wiki-article-type-wrap <?php echo $selected_type == 'mindmap' ? '' : 'hide' ?>" data-type="mindmap">
                                        <div>
                                            <label for="" class="control-label"><?php echo _l('wiki_help_build'); ?></label>
                                        </div>
                                        <button type="submit" name="submit" value="SAVE_AND_BUILD" class="btn btn-success"><?php echo _l('wiki_build_map'); ?></button>
                                        <?php 
                                            $value = null;
                                            if(isset($article) && isset($article->mindmap_thumb) && $article->mindmap_thumb != ""){
                                                $value = $article->mindmap_thumb;
                                            }
                                        ?>
                                        <img class="wiki-mindmap-thumb" src="<?php echo isset($value) ? wiki_get_mindmap_thumb($value) : wiki_get_mindmap_thumb() ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="wiki-article-type-wrap <?php echo $selected_type == 'document' ? '' : 'hide' ?>" data-type="document">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><?php echo _l('wiki_content'); ?>: <span class="text-warning">(<?php echo _l('article_help_menu'); ?>)</span></p>
                                    <p><small><?php echo _l('tinymce-help-article'); ?></small></p>
                                    <?php $value = (isset($article) ? $article->content : ''); ?>
                                    <?php echo render_textarea('content','',$value,array(),array(),'','tinymce-content'); ?>
                                </div>
                            </div>
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
                                                                <a href="#" class="pull-right" onclick="remove_wiki_article_attachment(this,<?php echo $attachment['id']; ?>); return false;">
                                                                    <i class="fa fa fa-times"></i>
                                                                </a>
                                                            <?php }
                                                            $externalPreview = false;
                                                            $is_image        = false;
                                                            $path            = FCPATH . 'uploads/' . WIKI_MODULE_NAME_ARTICAL . '/' . $article->id . '/' . $attachment['file_name'];
                                                            $href_url        = site_url('download/file/'.WIKI_MODULE_NAME_ARTICAL.'/' . $attachment['attachment_key']);
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
                                    <a href="<?php echo admin_url('wiki/articles/download_files/' . $article->id); ?>" class="bold">
                                        <?php echo _l('download_all'); ?> (.zip)
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                            <a href="<?php  echo isset($back_url) ? $back_url : admin_url('wiki/articles'); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
                            <?php if(isset($article)){ ?> 
                            <a href="<?php echo admin_url('wiki/articles/show/' . $article->id) ?>" class="btn btn-success"><?php echo _l('view'); ?></a>
                            <?php } ?>
                            <?php if(isset($article) && has_permission('wiki_articles','','delete')){ ?>
                                <a href="<?php echo admin_url('wiki/articles/delete/' . $article->id); ?>" class="btn btn-danger btn-remove" data-lang="<?php echo _l('wiki_confirm_delete'); ?>"><?php echo _l('delete'); ?></a>
                            <?php } ?>
                            <button type="submit" name="submit" value="ONLY_SAVE" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                         </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(WIKI_ASSETS_PATH.'/js/article.js'); ?>"></script>
</body>
</html>
