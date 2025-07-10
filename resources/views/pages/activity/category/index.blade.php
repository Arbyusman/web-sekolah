<x-default-layout>
    <x-alert-modal />
    <x-card>
        <x-slot:header>
            {{ $title }}
        </x-slot:header>
        <x-slot:toolbar>
            <div class="d-flex justify-content-end">
                <x-button color="primary" dataBsToggle="modal" dataBsTarget="#kt_modal_add_activity_category">
                    <x-icon class="fs-1" icon="plus-square" path="3"></x-icon>
                    Tambah
                </x-button>

                <x-modal id="kt_modal_add_activity_category" title="Tambah kategori kegiatan">
                    <form id="kt_modal_add_activity_category_form">
                        @csrf
                        <div class="fv-row mb-5">
                            <x-input type="text" name="name" id="name" label="Nama" />
                        </div>
                        <div class="text-end mt-4">
                            <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                            <x-button type="submit" color="primary">Simpan</x-button>
                        </div>
                    </form>
                </x-modal>

            </div>
        </x-slot:toolbar>
        <x-slot:body>
            <input type="hidden" id="table-url" value="{{ route('activity.categories.table') }}">
            <x-table class="fs-6 gy-5" id="kt_activity_categories_table">
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
            <x-modal id="kt_modal_edit_activity_category" title="Edit kategori kegiatan">
                <x-form action="" id="kt_modal_edit_activity_category_form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="fv-row mb-5">
                        <x-input type="text" name="name" id="name" label="Nama" />
                    </div>
                    <div class="text-end mt-4">
                        <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                        <x-button type="submit" color="primary">Simpan</x-button>
                    </div>
                </x-form>
            </x-modal>

            <x-modal class="delete-activity-category-btn" id="kt_modal_delete_activity_category" title="Hapus kategori kegiatan">
                <x-form action="" id="kt_modal_delete_activity_category_form" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="id">
                    <p>Apakah Anda yakin ingin kategori kegiatan ini?</p>
                    <div class="text-end mt-4">
                        <x-button type="button" color="light" dataBsDismiss="modal">Batal</x-button>
                        <x-button type="submit" color="danger">Hapus</x-button>
                    </div>
                </x-form>
            </x-modal>


        </x-slot>
    </x-card>
    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/categories/table.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/categories/add.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/activity/categories/edit.js') }}"></script>
    @endpush
</x-default-layout>
