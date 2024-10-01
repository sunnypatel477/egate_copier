$(function () {});

// Removes knowledge_base single attachment
function remove_knowledge_base_attachment(link, id) {
  if (confirm_delete()) {
    requestGetJSON("knowledge_base/remove_knowledge_base_attachment/" + id).done(
      function (response) {
        if (response.success === true || response.success == "true") {
          location.reload();
        }
      }
    );
  }
}
