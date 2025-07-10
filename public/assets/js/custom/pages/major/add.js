'use strict';

const KTAddMajor = (() => {
    const addModal = $('#kt_modal_add_major');
    const addForm = addModal.find('#kt_modal_add_major_form');
    const submitButton = addForm.find('button[type="submit"]');

    let fv;

    const validate = () => {
        fv = FormValidation.formValidation(
            addForm[0],
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

    const submit = () => {
        addForm.on('submit', function (e) {
            e.preventDefault();

            fv.validate().then(function (status) {
                if (status === 'Valid') {
                    const name = addForm.find('[name="name"]').val().trim();
                    submitButton.prop('disabled', true);

                    $.ajax({
                        url: '/majors',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({ name: name }),
                        success: function (response) {
                            addModal.modal('hide');
                            addForm[0].reset();
                            fv.resetForm(true);
                            window.datatable.ajax.reload();
                            Swal.fire('Success!', response.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
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

    const closaModal = () => {
        addModal.on('hidden.bs.modal', function () {
            addForm[0].reset();
            fv.resetForm(true);
        });
    };

    const init = () => {
        validate();
        submit();
        closaModal();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTAddMajor.init();
});
