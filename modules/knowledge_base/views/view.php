<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-7">
                <div class="panel_s">
                    <div class="panel-body tc-content">
                       <h4 class="bold no-margin"><?php echo $article->subject; ?></h4>
                       <hr class="hr-panel-heading" />
                       <div class="clearfix"></div>
                       <div class="kb-article">
                         <?php echo $article->description; ?>
                       </div>
                       <!-- <hr /> -->
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
                                                            <div class="pull-right task-attachment-user">
                                                                <?php
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
                            <hr />
                       <h4 class="mtop20"><?php echo _l('clients_knowledge_base_find_useful'); ?></h4>
                       
                       <div class="answer_response"></div>
                       <div class="btn-group mtop15 article_useful_buttons" role="group">
                        <input type="hidden" name="articleid" value="<?php echo $article->articleid; ?>">
                        <button type="button" data-answer="1" class="btn btn-success"><?php echo _l('clients_knowledge_base_find_useful_yes'); ?></button>
                        <button type="button" data-answer="0" class="btn btn-danger"><?php echo _l('clients_knowledge_base_find_useful_no'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php if(count($related_articles) > 0){ ?>
        <div class="col-md-5">
          <div class="panel_s">
              <div class="panel-body">
                <h4 class="bold no-margin"><?php echo _l('related_knowledgebase_articles'); ?></h4>
                 <hr class="hr-panel-heading" />
                <ul class="mtop10 articles_list">
                <?php foreach($related_articles as $rel_article_article) { ?>
                    <li>
                        <i class="fa fa-file-text-o"></i>
                        <a href="<?php echo admin_url('knowledge_base/view/'.$rel_article_article['slug']); ?>" class="article-heading"><?php echo $rel_article_article['subject']; ?></a>
                        <div class="text-muted mtop10"><?php echo strip_tags(mb_substr($rel_article_article['description'],0,100)); ?>...</div>
                    </li>
                    <hr />
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){

      // Lightbox for knowledge base images
      $.each($('.kb-article').find('img'), function () {
        if(!$(this).parent().is('a')){
          $(this).wrap('<a href="'+$(this).attr('src')+'" data-lightbox="kb-attachment"></a>');
        }
      });

      $('.article_useful_buttons button').on('click', function(e) {
           e.preventDefault();
           var data = {};
           data.answer = $(this).data('answer');
           data.articleid = '<?php echo $article->articleid; ?>';
           $.post(admin_url+'knowledge_base/add_kb_answer', data).done(function(response) {
               response = JSON.parse(response);
               if (response.success == true) {
                   $(this).focusout();
               }
               $('.answer_response').html(response.message);
           });
       });
   });
</script>
</body>
</html>
