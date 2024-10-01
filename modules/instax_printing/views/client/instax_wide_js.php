<script>
  $(document).ready(function() {

    $("#contact_wide").on('keydown', function(event) {
      // Allow the following keys: 0-9, arrow keys, backspace, delete
      if (event.key.match(/^[0-9]|ArrowLeft|ArrowRight|Backspace|Delete$/)) {
        var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers
        return; // Allow the input
      }

      event.preventDefault(); // Prevent input if the key is not allowed
    });
    $("#contact_wide").on('focusout', function() {
      var inputValue = $(this).val();
      var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers

      if (!pattern.test(inputValue)) {
        $(this).addClass('invalid');
        $("#contact_wide").focus(); // Set focus back to the textbox
      } else {
        $(this).removeClass('invalid');
      }
    });

    $("#order_image_upload_wide").change(function() {
      const fileInput = this;
      const fileDisplayContainer = $("#uploaded_file_container_wide");
      const fileDisplay = $("#uploaded_file_name_wide");
      const closeIcon = $("#close_icon_wide");
      const uploadContainer = $("#upload_container_wide");

      if (fileInput.files.length > 0) {
        const uploadedFileName = fileInput.files[0].name;
        fileDisplay.text(uploadedFileName);
        fileDisplayContainer.show();
        uploadContainer.hide();
      }
      const selectedFile = event.target.files[0];
      const reader = new FileReader();

      // Set up the FileReader onload event
      reader.onload = function(event) {
        // The result contains the base64 encoded string
        const base64String = event.target.result;

        // You can use the base64String as needed (e.g., display a preview, upload to the server)

        $('#order_image_preview_wide').attr("src", base64String);
      };

      // Read the file as a data URL (which contains the base64 encoding)
      reader.readAsDataURL(selectedFile);
    });

    $("#close_icon_wide").click(function() {
      const fileInput = $("#order_image_upload_wide");
      const fileDisplayContainer = $("#uploaded_file_container_wide");
      const fileDisplay = $("#uploaded_file_name_wide");
      const closeIcon = $("#close_icon_wide");
      const uploadContainer = $("#upload_container_wide");

      fileInput.val(""); // Reset the file input
      fileDisplayContainer.hide();
      fileDisplay.text("");
      uploadContainer.show();
    });

    $("#shippinng_image_upload_wide").change(function() {
      const fileInput = this;
      const fileDisplayContainer = $("#shippinng_uploaded_file_container_wide");
      const fileDisplay = $("#shippinng_uploaded_file_name_wide");
      const closeIcon = $("#shippinng_close_icon_wide");
      const uploadContainer = $("#shippinng_upload_container_wide");

      if (fileInput.files.length > 0) {
        const uploadedFileName = fileInput.files[0].name;
        fileDisplay.text(uploadedFileName);
        fileDisplayContainer.show();
        uploadContainer.hide();
      }
      const selectedFile = event.target.files[0];
      const reader = new FileReader();

      // Set up the FileReader onload event
      reader.onload = function(event) {
        // The result contains the base64 encoded string
        const base64String = event.target.result;

        // You can use the base64String as needed (e.g., display a preview, upload to the server)

        $('#shippinng_image_preview_wide').attr("src", base64String);
      };

      // Read the file as a data URL (which contains the base64 encoding)
      reader.readAsDataURL(selectedFile);
    });

    $("#shippinng_close_icon_wide").click(function() {
      const fileInput = $("#shippinng_image_upload_wide");
      const fileDisplayContainer = $("#shippinng_uploaded_file_container_wide");
      const fileDisplay = $("#shippinng_uploaded_file_name_wide");
      const closeIcon = $("#shippinng_close_icon_wide");
      const uploadContainer = $("#shippinng_upload_container_wide");

      fileInput.val(""); // Reset the file input
      fileDisplayContainer.hide();
      fileDisplay.text("");
      uploadContainer.show();
    });
  });
</script>



