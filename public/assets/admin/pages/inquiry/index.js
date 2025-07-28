$(document).ready(function () {
    let currentDeleteId = null;

    // Initialize DataTable - Destroy if exists
    if ($.fn.DataTable.isDataTable("#datatable")) {
        $("#datatable").DataTable().destroy();
    }

    $("#datatable").DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100],
        ],
        order: [[0, "desc"]],
        columnDefs: [{ orderable: false, targets: [1] }],
    });

    // Handle entries select change
    $("#entries-select").on("change", function () {
        var table = $("#datatable").DataTable();
        table.page.len(parseInt($(this).val())).draw();
    });

    // Delete inquiry
    $(document).on("click", ".delete-inquiry", function (e) {
        e.preventDefault();
        e.stopPropagation();
        currentDeleteId = $(this).data("id");
    });

    $("#confirm-delete").on("click", function () {
        if (currentDeleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: delete_url,
                method: "GET",
                data: { id: currentDeleteId },
                success: function (response) {
                    if (response.result) {
                        toastr.success("Inquiry deleted successfully");
                        $("#deleteModal").modal("hide");
                        location.reload();
                    } else {
                        toastr.error("Failed to delete inquiry");
                    }
                },
                error: function () {
                    toastr.error("Error deleting inquiry");
                },
            });
        }
    });
});
