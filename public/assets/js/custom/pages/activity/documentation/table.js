"use strict";

const KTActivityDocumentation = (() => {
    let datatable, table;
    let deleteForm, deleteIdInput, deleteModal;

    const getTableElement = () => document.querySelector("#kt_activity_documentations_table");
    const getAjaxUrl = () => document.getElementById("table-url")?.value || '';

    const renderActionButtons = (id) => `
        <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 edit-activity-documentation-btn" data-id="${id}">
            <i class="ki-duotone ki-pencil fs-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </button>
        <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-activity-documentation-btn" data-id="${id}">
            <i class="ki-duotone ki-trash fs-2">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
            </i>
        </button>
    `;

    const initDataTable = () => {
        if ($.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().clear().destroy();
        }

        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, "asc"]],
            ajax: {
                url: getAjaxUrl(),
            },
            info: false,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: -1,
                    className: "text-end",
                    render: (data) => renderActionButtons(data),
                },
            ],
        });

        datatable.on("draw", () => {
            KTMenu.createInstances();
        });

        window.datatable = datatable;
    };

    const deleteActivityDocumentation = () => {
        const button = deleteForm.find('button[type="submit"]');
        $(document).on('click', '.delete-activity-documentation-btn', function () {
            const id = $(this).data('id');
            deleteForm.attr('action', `/activity/documentations/${id}`);
            deleteIdInput.val(id);
            deleteModal.modal('show');
        });

        deleteForm.on('submit', function (e) {
            e.preventDefault();
            const id = deleteIdInput.val();

            button.attr('data-kt-indicator', 'on');
            button.prop('disabled', true);

            $.ajax({
                url: `/activity/documentations/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    button.removeAttr('data-kt-indicator');
                    button.prop('disabled', false);
                    deleteModal.modal('hide');
                    Swal.fire({
                        text: response.message || 'Dokumentasi kegiatan berhasil dihapus.',
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    window.datatable.ajax.reload();
                },
                error: function (xhr) {
                    button.removeAttr('data-kt-indicator');
                    button.prop('disabled', false);
                    let errorMessage = 'Terjadi kesalahan saat menghapus.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        text: errorMessage,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    };

    const init = () => {
        table = getTableElement();
        if (!table) return;

        deleteModal = $('#kt_modal_delete_activity_documentation');
        deleteForm = deleteModal.find('#kt_modal_delete_activity_documentation_form');
        deleteIdInput = deleteForm.find('#id');

        initDataTable();
        deleteActivityDocumentation();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTActivityDocumentation.init();
});
