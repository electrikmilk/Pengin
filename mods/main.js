function deleteFlag(id) {
  if (confirm("Are you sure?") === true) {
    $.ajax({
      type: "POST",
      url: "/mods/backend.php",
      data: {
        action: "deleteFlag",
        id: id
      },
      success: function (response) {
        if (response === "success") {
          $("#item-" + id).fadeOut();
          setTimeout(function () {
            $("#item-" + id).remove();
          }, 3000);
          newAlert("Report deleted");
        } else {
          newAlert("Error deleting report");
        }
      },
      error: function (data) {
        console.log("error getting privacy status");
      }
    });
  }
}
