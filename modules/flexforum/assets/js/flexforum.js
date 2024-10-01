"use strict";

if (typeof init_editor !== "function") {
  // Function to init the tinymce editor
  function init_editor(selector, settings) {
    selector = typeof selector == "undefined" ? ".tinymce" : selector;
    var _editor_selector_check = $(selector);

    if (_editor_selector_check.length === 0) {
      return;
    }

    $.each(_editor_selector_check, function () {
      if ($(this).hasClass("tinymce-manual")) {
        $(this).removeClass("tinymce");
      }
    });

    // Original settings
    var _settings = {
      branding: false,
      selector: selector,
      browser_spellcheck: true,
      height: 400,
      theme: "modern",
      skin: "perfex",
      language: app.tinymce_lang,
      relative_urls: false,
      inline_styles: true,
      verify_html: false,
      entity_encoding: "raw",
      cleanup: false,
      autoresize_bottom_margin: 25,
      valid_elements: "+*[*]",
      valid_children: "+body[style], +style[type]",
      apply_source_formatting: false,
      remove_script_host: false,
      removed_menuitems: "newdocument restoredraft",
      forced_root_block: "p",
      autosave_restore_when_empty: false,
      fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
      setup: function (ed) {
        // Default fontsize is 12
        ed.on("init", function () {
          this.getDoc().body.style.fontSize = "12pt";
        });
      },
      table_default_styles: {
        // Default all tables width 100%
        width: "100%",
      },
      plugins: [
        "advlist autoresize autosave lists link image print hr codesample",
        "visualblocks code fullscreen",
        "media save table contextmenu",
        "paste textcolor colorpicker",
      ],
      toolbar1:
        "fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | image link | bullist numlist | restoredraft",
      // file_browser_callback: elFinderBrowser,
      contextmenu:
        "link image inserttable | cell row column deletetable | paste copy",
    };

    // Add the rtl to the settings if is true
    isRTL == "true" ? (_settings.directionality = "rtl") : "";
    isRTL == "true" ? (_settings.plugins[0] += " directionality") : "";

    // Possible settings passed to be overwrited or added
    if (typeof settings != "undefined") {
      for (var key in settings) {
        if (key != "append_plugins") {
          _settings[key] = settings[key];
        } else {
          _settings["plugins"].push(settings[key]);
        }
      }
    }

    // Init the editor
    var editor = tinymce.init(_settings);
    $(document).trigger("app.editor.initialized");

    return editor;
  }
}

function flexforum_editor_config() {
  return {
    forced_root_block: "p",
    height: !is_mobile() ? 100 : 50,
    menubar: false,
    autoresize_bottom_margin: 15,
    plugins: [
      "lists link print hr codesample",
      "visualblocks code fullscreen",
      "media save contextmenu",
      "paste textcolor colorpicker table advlist codesample autosave" +
        (!is_mobile() ? " autoresize " : " "),
    ],
    toolbar:
      "insert formatselect bold forecolor backcolor" +
      (is_mobile() ? " | " : " ") +
      "alignleft aligncenter alignright bullist numlist | restoredraft",
    // toolbar1: "",
    toolbar1:
      "fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | link | bullist numlist | restoredraft",
  };
}

$(document).ready(function () {
  // Initialize selectpicker
  $(".selectpicker").selectpicker();

  $(document).on("change", "#category", function () {
    $("#childcategory").empty(); // Clear existing options

    if ($(this).val() !== "") {
      var parent_id = $(this).val();

      $.ajax({
        type: "POST",
        url: admin_url + "flexforum/flexforum/get_child_chatagory",
        dataType: "json",
        data: {
          parent_id: parent_id,
        },
        success: function (data) {

          $("#flexforum_topic_form #childcategory").empty();

          $.each(data, function () {
            var options = "<option  data-child-id='"+ this.name +"' value='" + this.id + "'>" + this.name + " (" + this.count + ")</option>";
            $("#flexforum_topic_form #childcategory").append(options);
        });
          datafiltercategories();

          $("#flexforum_topic_form #childcategory").selectpicker("refresh");
        },
      });
    }
  });

  $(document).on("change", "#childcategory", function () {
    var selectedOption = $('#childcategory option:selected');
    var childId = selectedOption.val();
    var childIdAttr = selectedOption.data("child-id");
    var data_table = $("#topic-table").DataTable();
    data_table.search("").columns().search("").draw();
    if (childId) {
        data_table.column(2).search(childIdAttr).draw();
    } else {
        data_table.column(2).search("").draw();
    }
  });

  $(document).on("change", "#model_id", function () {

    if ($(this).val() !== "") {
      var modelId = $(this).val();


      $.ajax({
        type: "POST",
        url: admin_url + "flexforum/flexforum/get_model_details",
        dataType: "json",
        data: {
            modelId: modelId,
        },
        success: function (data) {
          $("#brand_name").empty();
          $("#code").empty();
      
          $("#brand_name").prop("disabled", false);
          $("#code").prop("disabled", false);
      
          $.each(data, function () {
              var brandOptions = "<option value='" + this.id + "'>" + this.brand + "</option>";
              $("#brand_name").append(brandOptions);
      
              var codeOptions = "<option value='" + this.id + "'>" + this.code + "</option>";
              $("#code").append(codeOptions);
          });
      
          $("#brand_name").prop("disabled", true);
          $("#code").prop("disabled", true);
      
          $("#brand_name").selectpicker("refresh");
          $("#code").selectpicker("refresh");
      },
    });
    }
  });

  $(document).on("click", "#child_category_filter", function (e) {
    e.preventDefault();
    var childName = $(this).data("child-name");
    var data_table = $("#topic-table").DataTable();
    data_table.search("").columns().search("").draw();
    data_table.column(2).search(childName).draw();
  });

  $(document).on("click", "#parent_category_filter", function (e) {
    e.preventDefault();
    var parentName = $(this).data("parent-name");
    var data_table = $("#topic-table").DataTable();
    data_table.search("").columns().search("").draw();
    data_table.column(1).search(parentName).draw();
  });
});

function datafiltercategories() {
  var selectedOption = $('#category option:selected');
  var parentId = selectedOption.val();
  var parentIdAttr = selectedOption.data("parent-id");
  var data_table = $("#topic-table").DataTable();
  data_table.search("").columns().search("").draw();
  if (parentId) {
      data_table.column(1).search(parentIdAttr).draw();
  } else {
      data_table.column(1).search("").draw();
  }
}
