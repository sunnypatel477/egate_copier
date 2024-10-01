<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">

<div id="wrapper">
    <div class="content">
        <input type="hidden" id="inquery_id" value="<?php echo $inquery->id; ?>">
        <div id="proposal-wrapper">
            <style>
                .imagePreview {
                    width: 100%;
                    border: 1px solid #EEEEEE;

                }

                .btn-primary {
                    display: block;
                    border-radius: 0px;
                    box-shadow: 0px 4px 6px 2px rgba(0, 0, 0, 0.2);
                    margin-top: -5px;
                }



                .button_foot {
                    margin-bottom: 15px;
                }

                .del {
                    position: absolute;
                    top: 0px;
                    right: 15px;
                    width: 30px;
                    height: 30px;
                    text-align: center;
                    line-height: 30px;
                    background-color: rgba(255, 255, 255, 0.6);
                    cursor: pointer;
                }


                .col-sm-3 {
                    width: 19.5%;
                    float: left;
                }

                @page {
                    size: auto;
                    size: landscape;
                    margin: 0mm;
                }

                @media print {
                    * {
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        print-color-adjust: exact !important;

                    }

                    html,
                    body {
                        height: 210mm;
                        width: 297mm;
                        margin: 0 !important;
                        margin-left: 2mm !important;
                    }

                    div.new_row {
                        /* page-break-after: always; */
                    }

                    .page {
                        margin: 0;
                        border: initial;
                        border-radius: initial;
                        width: initial;
                        min-height: initial;
                        box-shadow: initial;
                        background: initial;
                        page-break-after: always;
                    }

                    .col-sm-3 {
                        width: 19.5%;
                        float: left;
                    }

                    .imagePreview {
                        width: 100%;
                        /* border: 1px solid #EEEEEE; */
                        /* margin-left:1mm; */

                    }


                    div.divFooter {
                        position: fixed;
                        bottom: 2%;
                        left: 10%;
                        text-align: center;
                        font-size: 20px;
                        justify-content: center;
                    }

                    .button_foot {
                        display: none;
                    }

                    .mainpreview_top {
                        border-top: 1px dotted lightgray;
                    }

                    .mainpreview_bottom {
                        border-top: 1px dotted lightgray;
                        border-bottom: 1px dotted lightgray;
                    }


                    .mainpreview_p {
                        margin-bottom: 0;
                        border-left: 1px solid gray;
                        width: 100%;
                        text-align: left;
                    }

                    .mainpreview_p_last {
                        margin-bottom: 0;
                        border-left: 1px solid gray;
                        border-right: 1px solid gray;
                        width: 100%;
                        text-align: left;
                    }

                    .mainpreview_bottom_p {
                        margin: 0;
                        border-left: 1px solid gray;
                        width: 100%;
                        text-align: left;
                    }

                    .mainpreview_bottom_p_last {
                        margin: 0;
                        border-left: 1px solid gray;
                        border-right: 1px solid gray;
                        width: 100%;
                        text-align: left;
                    }

                    /* .dotted {
                        width: 5px;
                        border-bottom: 1px solid gray;
                        height: 1px;

                    } */
                    .main_bg {
                        height: 90%;

                    }
                }

                .copy_div {
                    /* display: none; */
                }
            </style>

            <!-- <div class="order_number row" style="padding: 15px;">
            <input type="text" name="order_number" id="order_number" placeholder="Order Number" style="width: 100%;height: 50px;font-size: 20px;font-weight: 700;text-align: center;">
        </div> -->
            <div class="order_number row" style="padding: 15px;">
                <!-- First row with name, contact no, email, and order number -->
                <div class="col-md-3">
                    <input type="text" name="name" value="<?php echo isset($inquery->name) ? $inquery->name : '' ?>" id="name" placeholder="Name" style="width: 100%; height: 50px; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
                <div class="col-md-3">
                    <input type="text" name="contact" value="<?php echo isset($inquery->contact) ? $inquery->contact : '' ?>" id="contact" placeholder="Contact Number" style="width: 100%; height: 50px; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
                <div class="col-md-3">
                    <input type="text" name="email" value="<?php echo isset($inquery->email) ? $inquery->email : '' ?>" id="email" placeholder="Email" style="width: 100%; height: 50px; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
                <div class="col-md-3">
                    <input type="text" name="order_number" value="<?php echo isset($inquery->order_number) ? $inquery->order_number : '' ?>" id="order_number" placeholder="Order Number" style="width: 100%; height: 50px; font-size: 20px; font-weight: 700; text-align: center;">
                </div>
            </div>

            <?php
            for ($i = 0; $i < count($images); $i += 10) {
                if ($i > 0) {
                    echo '<div style="break-after:page"></div>';
                }
                $style = '';
                $style_i = '';
                if ($images[$i]['type'] == 'Whole') {
                    $style = 'background-image: url("' . $images[$i]['background_url'] . '"); background-position: left top; background-size: 100% 100%;';
                } elseif ($images[$i]['type'] == 'Individual') {
                    $style_i = 'background-image: url("' . $images[$i]['background_url'] . '") !important;background-repeat:repeat;background-position:center center;background-size: cover;';
                }
            ?>
                <!-- <div style="break-after:page"></div> -->
                <div class="panel_s">
                    <div class="panel-body" style="padding: 25px;">
                        <br>
                        <div class="proposal-wrapper">
                            <div class="row main_bg" style='<?php echo $style ?>'>

                                <div class="col-sm-3 imgUp">
                                    <p class="mainpreview_p">&nbsp;</p>
                                    <div class="mainpreview mainpreview_top mainpreview_first_1" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i]['image_url']) ? $images[$i]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;">
                                        </div>
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i]['text_data']) ? $images[$i]['text_data'] : ''; ?>
                                    </div>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <p class="mainpreview_p">&nbsp;</p>
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_top" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+1]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 1]['image_url']) ? $images[$i + 1]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 1]['text_data']) ? $images[$i + 1]['text_data'] : ''; ?>
                                    </div>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <p class="mainpreview_p">&nbsp;</p>
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_top" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+2]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 2]['image_url']) ? $images[$i + 2]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 2]['text_data']) ? $images[$i + 2]['text_data'] : ''; ?>
                                    </div>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <p class="mainpreview_p">&nbsp;</p>
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_top" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+3]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 3]['image_url']) ? $images[$i + 3]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 3]['text_data']) ? $images[$i + 3]['text_data'] : ''; ?>
                                    </div>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <p class="mainpreview_p_last">&nbsp;</p>
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_top mainpreview_last_1" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+4]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 4]['image_url']) ? $images[$i + 4]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 4]['text_data']) ? $images[$i + 4]['text_data'] : ''; ?>
                                    </div>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <!-- <div class="dotted"></div> -->
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_bottom_first_1 mainpreview_bottom" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+5]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 5]['image_url']) ? $images[$i + 5]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 5]['text_data']) ? $images[$i + 5]['text_data'] : ''; ?>
                                    </div>
                                    <p class="mainpreview_bottom_p">&nbsp;</p>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>

                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_bottom" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+6]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 6]['image_url']) ? $images[$i + 6]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 6]['text_data']) ? $images[$i + 6]['text_data'] : ''; ?>
                                    </div>
                                    <p class="mainpreview_bottom_p">&nbsp;</p>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>

                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_bottom" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+7]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 7]['image_url']) ? $images[$i + 7]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 7]['text_data']) ? $images[$i + 7]['text_data'] : ''; ?>
                                    </div>
                                    <p class="mainpreview_bottom_p">&nbsp;</p>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>

                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_bottom" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+8]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 8]['image_url']) ? $images[$i + 8]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 8]['text_data']) ? $images[$i + 8]['text_data'] : ''; ?>
                                    </div>
                                    <p class="mainpreview_bottom_p">&nbsp;</p>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>

                                </div><!-- col-2 -->
                                <div class="col-sm-3 imgUp">
                                    <!-- <div class="dotted"></div> -->
                                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;">6.2cm X 4.2cm</p> -->
                                    <div class="mainpreview mainpreview_bottom mainpreview_bottom_last_1" style='padding: 6.5%;height: 79mm; <?php echo $style_i ?>' data-id="<?php echo $images[$i+9]['id']; ?>">
                                        <!--  -->
                                        <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                                            <img class="imagePreview" src="<?php echo isset($images[$i + 9]['image_url']) ? $images[$i + 9]['image_url'] : module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                                        </div>
                                        <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                                        <!-- <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> -->
                                        <?php echo isset($images[$i + 9]['text_data']) ? $images[$i + 9]['text_data'] : ''; ?>
                                    </div>
                                    <p class="mainpreview_bottom_p_last">&nbsp;</p>
                                    <div style="display: flex;flex-grow: 50%;" class="button_foot">
                                        <label class="btn btn-primary" style="flex: 50%;">
                                            Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src="">
                                            Crop
                                        </label>
                                        <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src="">
                                            Text
                                        </label>
                                    </div>
                                </div><!-- col-2 -->


                            </div><!-- row -->
                        </div>
                    </div>
                </div>
            <?php    }
            ?>

            <!-- <div style="break-after:page"></div> -->



        </div>
        <div id="imagePopup" title="Edit the image as you wish">
            <p style="font-size: 14px;color: red;margin: 0;">1. Move or Resize the Crop Window to Choose the Desired Printing Area</p>
            <p style="font-size: 14px;color: red;margin: 0;">2. Ctrl + Scroll Mouse Wheel to Enlarge or Reduce Image</p>
            <p style="font-size: 14px;color: red;margin: 0;">3. Click " Rotate" for landscape Image</p>
            <img id="imagePreview" src="" alt="Upload Image First">
            <!-- <button id="rotateButton">Rotate</button> -->
        </div>
        <div id="text-dialog" title="Add Text Caption">
            <label for="font-type">Text:</label>
            <input type="text" id="textbox" class="form-control">
            <hr>
            <label for="font-type">Font Type:</label>
            <select id="font-type" class="form-control">
                <option value="Arial">Arial</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Verdana">Verdana</option>
                <option value="Helvetica">Helvetica</option>
                <option value="Georgia">Georgia</option>
                <option value="Courier New">Courier New</option>
                <option value="Tahoma">Tahoma</option>
                <option value="Calibri">Calibri</option>
                <option value="Comic Sans MS">Comic Sans MS</option>
                <option value="Trebuchet MS">Trebuchet MS</option>
                <!-- Add more font options here -->
            </select>
            <br>
            <label for="font-size" style="margin-top: 15px;">Font Size:</label>
            <select id="font-size" class="form-control">
                <option value="12px">12px</option>
                <option value="14px">14px</option>
                <option value="16px">16px</option>
                <option value="18px">18px</option>
                <option value="20px">20px</option>
                <option value="22px">22px</option>
                <option value="24px">24px</option>
                <option value="26px">26px</option>
                <option value="28px">28px</option>
                <!-- Add more font size options here -->
            </select>
            <br>
            <label for="font-color" style="margin-top: 15px;">Font Color:</label>
            <input type="color" id="font-color" class="form-control" style="width: 100px;">
            <br>
        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-12">
                <button class="btn btn-success col-md-2" style="margin: 5px;" id="Print_page">Print</button>
                <button class="btn btn-info col-md-2" style="margin: 5px;" id="send_page">Update</button>

            </div>

        </div>


    </div>