<script>
  $('.selectpicker').selectpicker({
    template: {
      caret: '<span class="glyphicon glyphicon-chevron-down"></span>'
    }
  });
  $(".imgAdd_wide").click(function() {
    var no_image = '<?php echo module_dir_url('instax_printing', 'images/instax_wide.png'); ?>';
    var drp = '<div class="order_number_wide row">';
    drp += '<div class="col-sm-12"><span style="color: red;font-size: 14px;">SET IMAGE AS BACKGOUND</span></div>';
    drp += '<div class="col-md-3" style="padding: 5px;"><select name="apply_type_wide" id="apply_type_wide" class="selectpicker" style="width: 100%;height: 36px;font-size: 15px;"><option value="">--Apply Image as--</option><option value="Whole">Photo Book Style</option><option value="Individual">Individual Photo</option></select></div>';
    // drp += '<div class="col-md-2" style="padding: 5px;"><select id="category_wide" name="category_wide" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_text'); ?>" data-live-search="true" data-style="btn-default">';
    // drp += '<option value="">-- Select category --</option>';
    // <?php //foreach ($category as $key => $c) { ?>
    //   drp += '<option value="<?php //echo $c['id']; ?>"><?php //echo $c['name']; ?></option>';
    // <?php //} ?>
    // drp += '</select></div>';
    drp += '<div class="col-md-3" style="padding: 5px;"><select id="event_category_wide" name="event_category_wide" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_text'); ?>" data-live-search="true" data-style="btn-default">';
    drp += '<option value="">-- Select Event category --</option>';
    <?php foreach ($event_category as $key => $c) { ?>
      drp += '<option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?> (<?php echo get_event_category_images_count($c['id']); ?>)</option>';
    <?php } ?>
    drp += '</select></div>';



    drp += '<div class="col-md-6" style="padding: 5px;"><select name="background_wide" id="background_wide" class="background_drp_wide selectpicker" style="width: 100%;height: 36px;font-size: 15px;" class="selectpicker"><option value="">-- Select Background --</option>';

    drp += '</select></div></div>';

    var rowCount = $('.main_bg_wide').length;
    var BoxCount = rowCount * 10;
    $('#proposal-wrapper-wide .panel_s:last').after('<h3 class="page_break" style="text-align: center;color: red;">Page Break</h3> <div class="break_page" style="break-after:page;page-break-before: always;"></div> <div class="panel_s"> <div class="panel-body" style="padding: 25px;"> ' + drp + ' <div class="main_bg_wide" data-count="' + rowCount + '"> <div class="row"> <div class="col-sm-5 imgUp" data-count="' + (BoxCount + 1) + '"> <p class="mainpreview_p"> </p> <div class="mainpreview mainpreview_top mainpreview_first_1"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm;"> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> <div class="col-sm-3 imgUp" data-count="' + (BoxCount + 2) + '"> <p class="mainpreview_p"> </p> <div class="mainpreview mainpreview_top"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm; "> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide crop-btn_wide_mini" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide_mini" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> <div class="col-sm-5 imgUp" data-count="' + (BoxCount + 3) + '"> <p class="mainpreview_p_last"> </p> <div class="mainpreview mainpreview_top mainpreview_last_1"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm; "> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <div style="display: flex;flex-grow: 50%;padding-top: 20px;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> </div> <div class="row"> <div class="col-sm-5 imgUp" data-count="' + (BoxCount + 4) + '"> <div class="mainpreview mainpreview_bottom_first_1 mainpreview_bottom"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm; "> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> <div class="col-sm-3 imgUp" data-count="' + (BoxCount + 5) + '"> <div class="mainpreview mainpreview_bottom"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm; "> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <p class="mainpreview_bottom_p"> </p> <div style="display: flex;flex-grow: 50%;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide crop-btn_wide_mini" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide_mini" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> <div class="col-sm-5 imgUp" data-count="' + (BoxCount + 6) + '"> <div class="mainpreview mainpreview_bottom mainpreview_bottom_last_1"> <div class="imagePreview_div" style="text-align: center;height: 63mm;margin-top:1mm;"> <img class="imagePreview" src="' + no_image + '" style="height: 63mm; "> </div> <p class="text_p" style="text-align: center;font-size: 15px;font-weight: 700;margin:15px 0 0 0;position:relative;z-index:9;"></p> </div> <p class="mainpreview_bottom_p_last"> </p> <div style="display: flex;flex-grow: 50%;" class="button_foot_wide"> <label class="btn btn-primary" style="flex: 25%;"> <i class="fa fa-cloud-upload" aria-hidden="true"></i><input type="file" class="uploadFile_wide img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;"> </label> <label style="flex: 25%;background: red;border-color: red;" class="btn btn-primary crop-btn_wide" data-src=""> Crop </label> <label style="flex: 25%;background: red;border-color: black;color:#fff;background-color: #000;" class="btn btn-primary crop-text_wide" data-src=""> Text </label> <label class="btn btn-primary btn-cover_wide" style="flex: 25%;background: orange;border-color: orange;color:#fff;background-color: orange;"> <i class="fa fa-file-image" aria-hidden="true"></i> </label> </div> </div> </div> </div> </div> </div> </div>');
    const select = $(".selectpicker");
    select.selectpicker('refresh');
  });

  $(document).on("click", ".delete_page_wide", function() {
    var classCount = $('#proposal-wrapper-wide .panel_s').length;
    if (classCount > 1) {
      $("#proposal-wrapper-wide .panel_s:last").remove();
      $("#proposal-wrapper-wide .page_break:last").remove();
    } else {
      alert_float("warning", "You can't delete this page");
      // alert_float("","You can't delete this page");
    }

    // $(this).closest(".panel_s").remove();
  });
  var width_pop = '600';
  var height_pop = '700';
  var width_pop_text = '500';
  var height_pop_text = '500';
  $(function() {

    function isValidEmail(email) {
      var emailRegex = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
      return emailRegex.test(email);
    }

   
    $('body').on('change', '[name="background_wide"]', function() {
      set_background_page_wide(this);

      // $('.mainpreview').css("background-image", "url(" + selectedDataUrl + ")");

    });
    $("body").on('change', '[name="apply_type_wide"]', function(e) {
      e.preventDefault();
      var data = {}
      data.apply_type = $(this).val();
      // data.category = $(this).closest('.order_number_wide').find('[name="category_wide"]').val();
      data.category = '3';
      data.event_category = $(this).closest('.order_number_wide').find('[name="event_category_wide"]').val();
      var _this = this;
      get_background_by_category_wide(data, _this);
      set_background_page_wide(this);
      var _this = this;
      var page_number = $(_this).closest('.panel-body').find('.main_bg_wide').attr("data-count");

      $(_this).closest('.panel-body').find('.mainpreview').attr("data-page", page_number);
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-type", '');
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-background", '');

      var class_name = 'mainpreview_wide_' + page_number;
      $(_this).closest('.panel-body').find('.mainpreview').addClass(class_name);
      $('.' + class_name + '_style').remove();
      $(_this).closest('.panel-body').find('.main_bg_wide').removeAttr("data-bgurl");
      var class_name = 'main_bg_wide_' + page_number;
      // $(this).addClass(class_name);
      $('.' + class_name + '_style').remove();

      $(_this).closest('.panel-body').find('.main_bg_wide').removeAttr("style");

      $(_this).closest('.panel-body').find('.mainpreview').css("background-image", "");
      $(_this).closest('.panel-body').find('#background_wide').val('').selectpicker('refresh');

      // $('.refresh_setting').click();
      // $(this).closest('.order_number_wide').find('[name="category_wide"]').val('').selectpicker('refresh');
      $(this).closest('.order_number_wide').find('[name="event_category_wide"]').val('').selectpicker('refresh');
    });
    $("body").on('change', '[name="event_category_wide"]', function(e) {
      e.preventDefault();
      var data = {}
      data.event_category = $(this).val();
      // data.category = $(this).closest('.order_number_wide').find('[name="category_wide"]').val();
      data.category = '3';
      data.apply_type = $(this).closest('.order_number_wide').find('[name="apply_type_wide"]').val();
      var _this = this;
      get_background_by_category_wide(data, _this);
    });
    /****Pending */
    $("body").on('change', '#eventbackgroundPopup_wide', function(e) {
      e.preventDefault();
      var data = {}
      data.event_category = $(this).val();
      data.category = '3';
      // data.category = $('#category').val();
      data.apply_type = 'individual';
      var _this = this;
      get_background_by_category_popup_wide(data, _this);
    });
    /****End Pending */
    $("body").on('click', ".refresh_setting_wide", function() {
      var _this = this;
      var page_number = $(_this).closest('.panel-body').find('.main_bg_wide').attr("data-count");

      $(_this).closest('.panel-body').find('.mainpreview').attr("data-page", page_number);
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-type", '');
      $(_this).closest('.panel-body').find('.mainpreview').attr("data-background", '');

      var class_name = 'mainpreview_wide_' + page_number;
      $(_this).closest('.panel-body').find('.mainpreview').addClass(class_name);
      $('.' + class_name + '_style').remove();
      $(_this).closest('.panel-body').find('.main_bg_wide').removeAttr("data-bgurl");
      var class_name = 'main_bg_wide_' + page_number;
      // $(this).addClass(class_name);
      $('.' + class_name + '_style').remove();

      $(_this).closest('.panel-body').find('.main_bg_wide').removeAttr("style");

      $(_this).closest('.panel-body').find('.mainpreview').css("background-image", "");
      $(_this).closest('.panel-body').find('#background_wide').val('').selectpicker('refresh');

    });
    $("body").on('change', '[name="category_wide"]', function(e) {
      e.preventDefault();
      var data = {}
      data.category = $(this).val();
      data.event_category = $(this).closest('.order_number_wide').find('[name="event_category_wide"]').val();
      data.apply_type = $(this).closest('.order_number_wide').find('[name="apply_type_wide"]').val();
      var _this = this;
      get_background_by_category_wide(data, _this);


    });
    /****Pending */
    function get_background_by_category_popup_wide(data, _this) {
      $.post(site_url + 'instax_printing/Instax_printing_client/get_background_by_category', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {

          const select = $('#backgroundPopup_wide');
          select.empty();
          const options = response.background_images;
          // Add new options dynamically
          let optionElement_one = $('<option value="" >-- Select Background --</option>');
          select.append(optionElement_one);
          options.forEach(function(option) {
            const optionElement = $('<option value="' + option.id + '" data-url="' + option.background_url + '" data-content="<div class=\'custom-thumbnail\'><img src=\'' + option.thumb_url + '\'> (' +option.id+')-' + option.image_name + '</div>"></option>');
            select.append(optionElement);
          });

          // Refresh the Bootstrap Select plugin to apply styling to the new options
          select.selectpicker('refresh');




        }

      });
    }
    /****End Pending */
    function get_background_by_category_wide(data, _this) {
      $.post(site_url + 'instax_printing/Instax_printing_client/get_background_by_category', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
          $(_this).closest('.order_number_wide').find('#background_wide');
          const select = $(_this).closest('.order_number_wide').find('#background_wide');
          select.empty();
          const options = response.background_images;
          // Add new options dynamically
          let optionElement_one = $('<option value="" >-- Select Background --</option>');
          select.append(optionElement_one);
          options.forEach(function(option) {
            const optionElement = $('<option value="' + option.id + '" data-url="' + option.background_url + '" data-content="<div class=\'custom-thumbnail\'><img src=\'' + option.thumb_url + '\'> (' +option.id+')-' + option.image_name + '</div>"></option>');
            select.append(optionElement);
          });

          // Refresh the Bootstrap Select plugin to apply styling to the new options
          select.selectpicker('refresh');


          if (data.event_category == '') {
            const event_category = $(_this).closest('.order_number_wide').find('#event_category_wide');
            event_category.empty();
            const event_category_options = response.data_event_category;
            // Add new options dynamically
            let optionElement_one_event_category = $('<option value="" >-- Select Event --</option>');
            event_category.append(optionElement_one_event_category);
            event_category_options.forEach(function(option) {
              const optionElementevent_category = $('<option value="' + option.id + '">' + option.name + '(' + option.count + ')</option>');
              event_category.append(optionElementevent_category);
            });

            // Refresh the Bootstrap Select plugin to apply styling to the new options
            event_category.selectpicker('refresh');
          }

        }

      });
    }

    function set_background_page_wide(_this) {
     
      var apply_type = $(_this).closest('.panel-body').find("#apply_type_wide").val();
      if (apply_type != '') {
        var selectedDataUrl = $(_this).closest('.panel-body').find('#background_wide').find(":selected").data("url");
        var selectedDataval = $(_this).closest('.panel-body').find('#background_wide').find(":selected").val();
        var page_number = $(_this).closest('.panel-body').find('.main_bg_wide').attr("data-count");

        $(_this).closest('.panel-body').find('.mainpreview').attr("data-page", page_number);
        $(_this).closest('.panel-body').find('.mainpreview').attr("data-type", apply_type);
        $(_this).closest('.panel-body').find('.mainpreview').attr("data-background", selectedDataval);
        if (apply_type == 'Individual') {
          var class_name = 'mainpreview_wide_' + page_number;
          $(_this).closest('.panel-body').find('.mainpreview').addClass(class_name);
          $('.' + class_name + '_style').remove();
          $('<div class="' + class_name + '_style"><style>.' + class_name + '::after { background-image: url(' + selectedDataUrl + '); }</style></div>').appendTo('#style_added_wide');
          $(_this).closest('.panel-body').find('.main_bg_wide').removeAttr("style");

        } else if (apply_type == "Whole") {
          $(_this).closest('.panel-body').find('.main_bg_wide').attr("data-bgurl", selectedDataUrl);
          $(_this).closest('.panel-body').find('.mainpreview').css("background-image", "");
          $(_this).closest('.panel-body').find('.main_bg_wide').css("background-image", "url(" + selectedDataUrl + ")");
          $(_this).closest('.panel-body').find('.main_bg_wide').css("background-position", "left top");
          $(_this).closest('.panel-body').find('.main_bg_wide').css("background-size", "100% 100%");

        }

      }
     
    }
    // Attach event listeners
    
    $("#Print_page_wide").on("click", function() {
      $("#qrcode").html('');
      var name = $("#name_wide").val();
      var contact = $("#contact_wide").val();
      var email = $("#email_wide").val();
      var orderNumber = $("#order_number_wide").val();
      var order_from_list = $("#order_from_list_wide").val();
      // var address = $("#address").val();
      var crop_pending = $('.crop_pending_wide').length;
      var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers
      // if (crop_pending > 0) {
      //   alert("Please crop all uploaded images");
      //   return false;
      // }
      // // Perform validation (you can customize the validation conditions as needed)
      // if (name.trim() === "") {
      //   alert("Please enter a valid name.");
      //   return;
      // }
      // if (order_from_list === "") {
      //   alert("Please order from.");
      //   return;
      // }

      // if (contact.trim() === "" || isNaN(contact)) {
      //   alert("Please enter a valid contact number.");
      //   return;
      // }
      // if (!pattern.test(contact)) {
      //   alert("The contact  number you typed is not in the correct format.");
      //   return;
      // }
      // if (email.trim() === "" || !isValidEmail(email)) {
      //   alert("Please enter a valid email address.");
      //   return;
      // }

      if (orderNumber.trim() === "") {
        alert("Please enter a valid order number.");
        return;
      } else {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
          text: orderNumber,
          width: 45,
          height: 45
        });
        var canvas = document.getElementById("qrcode").getElementsByTagName("canvas")[0];
        var base64 = canvas.toDataURL("image/png");

      }

      $(".main_bg_wide").each(function(index) {

        // Get the current value of the style attribute
        var styleValue = $(this).attr("style");

        // Set the new attribute name and value
        $(this).attr("new-style", styleValue);

        // Remove the original style attribute
        $(this).removeAttr("style");
        // Set background color for each div based on the index (or any other logic)
        var page_number = $(this).attr("data-count");
        var selectedDataUrl = $(this).attr("data-bgurl");

        var class_name = 'main_bg_wide_' + page_number;
        $(this).addClass(class_name);
        $('.' + class_name + '_style').remove();
        $('<div class="' + class_name + '_style main_whole_style_wide"><style>.' + class_name + '::after { background-image: url(' + selectedDataUrl + '); }.main_bg_wide::after { content: ""; z-index: 1; position: absolute; background-size: 100% 100%; top: 0; bottom: 0; height: 100%; display: block; width: 100%; }</style></div>').appendTo(this);

      });

      $('.button_foot_wide').hide();
      $('.order_number_wide').hide();
      $('#proposal-wrapper-wide .page_break').hide();
      var divContents = $("#proposal-wrapper-wide").html();
      var order_number = $("#order_number_wide").val();
      var name = $("#name_wide").val();
      var contact = $("#contact_wide").val();


      var qrCodeImage = new Image();
      qrCodeImage.src = base64; // The base64 image data

      qrCodeImage.onload = function() {
        $.ajax({
          url: site_url + "instax_printing/Instax_printing_client/update_print_btn_count",
          type: "POST",
          method: 'POST',
          data: {type:'print_btn_click_count'},
          success: function(response) {
            response = JSON.parse(response);
            if (response.status == 'success') {
              
            } else {
             }
          }
        });
        divContents += '<div class="divFooter"><img src="' + base64 + '">&nbsp; <span>' + order_number + '-' + name + '-' + contact + '-' + email + '</span></div>';

        // divContents += '<div class="divFooter"><span>' + name + '-' + contact + '-' + email + '</span></div>';
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


      }
      $('.button_foot_wide').show();
      $('.order_number_wide').show();
      $('#proposal-wrapper-wide .page_break').show();
      $('.main_whole_style_wide').remove();

      close_popup_wide();
    });
    $("#Email_page_wide").on("click", function() {
      $("#qrcode").html('');
      var name = $("#name_wide").val();
      var contact = $("#contact_wide").val();
      var email = $("#email_wide").val();
      var orderNumber = $("#order_number_wide").val();
      var order_from_list = $("#order_from_list_wide").val();
      // var address = $("#address").val();
      var crop_pending = $('.crop_pending_wide').length;
      var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers
      if (crop_pending > 0) {
        alert("Please crop all uploaded images");
        return false;
      }
      // Perform validation (you can customize the validation conditions as needed)
      if (name.trim() === "") {
        alert("Please enter a valid name.");
        return;
      }
      if (order_from_list === "") {
        alert("Please order from.");
        return;
      }

      if (contact.trim() === "" || isNaN(contact)) {
        alert("Please enter a valid contact number.");
        return;
      }
      if (!pattern.test(contact)) {
        alert("The contact  number you typed is not in the correct format.");
        return;
      }
      if (email.trim() === "" || !isValidEmail(email)) {
        alert("Please enter a valid email address.");
        return;
      }

      if (orderNumber.trim() === "") {
        alert("Please enter a valid order number.");
        return;
      } else {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
          text: orderNumber,
          width: 45,
          height: 45
        });
        var canvas = document.getElementById("qrcode").getElementsByTagName("canvas")[0];
        var base64 = canvas.toDataURL("image/png");

      }
      $('.loading').show();
      // if (address.trim() === "") {
      //     alert("Please enter a valid address.");
      //     return;
      // }
      $(".main_bg_wide").each(function(index) {

        // Get the current value of the style attribute
        var styleValue = $(this).attr("style");

        // Set the new attribute name and value
        $(this).attr("new-style", styleValue);

        // Remove the original style attribute
        $(this).removeAttr("style");
        // Set background color for each div based on the index (or any other logic)
        var page_number = $(this).attr("data-count");
        var selectedDataUrl = $(this).attr("data-bgurl");

        var class_name = 'main_bg_wide_' + page_number;
        $(this).addClass(class_name);
        $('.' + class_name + '_style').remove();
        $('<div class="' + class_name + '_style main_whole_style_wide"><style>.' + class_name + '::after { background-image: url(' + selectedDataUrl + '); }.main_bg_wide::after { content: ""; z-index: 1; position: absolute; background-size: 100% 100%; top: 0; bottom: 0; height: 100%; display: block; width: 100%; }</style></div>').appendTo(this);

      });

      $('.button_foot_wide').hide();
      $('.order_number_wide').hide();
      $('#proposal-wrapper-wide .page_break').hide();
      var divContents = $("#proposal-wrapper-wide").html();
      var order_number = $("#order_number_wide").val();
      var name = $("#name_wide").val();
      var contact = $("#contact_wide").val();


      var qrCodeImage = new Image();
      qrCodeImage.src = base64; // The base64 image data

      qrCodeImage.onload = function() {
        // console.log(qrCodeImage.src);
        // var qrCodeImage = document.getElementById("qrcode").querySelector("img").src;
        // divContents += '<p style="text-align:center;margin-top: 5pc;font-size:18px;">'+order_number+'</p>';
        // var head = '<div class="divFooter">' + order_number + '-'+name+'-'+contact+'-'+email+'-'+address+'</div>';
        divContents += '<div class="divFooter"><img src="' + base64 + '">&nbsp; <span>' + order_number + '-' + name + '-' + contact + '-' + email + '</span></div>';
        var tempDiv = $("<div>").html(divContents);
        tempDiv.find('.button_foot_wide').remove();
        tempDiv.find('.order_number_wide').remove();
        tempDiv.find('.page_break').remove();
        var modifiedDivContents = tempDiv.html();
       
        // divContents += '<div class="divFooter"><span>' + name + '-' + contact + '-' + email + '</span></div>';
        // var printWindow = window.open('', '', 'width=' + screen.width + ',height=' + screen.height);
        var printWindow = window;
        // printWindow.document.write('<style>@page { size: landscape; } @media print { .row  {height:90%;  }}</style>');
        printWindow.document.write('<html><head><title>Print Page</title>');
        // $('<style media="print"> body {} </style>').appendTo('head');
        printWindow.document.write('<link rel="stylesheet" type="text/css" href="https://egateinc.com/modules/instax_printing/css/style.css"></head><body><div class="loading">Loading&#8230;</div>');
        // printWindow.document.write(head);
        printWindow.document.write(modifiedDivContents);
        printWindow.document.write('</body></html>');

        kendo.drawing.drawDOM(printWindow.document.body, {
            forcePageBreak: ".break_page"
          })
          .then(function(group) {
            // Render the result as a PDF file

            return kendo.drawing.exportPDF(group, {
              paperSize: 'auto',
              landscape: true,
              template: $(".divFooter").html(),
              margin: {
                left: "1cm",
                top: "0cm",
                right: "1cm",
                bottom: "0cm"
              }
            });
          })
          .done(function(data) {
            // Save the PDF file
            
            $('.loading').show();
            

            // Remove the prefix
            // var base64Data = data.replace(/^data:application\/pdf;base64,/, '');

            // var blob = new Blob([base64Data], { type: "application/pdf" });
            // console.log(blob);
            var formData = new FormData();
            formData.append('file', data); // 'output.pdf' is the desired file name
            // formData.append('file', blob, 'output.pdf');
            formData.append('name', name); // 'output.pdf' is the desired file name
            formData.append('contact', contact); // 'output.pdf' is the desired file name
            formData.append('email', email); // 'output.pdf' is the desired file name
            formData.append('orderNumber', orderNumber); // 'output.pdf' is the desired file name

            $.ajax({
              url: site_url + "instax_printing/Instax_printing_client/send_email",
              type: "POST",
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                response = JSON.parse(response);
                if (response.status == 'success') {
                  alert_float('success', response.message);
                  printWindow.document.close();
                    window.location.href = site_url + 'instax_printing/Instax_printing_client';
                  
                } else {
                  alert_float('error', response.message);
                }
              },
              error: function(error) {
                console.error('Error:', error);
              }
            });

          });

        

        $('.loading').hide();

      }
      $('.button_foot_wide').show();
      $('.order_number_wide').show();
      $('#proposal-wrapper-wide  .page_break').show();
      $('.main_whole_style_wide').remove();

      close_popup_wide();
    });

    function close_popup_wide() {
      $(".sample_image").remove();

      $('meta[name="viewport"]').attr('content', 'width=device-width, initial-scale=1.0');

      $('.button_foot_show_wide').hide();
      $('.main_whole_style_wide').remove();

      var targetElement = $(".main_bg_wide");

      // Get the current value of the style attribute
      var styleValue = targetElement.attr("new-style");

      // Set the new attribute name and value
      targetElement.attr("style", styleValue);

      // Remove the original style attribute
      targetElement.removeAttr("new-style");
    }
   
    $(document).ready(function() {

      // window.jsPDF = window.jspdf.jsPDF;
      $("body").on("click", "#send_page_wide", function(e) {
        e.preventDefault();
        // var imageSrcList = getAllImageSrc();
        // return false;
        // Get input field values
        var name = $("#name_wide").val();
        var contact = $("#contact_wide").val();
        var email = $("#email_wide").val();
        var orderNumber = $("#order_number_wide").val();
        var crop_pending = $('.crop_pending_wide').length;
        var order_from_list = $("#order_from_list_wide").val();
        var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers
        if (crop_pending > 0) {
          alert("Please crop all uploaded images");
          return false;
        }
        // var address = $("#address").val();


        // Perform validation (you can customize the validation conditions as needed)
        if (name.trim() === "") {
          alert("Please enter a valid name.");
          return;
        }
        if (order_from_list === "") {
          alert("Please order from.");
          return;
        }
        if (contact.trim() === "" || isNaN(contact)) {
          alert("Please enter a valid contact number.");
          return;
        }
        if (!pattern.test(contact)) {
          alert("The contact  number you typed is not in the correct format.");
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


        var imageSrcList = getAllImageSrc_wide();
        sendImageSrcToServer_wide(imageSrcList);



      });
      $(".page_Preview_wide").on("click", function() {
        var crop_pending = $('.crop_pending_wide').length;
        var pattern = /^09\d{9}$/; // Regular expression pattern for Philippine mobile numbers
        if (crop_pending > 0) {
          alert("Please crop all uploaded images");
          return false;
        }
        $('meta[name="viewport"]').attr('content', 'width=1366');

        var class_name = 'main_bg_sample';
        var imageUrl = "https://egateinc.com/modules/instax_printing/images/sample.png";
        var imgTag = $("<img>").attr({
          "src": imageUrl,
          "class": "sample_image"
        });

        $(".main_bg_wide").append(imgTag);
        // $('.main_bg').addClass(class_name);
        // $('.' + class_name + '_style').remove();
        // $('<div class="' + class_name + '_style"><style>.' + class_name + '::after { background-image: url(https://egateinc.com/modules/instax_printing/images/sample.png);opacity:0.1; }</style></div>').appendTo('#style_added');
        $(".main_bg_wide").each(function(index) {
          // Set background color for each div based on the index (or any other logic)



          // Get the current value of the style attribute
          var styleValue = $(this).attr("style");

          // Set the new attribute name and value
          $(this).attr("new-style", styleValue);

          // Remove the original style attribute
          $(this).removeAttr("style");
          var page_number = $(this).attr("data-count");
          var selectedDataUrl = $(this).attr("data-bgurl");
          if (selectedDataUrl != undefined) {

            var class_name = 'main_bg_wide_' + page_number;
            $(this).addClass(class_name);
            $('.' + class_name + '_style').remove();
            $('<div class="' + class_name + '_style main_whole_style_wide"><style>.' + class_name + '::after { background-image: url(' + selectedDataUrl + '); }.main_bg_wide::after { content: ""; z-index: 1; position: absolute; background-size: 99% 100%; background-repeat: no-repeat; background-position: center top;top: 0px; bottom: 0; height: 100%; display: block; width: 104%;left: -20px; }</style></div>').appendTo(this);
          }



        });

        $('#proposal-wrapper-wide .imgUp').css('padding', '0');
        // $('#proposal-wrapper-wide .imgUp').css('width', '19%');
        $('.button_foot_wide').hide();
        $('.order_number_wide').hide();
        $('#proposal-wrapper-wide  .page_break').hide();
        $('.button_foot_show_wide').show();
      });
      $("#close_page_Preview_wide").on("click", function() {
        $(".sample_image").remove();
        // var class_name = 'main_bg_sample';
        //   $('.main_bg').addClass(class_name);
        //   $('.' + class_name + '_style').remove();
        $('meta[name="viewport"]').attr('content', 'width=device-width, initial-scale=1.0');
        $('#proposal-wrapper-wide .imgUp').removeAttr('style');
        $('.button_foot_wide').show();
        $('.order_number_wide').show();
        $('#proposal-wrapper-wide .page_break').show();
        $('.button_foot_show_wide').hide();
        $('.main_whole_style_wide').remove();

        var targetElement = $(".main_bg_wide");

        // Get the current value of the style attribute
        var styleValue = targetElement.attr("new-style");

        // Set the new attribute name and value
        targetElement.attr("style", styleValue);

        // Remove the original style attribute
        targetElement.removeAttr("new-style");

      });

      function getAllImageSrc_wide() {
        var imageSrcList = [];

        // $('.imagePreview').each(function() {
        //   imageSrcList.push($(this).attr('src'));
        // });

        $('#proposal-wrapper-wide .imagePreview').each(function() {
          var imageSrc = $(this).attr('src');
          var textHTML = $(this).closest('.imgUp').find('.text_p').prop('outerHTML'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          var page = $(this).closest('.imgUp').find('.mainpreview').attr('data-page'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          var type = $(this).closest('.imgUp').find('.mainpreview').attr('data-type'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          var background = $(this).closest('.imgUp').find('.mainpreview').attr('data-background'); // Get the 'text_p' element within the same parent containerconsole.log(textHTML);
          // Create an object with the image source and the full HTML of the 'text_p' element
          var elementWithText = {
            imageSrc: imageSrc,
            textHTML: textHTML,
            page: page,
            type: type,
            background: background
          };

          imageSrcList.push(elementWithText);
        });
        // console.log(imageSrcList);return false;
        return imageSrcList;
      }


      // AJAX function to send image src values to PHP
      function sendImageSrcToServer_wide(imageSrcList) {

        var name = $("#name_wide").val();
        var contact = $("#contact_wide").val();
        var email = $("#email_wide").val();
        var orderNumber = $("#order_number_wide").val();
        var order_from_list = $("#order_from_list_wide").val();
        // var address = $("#address").val();
        var background = $("#background_wide").val();
        var apply_type = $("#apply_type_wide").val();
        var order_image_preview = $("#order_image_preview_wide").attr('src');
        var shippinng_image_preview = $("#shippinng_image_preview_wide").attr('src');
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
            order_from: order_from_list,
            background: background,
            apply_type: apply_type,
            order_image_preview: order_image_preview,
            shippinng_image_preview: shippinng_image_preview,
            frame_type: 'wide'
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
      set_width_pop_wide();

      var cropper; // Cropper.js instance
      $(document).on("change", ".uploadFile_wide", function() {
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
            uploadFile.closest(".imgUp").find('.crop-btn_wide').attr('data-src', this.result);
            uploadFile.closest(".imgUp").find('.crop-btn_wide').addClass('crop_pending_wide');
          }
        }

      });
      $(document).on("click", ".col-sm-5 .crop-btn_wide", function() {
        var imageSrc = $(this).closest(".imgUp").find('.imagePreview').attr('src');
        if (imageSrc.startsWith("http://") || imageSrc.startsWith("https://")) {
          alert_float('danger', 'Upload Image First');
          return false;
        }

        $(this).parent().parent().addClass('croping_div_wide');
        var src = $(this).attr('data-src');
        $('#imagePreview_wide').attr('src', src);
        $('#imagePopup_wide').dialog('open');
        // (".ui-dialog-buttonpane .ui-dialog-buttonset").css('width', '100%');
        $(".ui-dialog-buttonpane .crop_note_wide").remove();
        $(".ui-dialog-buttonpane").append('<div class="crop_note_wide"><p style="font-size: 22px;text-align: center;margin: 0;color: red;">Adjust the crop windows position or size to select the preferred printing area</p></div>');

        initializeCropper_wide();
        // $(this).html('Ready');
      });
      $(document).on("click", ".crop-btn_wide_mini", function() {
        var imageSrc = $(this).closest(".imgUp").find('.imagePreview').attr('src');
        if (imageSrc.startsWith("http://") || imageSrc.startsWith("https://")) {
          alert_float('danger', 'Upload Image First');
          return false;
        }

        $(this).parent().parent().addClass('croping_div_wide_mini');
        var src = $(this).attr('data-src');
        $('#imagePreview_wide_mini').attr('src', src);
        $('#imagePopup_wide_mini').dialog('open');
        // (".ui-dialog-buttonpane .ui-dialog-buttonset").css('width', '100%');
        $(".ui-dialog-buttonpane .crop_note_wide_mini").remove();
        $(".ui-dialog-buttonpane").append('<div class="crop_note_wide_mini"><p style="font-size: 22px;text-align: center;margin: 0;color: red;">Adjust the crop windows position or size to select the preferred printing area</p></div>');

        initializeCropper_wide_mini();
        // $(this).html('Ready');
      });
      $(document).on('click', '.crop-text_wide', function() {
        $(this).parent().parent().addClass('text_div_wide');
        $(".ui-dialog-buttonpane .crop_note_wide").remove();
        $('#text-dialog_wide').dialog('open');
        // initializeCropper();
      });

      $("#text-dialog_wide").dialog({
        autoOpen: false,
        modal: true,
        width: width_pop_text, // Set the desired width of the dialog
        height: height_pop_text,
        open: function() {
          $(this).closest(".ui-dialog")
            .find(".ui-dialog-titlebar-close")
            .removeClass("ui-dialog-titlebar-close")
            .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        },
        buttons: {
          Submit: function() {
            var value = $("#textbox_wide").val();
            var font_type = $("#font-type_wide").val();
            var font_size = $("#font-size_wide").val();
            var color = $("#font-color_wide").val();
            $('.text_div_wide .text_p').html(value);
            $('.text_div_wide .text_p').css('font-family', font_type);
            $('.text_div_wide .text_p').css('font-size', font_size);
            $('.text_div_wide .text_p').css('color', color);
            // Perform any necessary actions with the cropped image data
            $('.text_div_wide').removeClass('text_div_wide');
            // Close the popup

            $(this).dialog("close");
          }
        }
      });

      function isMobile_wide() {
        return $(window).width() <= 768;
      }

      function set_width_pop_wide() {
        if (isMobile_wide()) {
          // Code for mobile devices
          width_pop = '350';
          height_pop = '400';
          width_pop_text = '300';
          height_pop_text = '400';
        } else {
          width_pop = '500';
          height_pop = '600';
        }

      }

      var aspectRatio = 1000 / 630;
      var maxWidth = width_pop; // Maximum width of the popup
      var maxHeight = maxWidth / aspectRatio; // Calculate the height based on the aspect ratio

      var aspectRatiocover = 470 / 600;
      var maxWidthCover = width_pop; // Maximum width of the popup
      var maxHeightCover = maxWidthCover / aspectRatiocover; // Calculate the height based on the aspect ratio
      $(document).on('click', ".btn-cover_wide", function() {
        $(this).closest('.imgUp').addClass('cover_div_wide');
        $('#CoverPopup_wide').dialog('open');
      });
      $(document).on('click', ".btn-cover_wide_mini", function() {
        $(this).closest('.imgUp').addClass('cover_div_wide');
        $('#CoverPopup_wide_mini').dialog('open');
      });
      $("#CoverPopup_wide_mini").dialog({
        autoOpen: false,
        modal: true,
        width: maxWidthCover, // Set the desired width of the dialog
        height: maxHeightCover,
        open: function() {
          $(this).closest(".ui-dialog")
            .find(".ui-dialog-titlebar-close")
            .removeClass("ui-dialog-titlebar-close")
            .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        },
        buttons: {
          Submit: function() {
            var dropdownPopup = $("#backgroundPopup_wide_mini");
            var selectedOptionPopup = dropdownPopup.find(":selected");
            var selectedDataval = dropdownPopup.find(":selected").val();
            var backgroundUrlPopup = selectedOptionPopup.data("url");
            var page_count = $('.cover_div_wide').closest('.main_bg_wide').data('count');
            var div_count = $('.cover_div_wide').data('count');
            $('.cover_div_wide .mainpreview').attr("data-background", selectedDataval);
            var class_name = 'mainpreview_after_wide_' + page_count + '_' + div_count;
            $('.cover_div_wide .mainpreview').addClass(class_name);

            // $('.'+class_name).html('');
            $('.' + class_name + '_style').remove();
            $('<div class="' + class_name + '_style"><style>.' + class_name + '::after { background-image: url(' + backgroundUrlPopup + ') !important; }</style></div>').appendTo('.' + class_name);

            $('.imgUp').removeClass('cover_div_wide');

            $(this).dialog("close");

          }
        }
      });
      $("body").on('change', '#eventbackgroundPopup_wide_mini', function(e) {
        e.preventDefault();
        var data = {}
        data.event_category = $(this).val();
        // data.category = $('#category').val();
        data.apply_type = 'individual';
        var _this = this;
        get_background_by_category_popup_wide_mini(data, _this);
      });
    function get_background_by_category_popup_wide_mini(data, _this) {
      $.post(site_url + 'instax_printing/Instax_printing_client/get_background_by_category', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {

          const select = $('#backgroundPopup_wide_mini');
          select.empty();
          const options = response.background_images;
          // Add new options dynamically
          let optionElement_one = $('<option value="" >-- Select Background --</option>');
          select.append(optionElement_one);
          options.forEach(function(option) {
            const optionElement = $('<option value="' + option.id + '" data-url="' + option.background_url + '" data-content="<div class=\'custom-thumbnail\'><img src=\'' + option.thumb_url + '\'> (' +option.id+')-' + option.image_name + '</div>"></option>');
            select.append(optionElement);
          });

          // Refresh the Bootstrap Select plugin to apply styling to the new options
          select.selectpicker('refresh');




        }

      });
    }
      $("#CoverPopup_wide").dialog({
        autoOpen: false,
        modal: true,
        width: maxWidthCover, // Set the desired width of the dialog
        height: maxHeightCover,
        open: function() {
          $(this).closest(".ui-dialog")
            .find(".ui-dialog-titlebar-close")
            .removeClass("ui-dialog-titlebar-close")
            .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        },
        buttons: {
          Submit: function() {
            var dropdownPopup = $("#backgroundPopup_wide");
            var selectedOptionPopup = dropdownPopup.find(":selected");
            var selectedDataval = dropdownPopup.find(":selected").val();
            var backgroundUrlPopup = selectedOptionPopup.data("url");
            var page_count = $('.cover_div_wide').closest('.main_bg_wide').data('count');
            var div_count = $('.cover_div_wide').data('count');
            $('.cover_div_wide .mainpreview').attr("data-background", selectedDataval);
            var class_name = 'mainpreview_after_wide_' + page_count + '_' + div_count;
            $('.cover_div_wide .mainpreview').addClass(class_name);

            // $('.'+class_name).html('');
            $('.' + class_name + '_style').remove();
            $('<div class="' + class_name + '_style"><style>.' + class_name + '::after { background-image: url(' + backgroundUrlPopup + ') !important; }</style></div>').appendTo('.' + class_name);

            $('.imgUp').removeClass('cover_div_wide');

            $(this).dialog("close");

          }
        }
      });
      $('#imagePopup_wide').dialog({
        autoOpen: false,
        modal: true,
        width: maxWidth,
        height: maxHeight,
        open: function() {
          $(this).closest(".ui-dialog")
            .find(".ui-dialog-titlebar-close")
            .removeClass("ui-dialog-titlebar-close")
            .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        },
        buttons: [{
            text: "Rotate Image",
            id: "rotateButton_wide",
            class: 'btn btn-info',
            click: function() {
              rotateImage_wide();
            }
          },
          {
            text: "+",
            class: 'btn btn-info',
            id: "zoomInButton_wide",
            click: function() {
              zoomIn_wide();
            }
          },

          {
            text: "-",
            class: 'btn btn-info',
            id: "zoomOutButton_wide",
            click: function() {
              zoomOut_wide();
            }
          },
          {
            text: '↑',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide('top');
            }
          },
          {
            text: '↓',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide('bottom');
            }
          },
          {
            text: '←',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide('left');
            }
          },
          {
            text: '→',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide('right');
            }
          },
          {
            text: "Crop",
            class: 'btn btn-success',
            click: function() {
              var croppedCanvas = cropper.getCroppedCanvas({
                width: 1000, // Set the desired width of the cropped image
                height: 630 // Set the desired height of the cropped image
              });

              // Retrieve the cropped image data
              var croppedImageDataURL = croppedCanvas.toDataURL();
              
              $('.croping_div_wide .imagePreview').attr('src', croppedImageDataURL);

              $('.croping_div_wide .crop-btn_wide').html('Ready');
              $('.croping_div_wide .crop-btn_wide').css('background', 'yellow');
              $('.croping_div_wide .crop-btn_wide').css('border-color', 'yellow');
              $('.croping_div_wide .crop-btn_wide').css('color', '#000');
              $('.croping_div_wide .crop-btn_wide').removeClass('crop_pending_wide');
              // Perform any necessary actions with the cropped image data
              $('.croping_div_wide').removeClass('croping_div_wide');
              // Close the popup
              $(this).dialog('close');
            }
          }
        ]
      });
      $('#imagePopup_wide_mini').dialog({
        autoOpen: false,
        modal: true,
        width: maxWidth,
        height: maxHeight,
        open: function() {
          $(this).closest(".ui-dialog")
            .find(".ui-dialog-titlebar-close")
            .removeClass("ui-dialog-titlebar-close")
            .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        },
        buttons: [{
            text: "Rotate Image",
            id: "rotateButton_wide_mini",
            class: 'btn btn-info',
            click: function() {
              rotateImage_wide_mini();
            }
          },
          {
            text: "+",
            class: 'btn btn-info',
            id: "zoomInButton_wide_mini",
            click: function() {
              zoomIn_wide_mini();
            }
          },

          {
            text: "-",
            class: 'btn btn-info',
            id: "zoomOutButton_wide_mini",
            click: function() {
              zoomOut_wide_mini();
            }
          },
          {
            text: '↑',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide_mini('top');
            }
          },
          {
            text: '↓',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide_mini('bottom');
            }
          },
          {
            text: '←',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide_mini('left');
            }
          },
          {
            text: '→',
            class: 'btn btn-info',
            click: function() {
              moveImage_wide_mini('right');
            }
          },
          {
            text: "Crop",
            class: 'btn btn-success',
            click: function() {
              var croppedCanvas = cropper.getCroppedCanvas({
                width: 470, // Set the desired width of the cropped image
                height: 630 // Set the desired height of the cropped image
              });

              // Retrieve the cropped image data
              var croppedImageDataURL = croppedCanvas.toDataURL();
              
              $('.croping_div_wide_mini .imagePreview').attr('src', croppedImageDataURL);

              $('.croping_div_wide_mini .crop-btn_wide_mini').html('Ready');
              $('.croping_div_wide_mini .crop-btn_wide_mini').css('background', 'yellow');
              $('.croping_div_wide_mini .crop-btn_wide_mini').css('border-color', 'yellow');
              $('.croping_div_wide_mini .crop-btn_wide_mini').css('color', '#000');
              $('.croping_div_wide_mini .crop-btn_wide_mini').removeClass('crop_pending_wide');
              // Perform any necessary actions with the cropped image data
              $('.croping_div_wide_mini').removeClass('croping_div_wide_mini');
              // Close the popup
              $(this).dialog('close');
            }
          }
        ]
      });
      var cropper = null;

      function initializeCropper_wide() {
        // Destroy the previous cropper instance (if exists)
        if (cropper) {
          cropper.destroy();
        }

        // Initialize Cropper.js on the image
        var image = document.getElementById('imagePreview_wide');
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

          aspectRatio: 1000 / 630,
          dragMode: 'move',
          ready: function() {
            cropper.setCropBoxData({
              width: 1000,
              height: 630,
            });
          },
        });
      }
      function initializeCropper_wide_mini() {
        // Destroy the previous cropper instance (if exists)
        if (cropper) {
          cropper.destroy();
        }

        // Initialize Cropper.js on the image
        var image = document.getElementById('imagePreview_wide_mini');
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
          dragMode: 'move',
          ready: function() {
            cropper.setCropBoxData({
              width: 470,
              height: 630,
            });
          },
        });
      }
      // Function to rotate the image
      function rotateImage_wide() {
        cropper.rotate(90); // Rotate the image by 90 degrees clockwise
      }

      function zoomIn_wide() {
        cropper.zoom(0.1);
      }

      function zoomOut_wide() {
        cropper.zoom(-0.1);
      }

      function moveImage_wide(direction) {
        var imageData = cropper.getImageData();
        var containerData = cropper.getContainerData();
        var canvasData = cropper.getCanvasData();

        var moveX = 0;
        var moveY = 0;

        // Calculate the amount to move the image based on the direction
        switch (direction) {
          case 'left':
            moveX = -10;
            break;
          case 'right':
            moveX = 10;
            break;
          case 'top':
            moveY = -10;
            break;
          case 'bottom':
            moveY = 10;
            break;
        }

        var newX = imageData.left + moveX;
        var newY = imageData.top + moveY;

        // Check if the new position is within the container boundaries
        if (newX >= 0 && newX + imageData.width <= containerData.width) {
          imageData.left = newX;
        }

        if (newY >= 0 && newY + imageData.height <= containerData.height) {
          imageData.top = newY;
        }

        // Apply the new position
        cropper.setCanvasData({
          left: canvasData.left + moveX,
          top: canvasData.top + moveY,
        });

        // Update the image position
        cropper.setImageData(imageData);
      }

    });
   
  });
</script>