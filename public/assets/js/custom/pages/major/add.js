'use strict';

const KTAddMajor = (() => {
    const addModal = $('#kt_modal_add_major');
    const addForm = addModal.find('#add-major-form');

    const AddMajor = () => {
        addForm.on('submit', function(e) {
            e.preventDefault();
            const name = addModal.find('#name').val();

            $.ajax({
                url: '/majors',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ name: name }),
                success: function(response) {
                    addModal.modal('hide');
                    window.datatable.ajax.reload();
                    Swal.fire('Success!', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
            });
        });
    };

    const init = () => {
        AddMajor();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTAddMajor.init();
});
