<x-default-layout>
    <x-alert-modal />
    <x-card>
        <x-slot:header>
            {{ $title }}
        </x-slot:header>
        <x-slot:toolbar>
            <div class="d-flex justify-content-end">
                <x-button color="primary" dataBsToggle="modal" dataBsTarget="#kt_modal_add_activity_documentation">
                    <x-icon class="fs-1" icon="plus-square" path="3"></x-icon>
                    Tambah
                </x-button>

                <x-modal size="xl" id="kt_modal_add_activity_documentation" title="Tambah Dokumentasi kegiatan">
                    <form id="kt_modal_add_activity_documentation_form">
                        @csrf
                        <div class="fv-row mb-5">
                            <x-input type="text" name="title" id="title" label="Judul Kegitan" />
                        </div>

                        <div class="fv-row mb-5">
                            <x-select2 name="activity_category_id" searchable="true" apiUrl="{{ route('activity.categories.search') }}"
                                placeholder="Search items..." >
                            </x-select2>
                        </div>

                        <div class="fv-row mb-5">
                            <label class="form-label">Deskripsi Kegiatan</label>
                            <textarea name="description" id="kt_add_activity_documentation_ckeditor_classic">
                            </textarea>
                        </div>

                        <div class="fv-row mb-5">
                            <div class="dropzone" id="kt_add_activity_documentation_dropzone_images">
                                <div class="dz-message needsclick">
                                    <i class="ki-duotone ki-file-up fs-3x text-primary"><span
                                            class="path1"></span><span class="path2"></span></i>

                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.
                                        </h3>
                                        <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                                    </div>
                                </div>
                                <div id="kt_add_activity_documentation_dropzone_images_preview"></div>
                            </div>

                            <div class="text-end mt-4">
                                <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                                <x-button type="submit" color="primary">Simpan</x-button>
                            </div>
                        </div>
                    </form>
                </x-modal>

            </div>
        </x-slot:toolbar>
        <x-slot:body>
            <input type="hidden" id="table-url" value="{{ route('activity.documentations.table') }}">
            <x-table class="fs-6 gy-5" id="kt_activity_documentations_table">
                <x-slot:head>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:80%">Nama</th>
                        <th style="width:15%">Aksi</th>
                    </tr>
                </x-slot:body>

                <x-slot:body>

                </x-slot:head>
            </x-table>
            <x-modal id="kt_modal_edit_activity_documentation" title="Edit Dokumentasi Kegiatan" size="lg">
                <x-form action="" id="kt_modal_edit_activity_documentation_form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- CKEditor for Title -->
                    <div class="fv-row mb-5">
                        <label class="form-label">Judul Kegiatan</label>
                        <textarea name="title" id="kt_docs_ckeditor_classic" class="form-control"></textarea>
                    </div>

                    <!-- Dropzone for Multiple Images -->
                    <div class="fv-row mb-5">
                        <label class="form-label">Upload Gambar</label>
                        <div class="dropzone" id="kt_dropzonejs_example_1">
                            <div class="dz-message needsclick">
                                <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                    <span class="fs-7 fw-semibold text-gray-400">Upload up to 10 files</span>
                                </div>
                            </div>
                        </div>
                        <div id="kt_dropzonejs_example_1_preview" class="mt-3"></div>
                    </div>

                    <div class="text-end mt-4">
                        <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                        <x-button type="submit" color="primary">Simpan</x-button>
                    </div>
                </x-form>
            </x-modal>

            <x-modal class="delete-activity-documentation-btn" id="kt_modal_delete_activity_documentation"
                title="Hapus Dokumentasi kegiatan">
                <x-form action="" id="kt_modal_delete_activity_documentation_form" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="id">
                    <p>Apakah Anda yakin ingin Dokumentasi kegiatan ini?</p>
                    <div class="text-end mt-4">
                        <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                        <x-button type="submit" color="danger">Hapus</x-button>
                    </div>
                </x-form>
            </x-modal>


        </x-slot>
    </x-card>
    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/documentation/table.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/documentation/add.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/documentation/edit.js') }}"></script>
    @endpush
</x-default-layout>
