(function () {
  "use strict";

  appValidateForm($("#disciplinary form"), {
    date: "required",
    discipline_category: "required",
    reason: "required",
    action_taken: "required",
    remark: "required",
  });

  var ContractsServerParams = {
    staff_id: "[name='staff_id']",
  };

  var table_disciplinary_history = $(".table-table_disciplinary_history");
  initDataTable(
    table_disciplinary_history,
    admin_url + "hr_profile/table_disciplinary_history",
    [0],
    [0],
    ContractsServerParams,
    [0, "desc"]
  );

  //hide first column
  var hidden_columns = [1];
  $(".table-table_disciplinary_history")
    .DataTable()
    .columns(hidden_columns)
    .visible(false, false);
})(jQuery);

function new_disciplinary_history() {
  "use strict";
  $("#disciplinary").modal("show");
  $("#dependent_person_id").html("");

  var today = new Date();
  var year = today.getFullYear();
  var month = ("0" + (today.getMonth() + 1)).slice(-2);
  var day = ("0" + today.getDate()).slice(-2);
  var formattedDate = year + "-" + month + "-" + day;

  $("#date").val(formattedDate);

  $(".edit-title").addClass("hide");
  $(".add-title").removeClass("hide");
}

function handleDisciplineCategoryChange() {
  var selected = $("#discipline_category").val();
  if (selected) {
    $.ajax({
      type: "POST",
      url: admin_url + "hr_profile/get_disciplinary_action_subject",
      data: { selected_action_category: selected },
      success: function (response) {
        try {
          var data = JSON.parse(response.trim());
          var select = $("#reason");
          select.empty();
          $.each(data, function (index, item) {
            var option = $("<option>", {
              value: item.id,
              text: item.discipline_reason_name,
            });
            select.append(option);
          });
          select.selectpicker("refresh");
          select.trigger("change");
        } catch (e) {
          console.error("Error parsing JSON:", e);
        }
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
  }
}

// Function to handle change event for reason
function handleReasonChange() {
  var reason = $("#reason").val();
  if (reason) {
    $.ajax({
      type: "POST",
      url: admin_url + "hr_profile/get_disciplinary_remark",
      data: { selected_action_remark: reason },
      success: function (response) {
        try {
          var data = JSON.parse(response.trim());
          tinyMCE.get("content").setContent("");
          tinyMCE.get("content_template").setContent("");
          $("#remark").val(data.discipline_reason_remarks);
          tinyMCE.get("content").setContent(data.company_rules);
          tinyMCE.get("content_template").setContent(data.content_template);
        } catch (e) {
          console.error("Error parsing JSON:", e);
        }
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
  }
}

// Bind change event handlers
$(document).ready(function () {
  $("#discipline_category").on("change", handleDisciplineCategoryChange);
  $("#reason").on("change", handleReasonChange);

  if ($("#discipline_category").val()) {
    $("#discipline_category").trigger("change");
  }
});

// Edit modal function
function edit_dependent_person(invoker, id) {
  "use strict";

  requestGetJSON("hr_profile/get_disciplinary_templetes/" + id).done(function (
    response
  ) {
    console.log("response", response);

    $("#dependent_person_id").append(hidden_input("id", id));
    $("input[name='date']").val(response.disciplinary_templete.date);

    // Set the value and trigger change for discipline_category
    $("select[name='discipline_category']").selectpicker("destroy");
    $("select[name='discipline_category']").val(
      response.disciplinary_templete.discipline_category
    );
    $("select[name='discipline_category']")
      .selectpicker("refresh")
      .trigger("change");

    // Set the value and trigger change for action_taken
    $("select[name='action_taken']").selectpicker("destroy");
    $("select[name='action_taken']").val(
      response.disciplinary_templete.action_taken
    );
    $("select[name='action_taken']").selectpicker("refresh");

    $("input[name='penalty_point']").val(response.disciplinary_templete.penalty_point);

    // Set the value and trigger change for reason
    $("select[name='reason']").selectpicker("destroy");
    $("select[name='reason']").val(response.disciplinary_templete.reason);
    $("select[name='reason']").selectpicker("refresh").trigger("change");

    $("#file_data").html(
      '<a href="' +
        response.disciplinary_templete.file_url +
        '" target="_blank">' +
        response.disciplinary_templete.file +
        "</a>"
    );

    $("input[name='reason']").val(response.disciplinary_templete.reason);
    $("textarea[name='remark']").val(response.disciplinary_templete.remark);

    tinyMCE.get("content").setContent(response.disciplinary_templete.content);
    tinyMCE
      .get("content_template")
      .setContent(response.disciplinary_templete.content_template);
    $("#image_download_a").attr("href", response.file_path);
    $("#image_download_a").html(response.disciplinary_templete.file);
  });

  $("#disciplinary").modal("show");
  $(".add-title").addClass("hide");
  $(".edit-title").removeClass("hide");
}

$("#disciplinary").on("hidden.bs.modal", function () {
  $("#discipline_category").val("").selectpicker("refresh");
  $("#reason").val("").selectpicker("refresh");
  $("#action_taken").val("").selectpicker("refresh");
  tinyMCE.get("content").setContent("");
  tinyMCE.get("content_template").setContent("");
  $("#remark").val("");
  $("#content").val("");
  $("#image_download_a").html("");
});

$('#action_taken').change(function () {
  var selected = $(this).val();
  $.ajax({
    type: "POST",
    url: admin_url + "hr_profile/get_penalty_point",
    data: { selected: selected },
    success: function (response) {
        var point = JSON.parse(response.trim());
        $("#penalty_point").val(point);
    },
  });
});
