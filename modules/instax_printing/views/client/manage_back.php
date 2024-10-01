<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/js/jquery.Jcrop.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/css/jquery.Jcrop.min.css"> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
<div class="container mt-4">
  <!-- Tab buttons -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#InstaxMini" aria-controls="InstaxMini" role="tab" data-toggle="tab">Instax Mini</a>
    </li>
    <li role="presentation">
      <a href="#InstaxSquare" aria-controls="InstaxSquare" role="tab" data-toggle="tab">Instax Square</a>
    </li>
    <li role="presentation">
      <a href="#InstaxWide" aria-controls="InstaxWide" role="tab" data-toggle="tab">Instax Wide</a>
    </li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="InstaxMini">
    <style>
        .imagePreview_div{
          height: 56mm !important;
        }
        .imagePreview{
          height: 56mm !important;
          width: 100%;
            border: 1px solid #EEEEEE;
        }
        .mainpreview {
          background : #FEEAD7;
        }
        </style>
      <div id="proposal-wrapper">
        <style>
          body {
            /* background-color: #f5f5f5; */
          }
          .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn){
            width: 100% !important;
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
          .main_bg{
              /* background-image: url("http://localhost/crystal_new/modules/instax_printing/uploads/background_images/1689697528_016e504d06fe.jpg");
              background-position: left top;
              background-size:100% 100%; */
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
          
/* 215mm */
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

            .mainpreview_last_1 {
              /* border-top: 1px dotted lightgray; */
             
            }

            .mainpreview_bottom {
              border-top: 1px dotted lightgray;
              border-bottom: 1px dotted lightgray;
            }

            .mainpreview_bottom_first_1 {
              /* border-top: 1px dotted lightgray;
              border-bottom: 1px dotted lightgray; */
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

            .dotted {
              /* width: 5px;
              border-bottom: 1px solid gray;
              height: 1px; */

            }
            .main_bg{
              height: 90%;
            
            }
          }

          .copy_div {
            /* display: none; */
          }
        </style>
        <div class="panel_s">
          <div class="panel-body" style="padding: 25px;">
            <br>
            <div class="proposal-wrapper">
          
              <div class="order_number row" style="padding: 15px;">
                <!-- First row with name, contact no, email, and order number -->
                <div class="col-md-2">
                  <input type="text" name="order_number" id="order_number" placeholder="Order Number" style="width: 100%; height: 34px; font-size: 15px; font-weight: 700; ">
                </div>
                <div class="col-md-2">
                  <input type="text" name="name" id="name" value="<?php echo isset($contact->firstname)  ? $contact->firstname : '' ?>" placeholder="Name" style="width: 100%; height: 34px; font-size: 15px; font-weight: 700; ">
                </div>
                <div class="col-md-3">
                  <input type="text" name="contact" id="contact" value="<?php echo isset($contact->phonenumber)  ? $contact->phonenumber : '' ?>" placeholder="Contact Number" style="width: 100%; height: 34px; font-size: 15px; font-weight: 700; ">
                </div>
                <div class="col-md-5">
                  <input type="text" name="email" id="email" value="<?php echo isset($contact->email)  ? $contact->email : '' ?>" placeholder="Email" style="width: 100%; height: 34px; font-size: 15px; font-weight: 700; ">
                </div>
                
              </div>
              <!-- <div class="order_number row" style="padding: 15px;padding-top: 0;">
                <div class="col-md-8">
                  <input type="text" name="address" id="address" placeholder="Address" style="width: 100%;height: 50px;font-size: 20px;font-weight: 700;">
                </div>
              </div> -->
              <div class="order_number row" style="padding: 15px;padding-top: 0;">
              <hr>
              <div class="col-md-5" style="text-align: center;padding-top: 5px;"><span style="color: red;font-size: 17px;">Select Background Image If You Want</span></div>
              <div class="col-md-3">
                <select name="apply_type" id="apply_type" style="width: 100%;height: 50px;font-size: 20px;font-weight: 700;text-align: center;">
                <option value="">-- Apply Image to --</option>
                    <option value="Whole">Whole Page</option>
                    <option value="Individual">Individual Photo</option>
                    
                  </select>
                </div>
              <div class="col-md-4">
                  <select name="background" id="background" style="width: 100%;height: 50px;font-size: 20px;font-weight: 700;">
                    <option value="">-- Select Background --</option>
                    <?php
                    if (!empty($background_imags)) {
                      foreach ($background_imags as $background_img) {
                    ?>
                        <option value="<?php echo $background_img['id']; ?>" data-url="<?php echo $background_img['background_url'];  ?>"><?php echo $background_img['image_name']; ?></option>
                    <?php
                      }
                    }
                    ?>

                  </select>
                </div>
                
            </div>
              <div class="row main_bg" data-count="0">

                <div class="col-sm-3 imgUp">
                  <p class="mainpreview_p">&nbsp;</p>
                  <!--  -->
                  <div class="mainpreview mainpreview_top mainpreview_first_1" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;">
                    </div>
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_top mainpreview_last_1" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_bottom_first_1 mainpreview_bottom" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <p class="mainpreview_bottom_p">&nbsp;</p>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <p class="mainpreview_bottom_p">&nbsp;</p>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <p class="mainpreview_bottom_p">&nbsp;</p>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <p class="mainpreview_bottom_p">&nbsp;</p>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
                  <div class="mainpreview mainpreview_bottom mainpreview_bottom_last_1" style="padding: 6.5%;height: 79mm;">
                    <!--  -->
                    <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;">
                      <img class="imagePreview" src="<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>" style="height: 62mm;  ">
                    </div>
                    <!-- <p style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;">Instax Mini</p> -->
                    <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p>
                  </div>
                  <p class="mainpreview_bottom_p_last">&nbsp;</p>
                  <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot">
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
              <!-- <div style="break-after:page"></div> -->

            </div><!-- container -->

          </div>
        </div>
        <!-- <i class="fa fa-plus imgAdd"></i> -->
        <div class="row button_foot" style="margin-top: 15px;">
        <div class="col-md-12">
          <button class="btn btn-warning col-md-2 imgAdd">Add Page</button>
          <div class="col-md-1"></div>
          <button class="btn btn-danger col-md-2 delete_page">Delete Page</button>
          <div class="col-md-1"></div>
          <button class="btn btn-info col-md-2" id="Print_page">Print Preview</button>
          <div class="col-md-1"></div>
          <button class="btn btn-success col-md-2" id="send_page">Order Printing</button>
        </div>

      </div>
      </div>
      
    </div>


    <div role="tabpanel" class="tab-pane fade" id="InstaxSquare">
      <h2 class="text-center">Comming Soon</h2>
    </div>

    <div role="tabpanel" class="tab-pane fade" id="InstaxWide">
      <h2 class="text-center">Comming Soon</h2>
    </div>
  </div>
</div>
<div id="imagePopup" title="Crop Image">
  <img id="imagePreview" src="" alt="Preview">
</div>
<div id="text-dialog" title="Add Text">
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
  <label for="font-color"  style="margin-top: 15px;">Font Color:</label>
  <input type="color" id="font-color"  style="margin-top: 15px;" >
  <br>
</div>
<script>
  $(".imgAdd").click(function() {
    var no_image = '<?php echo module_dir_url('instax_printing', 'images/no_image.png'); ?>';
    var drp = '<div class="order_number row" style="padding: 15px;padding-top: 0;">';
    drp +='<div class="col-md-5" style="text-align: center;padding-top: 5px;"><span style="color: red;font-size: 17px;">Select Background Image If You Want</span></div><div class="col-md-3"><select name="apply_type" id="apply_type" style="width: 100%;height: 36px;font-size: 15px;"><option value="">-- Apply Image to --</option><option value="Whole">Whole Page</option><option value="Individual">Individual Photo</option></select></div><div class="col-md-4"><select name="background" id="background" style="width: 100%;height: 36px;font-size: 15px;"><option value="">-- Select Background --</option>' ;
    drp += ' <?php if (!empty($background_imags)) { foreach ($background_imags as $background_img) { ?> <option value="<?php echo $background_img['id']; ?>" data-url="<?php echo $background_img['background_url'];  ?>"><?php echo $background_img['image_name']; ?></option> <?php } } ?>';
    drp += '</select></div></div>';
                  
    var rowCount = $('.main_bg').length;   
    // const clone = node.cloneNode(true);
    // $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><div style="display: flex;flex-grow: 50%;"><label class="btn btn-primary" style="flex: 50%;">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"></label><label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary">Crop</label></div><i class="fa fa-times del"></i></div>');
    // $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-3 imgUp"> <div class="mainpreview" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div"   style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="'+no_image+'"  style="height: 62mm;"> </div> </div> <div class="button_foot" style="display: flex;flex-grow: 50%;padding-top: 20px;"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> </div><i class="fa fa-times del"></i> </div>');
    // $(this).closest(".row").find('.imgAdd').before('<h3 class="page_break" style="text-align: center;color: red;">Page Break</h3><div style="break-after:page"></div><div class="panel_s"> <div class="panel-body" style="padding: 25px;"> <br>  <div class="row"> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p> <div class="mainpreview mainpreview_top mainpreview_first_1" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm;"> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p_last"> </p>  <div class="mainpreview mainpreview_top mainpreview_last_1" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <div class="dotted"></div>  <div class="mainpreview mainpreview_bottom_first_1 mainpreview_bottom" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <div class="dotted"></div>  <div class="mainpreview mainpreview_bottom mainpreview_bottom_last_1" style="padding: 6.5%;background: antiquewhite;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p_last"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> </div> <i class="fa fa-times del"></i> </div> </div> </div>');
    $('.panel_s:last').after('<h3 class="page_break" style="text-align: center;color: red;">Page Break</h3><div style="break-after:page"></div><div class="panel_s"> <div class="panel-body" style="padding: 25px;"> '+drp+'  <div class="row main_bg" data-count="'+rowCount+'"> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p> <div class="mainpreview mainpreview_top mainpreview_first_1" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm;"> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p"> </p>  <div class="mainpreview mainpreview_top" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <p class="mainpreview_p_last"> </p>  <div class="mainpreview mainpreview_top mainpreview_last_1" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <div class="dotted"></div>  <div class="mainpreview mainpreview_bottom_first_1 mainpreview_bottom" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp">  <div class="mainpreview mainpreview_bottom" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> <div class="col-sm-3 imgUp"> <div class="dotted"></div>  <div class="mainpreview mainpreview_bottom mainpreview_bottom_last_1" style="padding: 6.5%;height: 79mm;">  <div class="imagePreview_div" style="text-align: center;justify-content: center;display: grid;height: 62mm;margin-top:3mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 62mm; "> </div>  <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:10px 0 0 0;"></p> </div> <p class="mainpreview_bottom_p_last"> </p> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot"> <label class="btn btn-primary" style="flex: 50%;"> Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 50%;background: red;border-color: red;" class="btn btn-primary crop-btn" data-src=""> Crop </label> <label style="flex: 50%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text" data-src=""> Text </label> </div> </div> </div> <i class="fa fa-times del"></i> </div> </div> </div>');
  });
  $(document).on("click", "i.del", function() {
    // 	to remove card
    // $(this).parent().remove();
    
    $(this).closest(".panel_s").remove();
    $(".page_break:last").remove();
    // $(this).parent().find('.panel_s').remove();
    // to clear image
    // $(this).parent().find('.imagePreview').css("background-image","url('')");
  });
  $(document).on("click",".delete_page",function(){
      var classCount = $('.panel_s').length;
      if(classCount > 1){
        $(".panel_s:last").remove();
    $(".page_break:last").remove();
      }else{
        alert_float("warning","You can't delete this page");
        // alert_float("","You can't delete this page");
      }
    
    // $(this).closest(".panel_s").remove();
  });
  $(function() {
    function isValidEmail(email) {
      var emailRegex = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
      return emailRegex.test(email);
    }

    function addBackgroundImage() {
      document.body.classList.add('print-bg');
    }

    // Function to remove background image after printing
    function removeBackgroundImage() {
      document.body.classList.remove('print-bg');
    }
    $('body').on('change','[name="background"]', function() {
      set_background_page(this);
      
      // $('.mainpreview').css("background-image", "url(" + selectedDataUrl + ")");

    });
    $("body").on('change','[name="apply_type"]', function() {
      set_background_page(this);
    });
    function set_background_page(_this){
    //  var apply_type = $("#apply_type").val();
     var apply_type = $(_this).closest('.panel-body').find("#apply_type").val();
     if(apply_type != ''){
      var selectedDataUrl = $(_this).closest('.panel-body').find('#background').find(":selected").data("url");
      var selectedDataval = $(_this).closest('.panel-body').find('#background').find(":selected").val();
      var page_number = $(_this).closest('.panel-body').find('.main_bg').attr("data-count");
      
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-page", page_number);
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-type", apply_type);
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-background", selectedDataval);
     if(apply_type == 'Individual'){
      $(_this).closest('.panel-body').find('.mainpreview').css("background-image", "url(" + selectedDataUrl + ")");
      $(_this).closest('.panel-body').find('.mainpreview').css("background-repeat", "repeat");
      $(_this).closest('.panel-body').find('.mainpreview').css("background-position", "center center");
      $(_this).closest('.panel-body').find('.mainpreview').css("background-size", "cover");
      $(_this).closest('.panel-body').find('.mainpreview').css("background-size", "cover");
      $(_this).closest('.panel-body').find('.mainpreview').css("background-attachment", "unset");
      $(_this).closest('.panel-body').find('.main_bg').removeAttr("style");
      
      
      // $('.mainpreview').css("background-image", "url(" + selectedDataUrl + ")");
      // $('.mainpreview').css("background-repeat", "repeat");
      // $('.mainpreview').css("background-position", "center center");
      // $('.mainpreview').css("background-size", "cover");
      //   $('.mainpreview').css("background-attachment", "unset");
      //   $(".main_bg").removeAttr("style");
    }else if(apply_type == "Whole"){
      $(_this).closest('.panel-body').find('.mainpreview').css("background-image", "");
      $(_this).closest('.panel-body').find('.main_bg').css("background-image", "url(" + selectedDataUrl + ")");
      $(_this).closest('.panel-body').find('.main_bg').css("background-position", "left top");
      $(_this).closest('.panel-body').find('.main_bg').css("background-size", "100% 100%");
      // $('.main_bg').css("background-image", "url(" + selectedDataUrl + ")");
      // $('.main_bg').css("background-position", "left top");
      //   $('.main_bg').css("background-size", "100% 100%");
      //   $('.mainpreview').css("background-image", "");
      }
      
    } 
      // 
      // $('.mainpreview').css("background-size", "cover");
    }
    // Attach event listeners
    window.onbeforeprint = addBackgroundImage;
    window.onafterprint = removeBackgroundImage;
    $("#Print_page").on("click", function() {
     
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
      $('.page_break').hide();
      var divContents = $("#proposal-wrapper").html();
      var order_number = $("#order_number").val();
      var name = $("#name").val();
      var contact = $("#contact").val();
      // divContents += '<p style="text-align:center;margin-top: 5pc;font-size:18px;">'+order_number+'</p>';
      // var head = '<div class="divFooter">' + order_number + '-'+name+'-'+contact+'-'+email+'-'+address+'</div>';
      divContents += '<div class="divFooter">' + order_number + '-'+name+'-'+contact+'-'+email+'</div>';
      var printWindow = window.open('', '', 'width=' + screen.width + ',height=' + screen.height);
      // printWindow.document.write('<style>@page { size: landscape; } @media print { .row  {height:90%;  }}</style>');
      printWindow.document.write('<html><head><title>Print Page</title>');
      // $('<style media="print"> body {} </style>').appendTo('head');
      printWindow.document.write('</head><body>');
      // printWindow.document.write(head);
      printWindow.document.write(divContents);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
      $('.button_foot').show();
      $('.order_number').show();
      $('.page_break').show();
    });
    // var doc = new jsPDF();


    $(document).ready(function() {
      // window.jsPDF = window.jspdf.jsPDF;
      $("#send_page").on("click", function() {
        // var imageSrcList = getAllImageSrc();
        // return false;
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
        //   alert("Please enter a valid address.");
        //   return;
        // }


        //   $('.button_foot').hide();
        //   $('.order_number').hide();
        //   var divContents = $("#proposal-wrapper").html();
        //   var order_number = $("#order_number").val();
        //   // divContents += '<p style="text-align:center;margin-top: 5pc;font-size:18px;">'+order_number+'</p>';
        //   divContents += '<div class="divFooter">'+order_number+'</div>';

        //   var printWindow = window.open('', '', 'height=400,width=800');
        //   printWindow.document.write('<html><head><title>Print Page</title>');
        //   printWindow.document.write('</head><body >');
        //   printWindow.document.write(divContents);
        //   printWindow.document.write('</body></html>');
        //   printWindow.document.close();

        //   printWindow.onload = function() {
        //     // var pdf = new jsPDF();
        //     var doc = new jsPDF();

        // doc.html(document.body, {
        //    callback: function (doc) {
        //      doc.save();
        //      printWindow.close();
        //    },
        //    x: 10,
        //    y: 10
        // });
        var imageSrcList = getAllImageSrc();
        sendImageSrcToServer(imageSrcList);

        // };

        // $('.button_foot').show();
        // $('.order_number').show();


      });

      function getAllImageSrc() {
        var imageSrcList = [];

        // $('.imagePreview').each(function() {
        //   imageSrcList.push($(this).attr('src'));
        // });

        $('.imagePreview').each(function() {
      var imageSrc = $(this).attr('src');
      var textHTML = $(this).closest('.imgUp').find('.text_p').prop('outerHTML'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
      var page = $(this).closest('.imgUp').find('.mainpreview').attr('data-page'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
      var type = $(this).closest('.imgUp').find('.mainpreview').attr('data-type'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
      var background = $(this).closest('.imgUp').find('.mainpreview').attr('data-background'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
      // Create an object with the image source and the full HTML of the 'text_p' element
      var elementWithText = {
        imageSrc: imageSrc,
        textHTML: textHTML,
        page:page,
        type : type,
        background:background
      };

      imageSrcList.push(elementWithText);
    });
// console.log(imageSrcList);return false;
        return imageSrcList;
      }


      // AJAX function to send image src values to PHP
      function sendImageSrcToServer(imageSrcList) {

        var name = $("#name").val();
        var contact = $("#contact").val();
        var email = $("#email").val();
        var orderNumber = $("#order_number").val();
        // var address = $("#address").val();
        var background = $("#background").val();
        var apply_type = $("#apply_type").val();
        // return false;
        $.ajax({
          url: site_url + "instax_printing/Instax_printing_client/save_image",
          type: "POST",
          data: {
            images: JSON.stringify(imageSrcList),
            name: name,
            contact: contact,
            email: email,
            order_number: orderNumber,
            address: '',
            background: background,
            apply_type: apply_type
          },
          success: function(response) {
            // Handle the server's response if needed
            response = JSON.parse(response);
            if (response.status == 'success') {
              alert_float('success', response.message);
              setTimeout(function() {
                window.location.href = site_url + 'instax_printing/Instax_printing_client';
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
      // // var doc = new jsPDF();
      //   $("#send_page").on("click",function(){
      //     var divContents = $("#proposal-wrapper").html();
      // var order_number = $("#order_number").val();
      // divContents += '<div class="divFooter">' + order_number + '</div>';
      // window.jsPDF = window.jspdf.jsPDF;
      // var doc = new window.jsPDF();
      // console.log(divContents);
      // return false;
      // doc.html(divContents, {
      //   callback: function(pdf) {
      //     pdf.save('output.pdf');
      //   },
      //   x: 10,
      //   y: 10
      // });

      // $('.button_foot').show();
      // $('.order_number').show();
      //   });
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
        }
      }

    });
    $(document).on("click", ".crop-btn", function() {
      
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
      width: 500, // Set the desired width of the dialog
      height: 500,
      buttons: {
        Submit: function() {
          var value = $("#textbox").val();
          var font_type = $("#font-type").val();
          var font_size = $("#font-size").val();
          var color = $("#font-color").val();
          $('.text_div .text_p').html(value);
          $('.text_div .text_p').css('font-family',font_type);
          $('.text_div .text_p').css('font-size',font_size);
          $('.text_div .text_p').css('color',color);
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
      width: 'auto',
      height: 'auto',
      buttons: [{
        text: "Crop",
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
          $('.croping_div .crop-btn').css('background','yellow');
          $('.croping_div .crop-btn').css('border-color','yellow');
          $('.croping_div .crop-btn').css('color','#000');
          // Perform any necessary actions with the cropped image data
          $('.croping_div').removeClass('croping_div');
          // Close the popup
          $(this).dialog('close');
        }
      }]
    });

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
  });
</script>