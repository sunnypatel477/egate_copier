<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <?php $favicon = get_option('favicon'); ?>
    <link rel="icon" href="<?php echo base_url('uploads/company/'.$favicon); ?>" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="<?php echo base_url(WIKI_ASSETS_PATH.'/articles_show/styles/jquery.tocify.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/plugins/tinymce/plugins/codesample/css/prism.css'); ?>">
</head>

<style>

</style>
</head>

<body>

    <div id="mySidebar" class="sidebar">
        <div class="header">
           <div id="logo">
              <?php get_company_logo(get_admin_uri().'/') ?>
           </div>
           
        </div>
         <div class="button-header">
               <a href="<?php echo admin_url('wiki/articles'); ?>">
                        <?php echo _l('articles'); ?>
                <a href="<?php echo admin_url('wiki/books'); ?>">
                            <?php echo _l('books'); ?></a>
           </div>
        <div id="toc">
        </div>
    </div>

    <div id="main">
        <div id="header" style="">
            <div class="header-left">
              <a href="javascript:void(0)" id="closebtn" class="closebtn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                  </svg>
              </a>
              <span class="openbtn" id="openbtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
              </svg>
              </span>
              <span class=""> <?php echo $article['title']; ?></span>
            </div>
          
            
        </div>
        <div class="content-main">
            <?php 
                echo $article['content'];
            ?>
            <?php if (isset($attachments) && count($attachments) > 0) { ?>
                <div class="row task_attachments_wrapper">
                    <div class="col-md-12" id="attachments">
                        
                        <div class="row">
                            <?php
                            $i = 1;
                            // Store all url related data here
                            $comments_attachments            = [];
                            $attachments_data                = [];
                            foreach ($attachments as $attachment) { ?>
                                <?php ob_start(); ?>
                                <div style="width: unset;" data-num="<?php echo $i; ?>" class="task-attachment-col col-md-6">
                                    <ul class="list-unstyled task-attachment-wrapper" style="list-style-type: none;" data-placement="right" data-toggle="tooltip" data-title="<?php echo $attachment['file_name']; ?>">
                                        <li style="max-height: unset;min-height: unset;" class="mbot10 task-attachment<?php if (strtotime($attachment['dateadded']) >= strtotime('-16 hours')) {
                                                                                                                            echo ' highlight-bg';
                                                                                                                        } ?>">
                                            <div class="pull-right task-attachment-user">
                                                <?php
                                                $externalPreview = false;
                                                $is_image        = false;
                                                $path            = FCPATH . 'uploads/' . WIKI_MODULE_NAME_ARTICAL . '/' . $article['id'] . '/' . $attachment['file_name'];
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
                                                ?>
                                            </div>
                                            
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
                    
                </div>
            <?php } ?>
           
        </div>

    </div>

    <script>

    </script>

   
    <script src=" <?php echo base_url(WIKI_ASSETS_PATH.'/articles_show/javascripts/jquery/jquery-1.8.3.min.js'); ?>"></script>
    <script src=" <?php echo base_url(WIKI_ASSETS_PATH.'/articles_show/javascripts/jqueryui/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
    <script src=" <?php echo base_url(WIKI_ASSETS_PATH.'/articles_show/javascripts/jquery.tocify.min.js'); ?>"></script>
    <script src="<?php echo base_url(WIKI_ASSETS_PATH.'/js/article_show.js'); ?>"></script>

</body>

</html>