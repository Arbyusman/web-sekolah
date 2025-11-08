"use strict";

let KTUpdateActivityDocumentation = (function () {
    let documentationId;
    const element = document.getElementById("kt_modal_edit_activity_documentation");
    const form = element.querySelector("#kt_modal_edit_activity_documentation_form");
    const token = $("meta[name='csrf-token']").attr("content");

    let editor, myDropzone;

    const initCKEditor = () => {
        const editorElement = document.querySelector('#kt_docs_ckeditor_classic');
        if (!editorElement) {
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
                console.error('Edit CKEditor initialization error:', error);
                return null;
            });
    };

    const initDropzone = () => {
        const dropzoneElement = document.querySelector("#kt_dropzonejs_example_1");
        if (!dropzoneElement) {
            return;
        }

        if (dropzoneElement.dropzone) {
            dropzoneElement.dropzone.destroy();
        }

        dropzoneElement.classList.remove('dz-clickable');

        try {
            myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
                url: "/dummy-upload",
                paramName: "images",
                maxFiles: 10,
                maxFilesize: 5,
                addRemoveLinks: true,
                acceptedFiles: "image/*",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                previewsContainer: "#kt_dropzonejs_example_1_preview",
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
            console.error('Edit Dropzone initialization error:', error);
        }
    };

    let initUpdateActivityDocumentation = () => {
        let validator = FormValidation.formValidation(form, {
            fields: {
                title: {
                    validators: {
                        callback: {
                            message: 'Judul wajib diisi',
                            callback: function(input) {
                                if (editor && editor.getData().trim() === '') {
                                    return false;
                                }
                                return true;
                            }
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

        const closeButton = element.querySelector('[data-bs-dismiss="modal"]');
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
                        form.reset();
                        if (editor) editor.setData('');
                        if (myDropzone) myDropzone.removeAllFiles(true);
                    }
                });
            });
        }

        $(document).on("click", ".edit-activity-documentation-btn", function (e) {
            e.preventDefault();

            documentationId = $(this).data("id");

            $.ajax({
                url: `/activity/documentations/${documentationId}`,
                type: "GET",
            }).then((response) => {
                if (editor) {
                    editor.setData(response.title || '');
                }
                
                if (myDropzone) {
                    myDropzone.removeAllFiles(true);
                }
                
                if (response.images && response.images.length > 0) {
                    response.images.forEach(function(image) {
                        const mockFile = {
                            name: image.original_name || 'image.jpg',
                            size: image.size || 0,
                            type: 'image/jpeg',
                            status: Dropzone.ADDED,
                            url: image.url
                        };
                        
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, image.url);
                        myDropzone.emit("complete", mockFile);
                        mockFile.existingImageId = image.id;
                    });
                }
            }).fail(() => {
                Swal.fire({
                    text: 'Gagal memuat data dokumentasi kegiatan.',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: "Ok!",
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                });
            });
        });

        const submitButton = element.querySelector('button[type="submit"]');
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
                            submitButton.setAttribute("data-kt-indicator", "on");
                            submitButton.disabled = true;

                            const formData = new FormData();
                            formData.append('_token', token);

                            if (editor) {
                                formData.append('title', editor.getData());
                            }

                            if (myDropzone && myDropzone.files.length > 0) {
                                myDropzone.files.forEach((file, index) => {
                                    if (!file.existingImageId) {
                                        formData.append(`images[${index}]`, file);
                                    }
                                });
                            }

                            const errorSwal = {
                                buttonsStyling: false,
                                showConfirmButton: true,
                                confirmButtonText: "Close",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            };

                            $.ajax({
                                url: `/activity/documentations/${documentationId}`,
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                headers: {
                                    "X-HTTP-Method-Override": "PUT",
                                },
                            })
                            .done((response) => {
                                submitButton.disabled = false;
                                submitButton.removeAttribute("data-kt-indicator");

                                $('#kt_modal_edit_activity_documentation').modal('hide');

                                Swal.fire(
                                    Object.assign(
                                        {
                                            text: response.message || 'Dokumentasi kegiatan berhasil diperbarui.',
                                            icon: response.status || "success",
                                        },
                                        errorSwal
                                    )
                                ).then(() => {
                                    if (response.status === "success") {
                                        form.reset();
                                        if (editor) editor.setData('');
                                        if (myDropzone) myDropzone.removeAllFiles(true);
                                        if (typeof window.datatable !== 'undefined') {
                                            window.datatable.ajax.reload();
                                        }
                                    }
                                });
                            })
                            .fail((xhr, status, error) => {
                                submitButton.removeAttribute("data-kt-indicator");
                                submitButton.disabled = false;

                                let errorMessage = 'Terjadi kesalahan saat memperbarui.';
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
                                errorMsg = 'Judul kegiatan wajib diisi.';
                            }

                            Swal.fire({
                                text: errorMsg,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        }
                    });
                }
            });
        }
    };

    return {
        init: function () {
            initCKEditor().then(() => {
                initDropzone();
                initUpdateActivityDocumentation();
            });
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTUpdateActivityDocumentation.init();
});
