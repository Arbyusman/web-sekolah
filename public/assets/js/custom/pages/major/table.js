"use strict";

const KTMajor = (() => {
    let datatable, table;
    let deleteForm, deleteIdInput, deleteModal;

    const getTableElement = () => document.querySelector("#kt_majors_table");
    const getAjaxUrl = () => document.getElementById("table-url")?.value || '';

    const renderActionButtons = (data) => `
        <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 edit-major-btn"
            data-id="${data}">
            <i class="ki-duotone ki-pencil fs-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </button>
        <button class="btn btn-icon btn-bg-danger btn-active-color-danger btn-sm me-1 delete-major-btn"
            data-id="${data}">
            <i class="ki-duotone ki-trash fs-2">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
            </i>
        </button>
    `;

    const initDataTable = () => {
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
                { data: 'name', name: 'name' },
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

    const deleteMajor = () => {
        $(document).on('click', '.delete-major-btn', function () {
            const id = $(this).data('id');
            deleteForm.attr('action', `/majors/${id}`);
            deleteIdInput.val(id);
            deleteModal.modal('show');
        });

        deleteForm.on('submit', function (e) {
            e.preventDefault();
            const id = deleteIdInput.val();

            $.ajax({
                url: `/majors/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    deleteModal.modal('hide');
                    window.datatable.ajax.reload();
                    Swal.fire('Deleted!', 'Jurusan berhasil dihapus.', 'success');
                },
                error: function () {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menghapus.', 'error');
                }
            });
        });
    };

    const init = () => {
        table = getTableElement();
        if (!table) return;

        deleteForm = $('#delete-major-form');
        deleteIdInput = $('#delete-major-id');
        deleteModal = $('#kt_modal_delete_major');

        initDataTable();
        deleteMajor();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTMajor.init();
});
