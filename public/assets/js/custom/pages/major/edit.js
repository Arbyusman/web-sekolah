'use strict';

const KTUpdateMajor = (() => {
    const editModal = $('#kt_modal_edit_major');
    const editForm = editModal.find('#kt_modal_edit_major_form');
    const editIdInput = editForm.find('#id');
    const editNameInput = editForm.find('#name');
    const submitButton = editForm.find('button[type="submit"]');
    let fv;

    const validate = () => {
        fv = FormValidation.formValidation(
            editForm[0],
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Nama wajib diisi'
                            },
                            stringLength: {
                                min: 2,
                                message: 'Nama minimal 2 karakter'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    }),
                }
            }
        );
    };

    const show = () => {
        $(document).on('click', '.edit-major-btn', function () {
            const id = $(this).data('id');
            $.get(`/majors/${id}`, function (data) {
                fv.resetForm(true);
                editIdInput.val(data.id);
                editNameInput.val(data.name);
                editModal.modal('show');
            });
        });
    };

    const submit = () => {
        editForm.on('submit', function (e) {
            e.preventDefault();

            fv.validate().then(function (status) {
                if (status === 'Valid') {
                    const id = editIdInput.val();
                    const name = editNameInput.val().trim();

                    submitButton.prop('disabled', false);

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
                            editForm[0].reset();
                            fv.resetForm(true);
                            window.datatable.ajax.reload();
                            Swal.fire('Updated!', response.message || 'Jurusan berhasil diperbarui.', 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui.', 'error');
                        },
                        complete: function () {
                            submitButton.prop('disabled', false);
                        }
                    });
                } else {
                    Swal.fire('Error!', 'Silakan lengkapi form dengan benar.', 'error');
                }
            });
        });
    };

    const closeModal = () => {
        editModal.on('hidden.bs.modal', function () {
            editForm[0].reset();
            fv.resetForm(true);
        });
    };

    const init = () => {
        validate();
        show();
        submit();
        closeModal();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTUpdateMajor.init();
});
