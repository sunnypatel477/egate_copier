<?php

use Carbon\Carbon;
?>
<div class="row">
    <div class="col-md-12">
        <div id="flexforum-topic" class="panel_s">
            <div class="panel-heading" style="display: flex; flex-direction: row; justify-content: space-between;">
                <div>
                    <span class="tw-font-semibold tw-text-lg">
                        <?php echo $title ?>
                    </span>
                    <span class="badge tw-bg-danger-500 tw-text-white">
                        <?php echo $category[0]['name'] ?>
                    </span>
                </div>

                <?php if (flexforum_has_permission('view')) { ?>
                    <a href="<?php echo flexforum_get_url() ?>" class="btn btn-primary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <?php echo flexforum_lang('topics'); ?>
                    </a>
                <?php } ?>
            </div>
            <div id="topic-body" class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex; flex-direction: row; justify-content: space-between;">
                            <span>
                                <img src="<?php echo $poster_image ?>" class="staff-profile-image-small">
                                <span class="tw-font-semibold">
                                    <?php echo $poster_name ?>
                                </span>
                            </span>
                            <span>
                                <?php echo $last_modified ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12 tw-mt-5">
                        <p>
                            <?php echo $topic['description'] ?>
                        </p>
                    </div>

                    <div class="col-md-12 tw-mt-5">
                        <?php
                        $topicfileDir = module_dir_url('flexforum/uploads/topic_file/');
                        $topic_filename =  $topicfileDir . $topic['topic_file'];
                        ?>
                        <img src="<?php echo $topic_filename; ?>" alt="<?php echo $topic['topic_file']; ?>" height="150">
                    </div>

                    <div class="col-md-12 tw-mt-5" style="display: flex; flex-direction: row; justify-content: space-between;">
                        <span>
                            <?php if (!$topic['closed']) { ?>
                                <?php $like_type = FLEXFORUM_TOPIC_LIKE_TYPE ?>
                                <button id='like-btn' data-type-id="<?php echo $topic['id'] ?>" data-type="<?php echo $like_type; ?>" class="btn btn-default tw-my-2" title="<?php echo flexforum_lang('like') ?>">
                                    <i id='like-btn-icon' class="<?php echo $topic_liked ? 'fa-solid' : 'fa-regular' ?> fa-heart text-danger"></i>
                                </button>
                                <button id='follow-btn' data-type-id="<?php echo $topic['id'] ?>" data-type="<?php echo $like_type; ?>" class="btn btn-default tw-my-2" title="<?php echo flexforum_lang('follow') ?>">
                                    <i id="follow-btn-icon" class="<?php echo $topic_followed ? 'fa-solid' : 'fa-regular' ?>  fa-thumbs-up text-info"></i>
                                </button>
                                <button id='reply-btn' onclick="new_flexforum_reply(); return false;" class="btn btn-default tw-my-2" title="<?php echo flexforum_lang('reply') ?>">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                            <?php } ?>
                        </span>
                        <span>
                            <span class='tw-px-1 tw-my-3 tw-inline-block tw-font-semibold tw-text-neutral-600'>
                                <span id="topic-likes">
                                    <?php echo $topic['likes'] ? $topic['likes'] : 0 ?>
                                </span>
                                <?php echo flexforum_lang('likes') ?>
                            </span>
                            <span class='tw-px-1 tw-my-3 tw-inline-block tw-font-semibold tw-text-neutral-600'>
                                <span id="topic-followers">
                                    <?php echo $topic['followers'] ? $topic['followers'] : 0 ?>
                                </span>
                                <?php echo flexforum_lang('followers') ?>
                            </span>
                            <span class='tw-px-1 tw-my-3 tw-inline-block tw-font-semibold tw-text-neutral-600'>
                                <span id="topic-replies">
                                    <?php echo $topic['replies'] ? $topic['replies'] : 0 ?>
                                </span>
                                <?php echo flexforum_lang('replies') ?>
                            </span>
                        </span>
                    </div>
                </div>

                <?php $this->load->view('partials/reply-form', ['form_id' => 'flexforum_reply_form']); ?>

                <?php $like_type = FLEXFORUM_REPLY_LIKE_TYPE ?>
                <?php $follower_type = FLEXFORUM_REPLY_FOLLOWER_TYPE ?>
                <?php foreach ($replies as $reply) {
                ?>
                    <?php $this->load->view('partials/reply-content', ['reply' => $reply, 'like_type' => $like_type, 'follower_type' => $follower_type, 'has_reply_form' => true, 'is_secondary_reply' => false, 'closed' => $topic['closed']]) ?>
                    <?php
                    if (isset($reply['repaly'])) { ?>
                        <?php foreach ($reply['repaly'] as $r) {

                            $r['poster_name'] = flexforum_get_user_name($r['user_id'], $r['user_type']);
                            $r['poster_image'] = flexforum_get_poster_image($r['user_id'], $r['user_type']);
                            $r['last_modified'] = Carbon::parse(_dt($r['date_updated']))->diffForHumans();
                            $r['reply_liked'] = flexforum_get_reply_liked($r['id']);
                            $r['reply_followed'] = flexforum_get_reply_followed($r['id']);
                            $r['is_reply_owner'] = (flexforum_get_user_id() === $r['user_id'] && flexforum_get_user_type() == $r['user_type']);
                        ?>
                            <?php $this->load->view('partials/reply-content-sub', ['reply' => $r, 'like_type' => $like_type, 'follower_type' => $follower_type, 'has_reply_form' => true, 'is_secondary_reply' => true, 'closed' => $topic['closed']]) ?>

                        <?php } ?>
                    <?php }
                    ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    'use strict';
    window.addEventListener('load', function() {

        $('#flexforum-topic').on('click', '.reply-like-btn', like_reply);
        $('#flexforum-topic').on('click', '.reply-follow-btn', follow_reply);
        $('#flexforum-topic').on('click', '.reply-reply-btn', new_secondary_flexforum_reply);
        $('#flexforum-topic').on('click', '.edit_replay', edit_secondary_flexforum_reply);
        $('#flexforum-topic').on('click', '.delete-replay-file', delete_replay_file);
        $('#flexforum-topic').on('click', '.show-secondary-replies-btn', flexforum_show_secondary_replies);

        $('#like-btn').on('click', function(e) {
            e.preventDefault();
            let url = getLikeBaseURL();
            let data = $(this).data();

            $.post(url, data,
                function(data, textStatus, jqXHR) {
                    if (data.success) {
                        $('#like-btn-icon').toggleClass('fa-solid')
                            .toggleClass('fa-regular');
                        $('#topic-likes').text(data.data.count);
                        // alert_float('success', data.message)
                    } else {
                        alert_float('danger', data.message)
                    }
                },
                "json"
            );
        })

        $('#follow-btn').on('click', function(e) {
            e.preventDefault();
            let url = getFollowBaseURL();
            let data = $(this).data();

            $.post(url, data,
                function(data, textStatus, jqXHR) {
                    if (data.success) {
                        $('#follow-btn-icon').toggleClass('fa-solid')
                            .toggleClass('fa-regular');
                        $('#topic-followers').text(data.data.count);
                    } else {
                        alert_float('danger', data.message)
                    }
                },
                "json"
            );
        })

        $('#flexforum_reply_form').on('submit', handle_reply_submission)
        $(document).on('submit', "form[id|='flexforum_secondary_reply_form']", handle_secondary_reply_submission)
        flexforum_reply_tinymce();
    });

    function flexforum_editor_config() {

        return {
            forced_root_block: "p",
            height: !is_mobile() ? 100 : 50,
            menubar: false,
            autoresize_bottom_margin: 15,
            plugins: [
                "table advlist codesample autosave mention" +
                (!is_mobile() ? " autoresize " : " ")
            ],
            toolbar: "insert formatselect bold forecolor backcolor" +
                (is_mobile() ? " | " : " ") +
                "alignleft aligncenter alignright bullist numlist | restoredraft",
            toolbar1: "",
        };
    }

    function flexforum_reply_tinymce() {
        init_editor(".flexforum-reply-admin");
        init_editor(".flexforum-reply-client", flexforum_editor_config());
    }

    function like_reply(e) {
        e.preventDefault()

        let url = getLikeBaseURL();
        let payload = $(this).data();

        $.post(url, payload,
            function(data, textStatus, jqXHR) {
                if (data.success) {
                    $(`#reply-like-btn-icon${payload.typeId}`).toggleClass('fa-solid')
                        .toggleClass('fa-regular');
                    $(`#reply-likes${payload.typeId}`).text(data.data.count);
                    // alert_float('success', data.message)
                } else {
                    alert_float('danger', data.message)
                }
            },
            "json"
        );
    }

    function follow_reply(e) {
        e.preventDefault()

        let url = getFollowBaseURL();
        let payload = $(this).data();

        $.post(url, payload,
            function(data, textStatus, jqXHR) {
                if (data.success) {
                    $(`#reply-follow-btn-icon${payload.typeId}`).toggleClass('fa-solid')
                        .toggleClass('fa-regular');
                } else {
                    alert_float('danger', data.message)
                }
            },
            "json"
        );
    }

    // function handle_reply_submission(e) {
    //     e.preventDefault();

    //     // Disable the submit button and display a loader to indicate 
    //     // that the request is processing
    //     $(this).find("button[type='submit']").prop('disabled', true);
    //     $(this).find("button[type='submit'] .loader").removeClass('hidden');

    //     let action = $('#flexforum_reply_form')[0].action;
    //     let data = {
    //         type: $(this).find("input[name='type']").val(),
    //         type_id: $(this).find("input[name='type_id']").val()
    //     }
    //     data.reply = tinymce.activeEditor.getContent();

    //     $.post(action, data,
    //         function(data, textStatus, jqXHR) {
    //             $("button[type='submit']").prop('disabled', false);
    //             $("button[type='submit'] .loader").addClass('hidden');

    //             if (data.success) {
    //                 alert_float('success', data.message)
    //                 $('#flexforum_reply_form').after(data.data)
    //                 // Increment the topic replies counter
    //                 let topicReplies = $('#topic-replies')
    //                 let topicRepliesCount = Number(topicReplies.text());
    //                 topicReplies.text(++topicRepliesCount);
    //                 hide_flexforum_reply()
    //             } else {
    //                 alert_float('danger', data.message)
    //             }
    //         },
    //         "json"
    //     );
    // }

    function handle_reply_submission(e) {
        e.preventDefault();

        // Disable the submit button and display a loader to indicate 
        // that the request is processing
        $(this).find("button[type='submit']").prop('disabled', true);
        $(this).find("button[type='submit'] .loader").removeClass('hidden');

        let action = $(this).attr('action');
        let formData = new FormData($(this)[0]);

        if (tinymce && tinymce.activeEditor) {
            let replyContent = tinymce.activeEditor.getContent();
            formData.append('reply', replyContent);
        } else {
            console.error('TinyMCE is not initialized or does not have an active editor.');
        }

        // AJAX request
        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data, textStatus, jqXHR) {
                $("button[type='submit']").prop('disabled', false);
                $("button[type='submit'] .loader").addClass('hidden');

                if (data.success) {
                    alert_float('success', data.message);
                    $('#flexforum_reply_form').after(data.data);
                    let topicReplies = $('#topic-replies');
                    let topicRepliesCount = Number(topicReplies.text());
                    topicReplies.text(++topicRepliesCount);
                    hide_flexforum_reply();
                    const currentUrl = window.location.href;
                    window.location.href = currentUrl;
                    window.location.reload(true);

                } else {
                    alert_float('danger', data.message);
                }
            },
            dataType: "json"
        });
    }

    // function handle_secondary_reply_submission(e) {
    //     e.preventDefault();

    //     // Disable the submit button and display a loader to indicate 
    //     // that the request is processing
    //     $(this).find("button[type='submit']").prop('disabled', true);
    //     $(this).find("button[type='submit'] .loader").removeClass('hidden');

    //     let form = $(this)[0]

    //     let action = form.action;
    //     let reply_id = $(this).data('id');
    //     let data = {
    //         type: $(this).find("input[name='type']").val(),
    //         type_id: $(this).find("input[name='type_id']").val()
    //     }
    //     data.reply = tinymce.activeEditor.getContent();

    //     $.post(action, data,
    //         function(data, textStatus, jqXHR) {
    //             $("button[type='submit']").prop('disabled', false);
    //             $("button[type='submit'] .loader").addClass('hidden');

    //             if (data.success) {
    //                 alert_float('success', data.message)
    //                 let form_id = "flexforum_secondary_reply_form-" + reply_id;
    //                 $(`#${form_id}`).after(data.data)
    //                 // Increment the reply replies counter
    //                 let replyReplies = $("#topic-replies-" + reply_id)
    //                 let replyRepliesCount = Number(replyReplies.text());
    //                 replyReplies.text(++replyRepliesCount);
    //                 hide_secondary_flexforum_reply(form_id);
    //             } else {
    //                 alert_float('danger', data.message)
    //             }
    //         },
    //         "json"
    //     );
    // }

    function handle_secondary_reply_submission(e) {
        e.preventDefault();

        $(this).find("button[type='submit']").prop('disabled', true);
        $(this).find("button[type='submit'] .loader").removeClass('hidden');

        let form = $(this)[0];

        let action = form.action;
        let reply_id = $(this).data('id');

        // Create a FormData object from the form
        let formData = new FormData(form);

        if (tinymce && tinymce.activeEditor) {
            let replyContent = tinymce.activeEditor.getContent();
            formData.append('reply', replyContent);
        } else {
            console.error('TinyMCE is not initialized or does not have an active editor.');
        }


        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            success: function(data, textStatus, jqXHR) {
                $("button[type='submit']").prop('disabled', false);
                $("button[type='submit'] .loader").addClass('hidden');

                location.reload();

                if (data.success) {
                    alert_float('success', data.message)
                    let form_id = "flexforum_secondary_reply_form-" + reply_id;
                    $(`#${form_id}`).after(data.data)
                    // Increment the reply replies counter
                    let replyReplies = $("#topic-replies-" + reply_id)
                    let replyRepliesCount = Number(replyReplies.text());
                    replyReplies.text(++replyRepliesCount);
                    hide_secondary_flexforum_reply(form_id);
                    const currentUrl = window.location.href;
                    window.location.href = currentUrl;
                    window.location.reload(true);
                } else {
                    alert_float('danger', data.message)
                }
            },
            dataType: "json"
        });
    }


    function flexforum_show_secondary_replies(e) {
        e.preventDefault();
        $(this).children().toggleClass('fa-angle-down');
        $(this).children().toggleClass('fa-angle-up');
        let type_id = $(this).data('id');
        let closed = $(this).data('closed');

        let secondary_reples = $(`.replies-reply-${type_id}`);

        if ($(`.replies-reply-${type_id}`).hasClass('hide')) {
            $(`.replies-reply-${type_id}`).removeClass('hide');
        } else {
            $(`.replies-reply-${type_id}`).addClass('hide');
        }
        // if (secondary_reples.length > 0) {
        //     secondary_reples.remove();
        // } else {
        //     let type = "<?php echo FLEXFORUM_REPLY_REPLY_TYPE ?>";
        //     let data = {
        //         type_id: type_id,
        //         type: type,
        //         closed: closed
        //     }

        //     $.getJSON(getRepliesBaseURL(), data,
        //         function(data, textStatus, jqXHR) {
        //             if (data.success) {
        //                 let form_id = "flexforum_secondary_reply_form-" + type_id;
        //                 $(`#${form_id}`).after(data.data)
        //             } else {
        //                 alert_float('danger', data.message)
        //             }
        //         }
        //     );
        // }
    }

    function resetReplyForm(secondary = false) {
        $('#flexforum_reply_form #additional').html('');
        $('#flexforum_reply_form input').not('[type="hidden"]').val('');
        $('#flexforum_reply_form textarea').not('[type="hidden"]').val('');
        tinymce.activeEditor.setContent('');
    }

    function hide_flexforum_reply(secondary = false) {
        resetReplyForm()
        $('#flexforum_reply_form').addClass('hidden');
    };

    function resetSecondaryReplyForm(form_id) {
        $(`#${form_id} #additional`).html('');
        $(`#${form_id} input`).not('[type="hidden"]').val('');
        $(`#${form_id} textarea`).not('[type="hidden"]').val('');
        $(`#${form_id} textarea`).val('');
        tinymce.activeEditor.setContent('');
    }

    function hide_secondary_flexforum_reply(form_id) {
        resetSecondaryReplyForm(form_id)
        $(`#${form_id}`).addClass('hidden');
    };

    function new_secondary_flexforum_reply(e) {
        e.preventDefault()
        let type_id = $(this).data('id');
        let form_id = `flexforum_secondary_reply_form-${type_id}`
        let form = $(`#${form_id}`)

        let display = form.css('display');

        if (display == 'none') {
            $(`#${form_id} #additional`).append(hidden_input('type_id', type_id));
            $(`#${form_id} #additional`).append(hidden_input('type', "<?php echo FLEXFORUM_REPLY_REPLY_TYPE ?>"));
        } else {
            resetSecondaryReplyForm(form_id)
        }

        form.toggleClass('hidden')
    }

    function edit_secondary_flexforum_reply(e) {
        e.preventDefault();

        let type_id = $(this).data('id');
        let form_id = `flexforum_edit_secondary_reply_form-${type_id}`;
        let form = $(`#${form_id}`);

        $.ajax({
            url: admin_url + "flexforum/flexforum/get_replay_by_id",
            type: 'POST',
            data: {
                type_id: type_id
            },
            success: function(response) {
                let replyData = (typeof response === "string") ? JSON.parse(response) : response;

                $(`#${form_id} textarea[name="reply"]`).val(replyData.reply);

                // $('#flexforum_topic_modal textarea[name="description"]').val(data.data.description);
                if (tinymce && tinymce.activeEditor) {

                    tinyMCE.activeEditor.setContent(replyData.reply);
                }

                // let plainTextReply = $('<div>').html(replyData.reply).text();

                // $(`#${form_id} textarea[name="reply"]`).val(plainTextReply);

                // $(`#${form_id} textarea[name="reply"]`).val(replyData.reply);

                $(`#${form_id} #additional`).empty();
                $(`#${form_id} #additional`).append(hidden_input('hidden_id', type_id));
                form.toggleClass('hidden');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching reply data:', error);
            }
        });
    }

    function delete_replay_file(e) {
        e.preventDefault();

        let type_id = $(this).data('id');

        $.ajax({
            url: admin_url + "flexforum/flexforum/delete_replay_file",
            type: 'POST',
            data: {
                type_id: type_id
            },
            success: function(response) {
                location.reload();
                alert_float("success", "replay file deleted successfully")
            },
        });
    }

    function new_flexforum_reply() {
        let form = $('#flexforum_reply_form')

        let display = form.css('display');

        if (display == 'none') {
            $('#additional').append(hidden_input('type_id', "<?php echo $topic['id'] ?>"));
            $('#additional').append(hidden_input('type', "<?php echo FLEXFORUM_TOPIC_REPLY_TYPE ?>"));
        } else {
            resetReplyForm()
        }

        form.toggleClass('hidden')
    }

    function getBaseURL() {
        return "<?php echo flexforum_get_url() ?>";
    }

    function getRepliesBaseURL() {
        return "<?php echo flexforum_get_url('replies') ?>";
    }

    function getLikeBaseURL() {
        return "<?php echo flexforum_get_url('like') ?>";
    }

    function getFollowBaseURL() {
        return "<?php echo flexforum_get_url('follow') ?>";
    }
</script>