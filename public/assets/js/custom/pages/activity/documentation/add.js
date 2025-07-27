'use strict';

const KTAddActivityDocumentation = (() => {
    const addModal = $('#kt_modal_add_activity_documentation');
    const addForm = addModal.find('#kt_modal_add_activity_documentation_form');
    const submitButton = addForm.find('button[type="submit"]');

    let fv;
    let editor;
    let myDropzone;

    const initCKEditor = () => {
        return ClassicEditor
            .create(document.querySelector('#kt_add_activity_documentation_ckeditor_classic'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    };

    const initSingleDatePicker = () => {
        $("#kt_add_activity_documentation_daterangepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: moment().year(),
            maxYear: parseInt(moment().add(10, 'years').format("YYYY"), 10),
        });
    };

    const initDropzone = () => {
        myDropzone = new Dropzone("#kt_add_activity_documentation_dropzone_images", {
            url: "/dummy-upload",
            paramName: "images",
            maxFiles: 25,
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            previewsContainer: "#kt_add_activity_documentation_dropzone_images_preview",
            init: function () {
                this.on("addedfile", function (file) {
                    const removeButton = file.previewElement.querySelector(".dz-remove");
                    removeButton.addEventListener("click", (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.removeFile(file);
                    });
                });
            }
        });
    };

    const validate = () => {
        fv = FormValidation.formValidation(
            addForm[0],
            {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'title wajib diisi'
                            },
                            stringLength: {
                                min: 2,
                                message: 'title minimal 2 karakter'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Deskripsi wajib diisi'
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
                    const formData = new FormData();
                    formData.append('title', addForm.find('[name="title"]').val());
                    formData.append('activity_category_id', addForm.find('[name="activity_category_id"]').val());
                    formData.append('date', addForm.find('[name="date"]').val());
                    formData.append('description', editor.getData());

                    if (myDropzone.files.length > 0) {
                        myDropzone.files.forEach((file, index) => {
                            formData.append(`images[${index}]`, file);
                        });
                    }

                    submitButton.prop('disabled', true);
                    submitButton.html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Menyimpan...');

                    $.ajax({
                        url: '/activity/documentations',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            addModal.modal('hide');
                            addForm[0].reset();
                            editor.setData('');
                            myDropzone.removeAllFiles(true);
                            fv.resetForm(true);

                            if (typeof window.datatable !== 'undefined') {
                                window.datatable.ajax.reload();
                            }

                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        },
                        complete: function () {
                            submitButton.prop('disabled', false);
                            submitButton.html('Simpan');
                        }
                    });
                } else {
                    Swal.fire({
                        text: 'Silakan lengkapi form dengan benar.',
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

    const closeModal = () => {
        addModal.on('hidden.bs.modal', function () {
            addForm[0].reset();
            if (editor) editor.setData('');
            if (myDropzone) myDropzone.removeAllFiles(true);
            fv.resetForm(true);
        });
    };

    const init = () => {
        initCKEditor();
        initSingleDatePicker();
        initDropzone();
        validate();
        submit();
        closeModal();
    };

    return { init };
})();

KTUtil.onDOMContentLoaded(() => {
    KTAddActivityDocumentation.init();
});