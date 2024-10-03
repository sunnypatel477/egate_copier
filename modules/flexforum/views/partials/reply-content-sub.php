<?php

?>

<div class="row <?php echo "replies-$reply[type]-$reply[type_id]" ?>">
    <div class="col-xs-12">
        <hr>
    </div>
    <div class="<?php echo isset($is_secondary_reply) && $is_secondary_reply ? 'col-xs-11 col-xs-offset-1' : 'col-xs-11 tw-ml-3' ?>">
        <div class="row">
            <div class="col-md-12">
                <div style="display: flex; flex-direction: row; justify-content: space-between;">
                    <span>
                        <img src="<?php echo $reply['poster_image'] ?>" class="staff-profile-image-small">
                        <span class="tw-font-semibold">
                            <?php echo $reply['poster_name'] ?>
                        </span>
                    </span>
                    <span>
                        <?php echo $reply['last_modified'] ?>
                    </span>
                </div>
            </div>
            <div class="col-md-12 tw-mt-5">
                <p>
                    <?php echo htmlspecialchars_decode($reply['reply']) ?>
                </p>
            </div>
            <div class="col-md-12 tw-mt-5">
                <?php
                $replayfileDir = module_dir_url('flexforum/uploads/replay_file/');
                $replay_filename =  $replayfileDir . $reply['replay_file'];
                ?>
                <?php if (!empty($reply['replay_file'])): ?>
                    <a target="_blank" href="<?php echo $replay_filename; ?>"><?php echo $reply['replay_file']; ?></a>
                    <button class="btn btn-danger btn-sm delete-replay-file" data-id="<?php echo $reply['id']; ?>">X</button>
                <?php endif; ?>
            </div>
            <div class="col-md-12 tw-mt-5" style="display: flex; flex-direction: row; justify-content: space-between;">
                <span>
                    <?php $hide = isset($closed) && $closed; ?>
                    <?php if (!$hide) { ?>
                        <button id="<?php echo $reply['id'] ?>" data-type-id="<?php echo $reply['id'] ?>" data-type="<?php echo $like_type; ?>" class="btn btn-default tw-my-2 reply-like-btn" title="<?php echo flexforum_lang('like') ?>">
                            <i id="reply-like-btn-icon<?php echo $reply['id'] ?>" class="<?php echo $reply['reply_liked'] ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger"></i>
                        </button>

                        <?php if ($reply['type'] == FLEXFORUM_TOPIC_REPLY_TYPE) { ?>

                            <button id="<?php echo $reply['id'] ?>" class="btn btn-default tw-my-2 reply-reply-btn" title="<?php echo flexforum_lang('reply') ?>" data-id="<?php echo $reply['id'] ?>">
                                <i class="fa-solid fa-reply"></i>
                            </button>
                        <?php } ?>

                        <?php if (is_admin() || $reply['is_reply_owner']) { ?>
                            <a class="btn btn-primary tw-my-2 edit_replay" title="<?php echo flexforum_lang('edit_replay') ?>" data-id="<?php echo $reply['id'] ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="<?php echo flexforum_get_url("delete_reply/" . $reply['id']) ?>" id="<?php echo $reply['id'] ?>" class="btn btn-danger tw-my-2 reply-delete-btn _delete" title="<?php echo flexforum_lang('delete_reply') ?>" data-id="<?php echo $reply['id'] ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </span>
                <span>
                    <span class='tw-px-1 tw-my-3 tw-inline-block tw-font-semibold tw-text-neutral-600'>
                        <span id="reply-likes<?php echo $reply['id'] ?>">
                            <?php echo $reply['likes'] ? $reply['likes'] : 0 ?>
                        </span>
                        <?php echo flexforum_lang('likes') ?>
                    </span>

                    <?php if ($reply['type'] == FLEXFORUM_TOPIC_REPLY_TYPE) { ?>
                        <span class='tw-px-1 tw-my-3 tw-inline-block tw-font-semibold tw-text-neutral-600'>
                            <span id="<?php echo isset($is_secondary_reply) && $is_secondary_reply ? "reply-replies-$reply[id]" : "topic-replies-$reply[id]" ?>">
                                <?php echo $reply['replies'] ? $reply['replies'] : 0 ?>
                            </span>
                            <?php echo flexforum_lang('replies') ?>
                        </span>

                        <?php if ($reply['replies'] > 0) { ?>
                            <button class="btn btn-link tw-my-2 show-secondary-replies-btn" title="<?php echo flexforum_lang('show_replies') ?>" data-id="<?php echo $reply['id'] ?>" data-closed="<?php echo $hide ? '1' : '0' ?>">
                                <i class="fa-solid fa-angle-down"></i>
                            </button>
                        <?php } ?>
                    <?php } ?>
                </span>
            </div>
            <?php if (isset($has_reply_form) && $has_reply_form) { ?>
                <div class="row">
                    <div class="col-xs-11 col-xs-offset-1">
                        <?php $this->load->view('partials/reply-form', ['form_id' => "flexforum_secondary_reply_form-$reply[id]", 'reply_type' => FLEXFORUM_REPLY_REPLY_TYPE, 'type_id' => $reply['id']]); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-xs-11 col-xs-offset-1">
                    <?php $this->load->view('partials/reply-edit-form', ['form_id' => "flexforum_edit_secondary_reply_form-$reply[id]", 'reply_type' => FLEXFORUM_REPLY_REPLY_TYPE, 'reply' => $reply, 'type_id' => $reply['id']]); ?>
                </div>
            </div>
        </div>
    </div>
</div>