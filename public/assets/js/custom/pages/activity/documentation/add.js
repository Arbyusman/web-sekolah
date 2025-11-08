"use strict";

let KTAddActivityDocumentation = (function () {
    const element = document.getElementById("kt_modal_add_activity_documentation"),
        form = element.querySelector("#kt_modal_add_activity_documentation_form"),
        token = $("meta[name='csrf-token']").attr("content");

    let editor, myDropzone, dateRangePicker;

    const initCKEditor = () => {
        const editorElement = document.querySelector('#kt_add_activity_documentation_ckeditor_classic');
        if (!editorElement) {
            console.error('CKEditor element not found');
            return Promise.resolve(null);
        }

        if (editor) {
            return Promise.resolve(editor);
        }

        return ClassicEditor
            .create(editorElement)
            .then(newEditor => {
                editor = newEditor;
                return editor;
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
                return null;
            });
    };

    const initDateRangePicker = () => {
        const dateInput = $("#kt_add_activity_documentation_daterangepicker");
        if (dateInput.length > 0 && !dateRangePicker) {
            dateRangePicker = dateInput.daterangepicker({
                showDropdowns: true,
                minYear: moment().year() - 5,
                maxYear: parseInt(moment().add(10, 'years').format("YYYY"), 10),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        }
    };

    const initDropzone = () => {
        const dropzoneElement = document.querySelector("#kt_add_activity_documentation_dropzone_images");
        if (!dropzoneElement) {
            return;
        }

        if (dropzoneElement.dropzone) {
            dropzoneElement.dropzone.destroy();
        }

        dropzoneElement.classList.remove('dz-clickable');

        try {
            myDropzone = new Dropzone("#kt_add_activity_documentation_dropzone_images", {
                url: "/dummy-upload",
                paramName: "images",
                maxFiles: 25,
                maxFilesize: 5,
                addRemoveLinks: true,
                acceptedFiles: "image/*",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                previewsContainer: "#kt_add_activity_documentation_dropzone_images_preview",
                init: function () {
                    this.on("addedfile", function (file) {
                        const removeButton = file.previewElement.querySelector(".dz-remove");
                        if (removeButton) {
                            removeButton.addEventListener("click", (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                this.removeFile(file);
                            });
                        }
                    });
                }
            });
        } catch (error) {
            console.error('Dropzone initialization error:', error);
        }
    };

    const resetForm = () => {
        form.reset();
        if (editor) editor.setData('');
        if (myDropzone) myDropzone.removeAllFiles(true);
        $('#kt_add_activity_documentation_daterangepicker').val('');
    };

    let initAddActivityDocumentation = () => {
        window.token = token;

        let validator = FormValidation.formValidation(form, {
            fields: {
                title: {
                    validators: {
                        notEmpty: {
                            message: "Judul wajib diisi"
                        },
                        stringLength: {
                            min: 2,
                            message: "Judul minimal 2 karakter"
                        }
                    }
                },
                activity_category_id: {
                    validators: {
                        notEmpty: {
                            message: "Kategori kegiatan wajib dipilih"
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
            },
        });

        $(form.querySelector('[name="activity_category_id"]')).on('change', function () {
            if (validator) {
                validator.revalidateField('activity_category_id');
            }
        });


        const closeButton = element.querySelector('[data-kt-activity-documentation-modal-action="close"]');
        if (closeButton) {
            closeButton.addEventListener("click", (e) => {
                e.preventDefault();

                Swal.fire({
                    text: "Apakah Anda yakin ingin menutup form?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, tutup!",
                    cancelButtonText: "Tidak, kembali",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then(function (result) {
                    if (result.value) {
                        resetForm();
                        $('#kt_modal_add_activity_documentation').modal('hide');
                    }
                });
            });
        }

        const submitButton = element.querySelector('[data-kt-activity-documentation-modal-action="submit"]');
        if (submitButton) {
            submitButton.addEventListener("click", function (e) {
                e.preventDefault();

                if (validator) {
                    let isEditorValid = true;
                    if (editor) {
                        const editorContent = editor.getData().trim();
                        if (editorContent === '' || editorContent === '<p>&nbsp;</p>') {
                            isEditorValid = false;
                        }
                    }

                    validator.validate().then(function (status) {

                        if (status === "Valid" && isEditorValid) {
                            if (submitButton && typeof submitButton.setAttribute === 'function') {
                                submitButton.setAttribute("data-kt-indicator", "on");
                            }
                            if (submitButton && typeof submitButton.disabled !== 'undefined') {
                                submitButton.disabled = true;
                            }

                            const formData = new FormData();
                            const titleElement = form.querySelector('[name="title"]');
                            const categoryElement = form.querySelector('[name="activity_category_id"]');

                            const title = titleElement ? titleElement.value : '';
                            const categoryId = categoryElement ? categoryElement.value : '';


                            formData.append('title', title);
                            formData.append('activity_category_id', categoryId);
                            formData.append('_token', token);

                            const dateRange = $('#kt_add_activity_documentation_daterangepicker').val();
                            if (dateRange) {
                                const dates = dateRange.split(' - ');
                                formData.append('start_date', dates[0]);
                                if (dates.length > 1) {
                                    formData.append('end_date', dates[1]);
                                } else {
                                    formData.append('end_date', dates[0]);
                                }
                            }

                            if (editor) {
                                formData.append('description', editor.getData());
                            }

                            if (myDropzone && myDropzone.files.length > 0) {
                                myDropzone.files.forEach((file, index) => {
                                    formData.append(`images[${index}]`, file);
                                });
                            }

                            const errorSwal = {
                                buttonsStyling: false,
                                showConfirmButton: true,
                                confirmButtonText: "Tutup",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            };

                            $.ajax({
                                url: submitButton.dataset.url,
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                headers: {
                                    "X-CSRF-TOKEN": token,
                                },
                            })
                                .done((response) => {

                                    if (submitButton && typeof submitButton.removeAttribute === 'function') {
                                        submitButton.removeAttribute("data-kt-indicator");
                                    }
                                    if (submitButton && typeof submitButton.disabled !== 'undefined') {
                                        submitButton.disabled = false;
                                    }

                                    if (response.status === "success") {
                                        $('#kt_modal_add_activity_documentation').modal('hide');
                                    }

                                    Swal.fire(
                                        Object.assign(
                                            {
                                                text: response.message || 'Dokumentasi kegiatan berhasil ditambahkan.',
                                                icon: response.status || "success",
                                            },
                                            errorSwal
                                        )
                                    ).then(() => {
                                        if (response.status === "success") {
                                            resetForm();
                                            window.datatable.ajax.reload();
                                        }
                                    });
                                })
                                .fail((xhr, status, error) => {

                                    if (submitButton && typeof submitButton.removeAttribute === 'function') {
                                        submitButton.removeAttribute("data-kt-indicator");
                                    }
                                    if (submitButton && typeof submitButton.disabled !== 'undefined') {
                                        submitButton.disabled = false;
                                    }

                                    let errorMessage = 'Terjadi kesalahan.';
                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        } else if (xhr.responseJSON.errors) {
                                            const errors = Object.values(xhr.responseJSON.errors).flat();
                                            errorMessage = errors.join('<br>');
                                        }
                                    }

                                    Swal.fire(
                                        Object.assign(
                                            {
                                                html: errorMessage,
                                                icon: "error",
                                            },
                                            errorSwal
                                        )
                                    );
                                });
                        } else {
                            let errorMsg = 'Silakan lengkapi form dengan benar.';
                            if (!isEditorValid) {
                                errorMsg = 'Deskripsi kegiatan wajib diisi.';
                            }

                            Swal.fire({
                                text: errorMsg,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        }
                    }).catch(function (error) {
                        console.error('Validation error:', error);
                    });
                } else {
                    console.error('Validator not initialized');
                }
            });
        } else {
            console.error('Submit button not found. Available buttons:', element.querySelectorAll('button'));
        }

        $('#kt_modal_add_activity_documentation').on('hidden.bs.modal', function () {
            resetForm();
            if (validator) validator.resetForm(true);
        });
    };

    return {
        init: function () {

            if (!element) {
                console.error('Modal element not found!');
                return;
            }

            if (!form) {
                console.error('Form element not found!');
                return;
            }

            initCKEditor().then(() => {
                initDateRangePicker();
                initDropzone();
                initAddActivityDocumentation();
            }).catch(error => {
                console.error('Initialization error:', error);
            });
        },

    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTAddActivityDocumentation.init();
});