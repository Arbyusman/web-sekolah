'use strict';

const KTUpdateMajor = (() => {
    const editModal = $('#kt_modal_edit_major');
    const editForm = editModal.find('#edit-major-form');
    const editIdInput = editForm.find('#id');
    const editNameInput = editForm.find('#name');

    const updateMajor = () => {
        $(document).on('click', '.edit-major-btn', function () {
            const id = $(this).data('id');
            editForm.attr('action', `/majors/${id}`);

            $.get(`/majors/${id}`, function (data) {
                editIdInput.val(data.id);
                editNameInput.val(data.name);
                editModal.modal('show');
            });
        });

        editForm.on('submit', function (e) {
            e.preventDefault();
            const id = editIdInput.val();
            const name = editNameInput.val();

            $.ajax({
                url: `/majors/${id}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: JSON.stringify({ name: name, _method: 'PUT' }),
                success: function (response) {
                    editModal.modal('hide');
                    window.datatable.ajax.reload();
                    Swal.fire('Updated!', 'Jurusan berhasil diperbarui.', 'success');
                },
                error: function () {
                    Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui.', 'error');
                }
            });
        });
    };


    const init = () => {
        updateMajor();
    };
    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTUpdateMajor.init();
});