</div>


<?php init_tail(); ?>
<script>
    $(function() {
        var width_pop = '600';
        var height_pop = '700';
        var width_pop_text = '500';
        var height_pop_text = '500';

        function isMobile() {
            return $(window).width() <= 768;
        }

        function set_width_pop() {
            if (isMobile()) {
                // Code for mobile devices
                width_pop = '300';
                height_pop = '400';
                width_pop_text = '300';
                height_pop_text = '400';
            } else {
                width_pop = '500';
                height_pop = '600';
            }

        }
        set_width_pop();

        function isValidEmail(email) {
            var emailRegex = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
            return emailRegex.test(email);
        }
        $("#Print_page").on("click", function() {
            // Get input field values
            var name = $("#name").val();
            var contact = $("#contact").val();
            var email = $("#email").val();
            var orderNumber = $("#order_number").val();
            // var address = $("#address").val();

            // Perform validation (you can customize the validation conditions as needed)
            if (name.trim() === "") {
                alert("Please enter a valid name.");
                return;
            }

            if (contact.trim() === "" || isNaN(contact)) {
                alert("Please enter a valid contact number.");
                return;
            }

            if (email.trim() === "" || !isValidEmail(email)) {
                alert("Please enter a valid email address.");
                return;
            }

            if (orderNumber.trim() === "") {
                alert("Please enter a valid order number.");
                return;
            }

            // if (address.trim() === "") {
            //     alert("Please enter a valid address.");
            //     return;
            // }


            $('.button_foot').hide();
            $('.order_number').hide();
            var divContents = $("#proposal-wrapper").html();
            var order_number = $("#order_number").val();
            // divContents += '<p style="text-align:center;margin-top: 5pc;font-size:18px;">'+order_number+'</p>';
            divContents += '<div class="divFooter">' + order_number + '-' + name + '-' + contact + '-' + email + '</div>';
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>Print Page</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
            $('.button_foot').show();
            $('.order_number').show();
        });
        var cropper; // Cropper.js instance
        $(document).on("change", ".uploadFile", function() {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function() { // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                    // uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                    uploadFile.closest(".imgUp").find('.imagePreview').attr('src', this.result);
                    // $('#imagePreview').attr('src', src);
                    uploadFile.closest(".imgUp").find('.crop-btn').attr('data-src', this.result);
                    uploadFile.closest(".imgUp").find('.crop-btn').addClass('crop_pending');
                }
            }

        });
        $(document).on("click", ".crop-btn", function() {
            var imageSrc = $(this).closest(".imgUp").find('.imagePreview').attr('src');
            if (imageSrc.startsWith("http://") || imageSrc.startsWith("https://")) {
                alert_float('danger', 'Upload Image First');
                return false;
            }

            $(this).parent().parent().addClass('croping_div');
            var src = $(this).attr('data-src');
            $('#imagePreview').attr('src', src);
            $('#imagePopup').dialog('open');
            initializeCropper();
            // $(this).html('Ready');
        });
        $(document).on('click', '.crop-text', function() {
            $(this).parent().parent().addClass('text_div');
            $('#text-dialog').dialog('open');
            // initializeCropper();
        });

        $("#text-dialog").dialog({
            autoOpen: false,
            modal: true,
            width: width_pop_text, // Set the desired width of the dialog
            height: height_pop_text,
            buttons: {
                Submit: function() {
                    var value = $("#textbox").val();
                    var font_type = $("#font-type").val();
                    var font_size = $("#font-size").val();
                    var color = $("#font-color").val();
                    $('.text_div .text_p').html(value);
                    $('.text_div .text_p').css('font-family', font_type);
                    $('.text_div .text_p').css('font-size', font_size);
                    $('.text_div .text_p').css('color', color);
                    // Perform any necessary actions with the cropped image data
                    $('.text_div').removeClass('text_div');
                    // Close the popup

                    $(this).dialog("close");
                }
            }
        });
        $('#imagePopup').dialog({
            autoOpen: false,
            modal: true,
            width: width_pop,
            open: function() {
                $(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .removeClass("ui-dialog-titlebar-close")
                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
            },
            height: height_pop,
            buttons: [{
                    text: "Crop",
                    class: 'btn btn-success',
                    click: function() {
                        var croppedCanvas = cropper.getCroppedCanvas({
                            width: 470, // Set the desired width of the cropped image
                            height: 630 // Set the desired height of the cropped image
                        });

                        // Retrieve the cropped image data
                        var croppedImageDataURL = croppedCanvas.toDataURL();
                        // $('.croping_div .imagePreview').css("background-image", "url(" + croppedImageDataURL + ")");
                        $('.croping_div .imagePreview').attr('src', croppedImageDataURL);

                        $('.croping_div .crop-btn').html('Ready');
                        $('.croping_div .crop-btn').css('background', 'yellow');
                        $('.croping_div .crop-btn').css('border-color', 'yellow');
                        $('.croping_div .crop-btn').css('color', '#000');
                        $('.croping_div .crop-btn').removeClass('crop_pending');
                        // Perform any necessary actions with the cropped image data
                        $('.croping_div').removeClass('croping_div');
                        // Close the popup
                        $(this).dialog('close');
                    }
                },
                {
                    text: "Rotate Image",
                    id: "rotateButton",
                    class: 'btn btn-info',
                    click: function() {
                        rotateImage();
                    }
                }
            ]
        });

        var cropper = null;

        function initializeCropper() {
            // Destroy the previous cropper instance (if exists)
            if (cropper) {
                cropper.destroy();
            }

            // Initialize Cropper.js on the image
            var image = document.getElementById('imagePreview');
            cropper = new Cropper(image, {
                // aspectRatio: 2/3, // Set the aspect ratio for cropping
                // viewMode: 3, // Display the image within the container without restrictions
                // autoCropArea: 3, // Automatically select the whole image for cropping
                preview: '.preview',
                viewMode: 2,
                modal: true,
                cropBoxResizable: true,
                responsive: true,
                center: true,

                aspectRatio: 470 / 630,

                ready: function() {
                    cropper.setCropBoxData({
                        width: 470,
                        height: 630,
                    });
                },
            });
        }

        // Function to rotate the image
        function rotateImage() {
            cropper.rotate(90); // Rotate the image by 90 degrees clockwise
        }
        $("#send_page").on("click", function() {
            
            var imageSrcList = getAllImageSrc();
            sendImageSrcToServer(imageSrcList);



        });
        function getAllImageSrc() {
        var imageSrcList = [];

        // $('.imagePreview').each(function() {
        //   imageSrcList.push($(this).attr('src'));
        // });

        $('.imagePreview').each(function() {
          var imageSrc = $(this).attr('src');
          var textHTML = $(this).closest('.imgUp').find('.text_p').prop('outerHTML'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          var id = $(this).closest('.imgUp').find('.mainpreview').attr('data-id'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          
          // Create an object with the image source and the full HTML of the 'text_p' element
          var elementWithText = {
            imageSrc: imageSrc,
            textHTML: textHTML,
            id: id
            
          };

          imageSrcList.push(elementWithText);
        });
        // console.log(imageSrcList);return false;
        return imageSrcList;
      }


      // AJAX function to send image src values to PHP
      function sendImageSrcToServer(imageSrcList) {
        var inquery_id = $('#inquery_id').val();
        $.ajax({
          url: admin_url + "instax_printing/update_saved_image",
          type: "POST",
          data: {
            images: JSON.stringify(imageSrcList),
            instax_printing_inquery_id : inquery_id
          },
          success: function(response) {
            // Handle the server's response if needed
            response = JSON.parse(response);
            if (response.status == 'success') {
              alert_float('success', response.message);
              setTimeout(function() {
                window.location.href = admin_url + 'instax_printing/print_page_view/' + inquery_id + '/';
              }, 3000);
            } else {
              alert_float('error', response.message);
            }

          },
          error: function(xhr, status, error) {
            console.error(error);
            alert_float('error', error);
          }
        });
      }
    });
</script>
</body>

</html>