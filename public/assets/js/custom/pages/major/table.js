"use strict";

const KTMajor = (() => {
    let datatable;
    let table;

    const getTableElement = () => document.querySelector("#kt_majors_table");

    const getAjaxUrl = () => document.getElementById("table-url")?.value || '';

    const renderActionButtons = (data) => `
        <button class="edit-bank btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1"
            data-bs-toggle="modal"
            data-bs-target="#kt_modal_edit_bank"
            data-id="${data}">
            <i class="ki-duotone ki-pencil fs-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </button>
        <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
            data-kt-banks-table-filter="delete_row"
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

    const init = () => {
        table = getTableElement();

        if (!table) return;

        initDataTable();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTMajor.init();
});
