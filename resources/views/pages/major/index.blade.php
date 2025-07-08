<x-default-layout>
    <x-alert-toast />
    <x-alert-modal />
    <x-card>
        <x-slot:header>
            {{ $title }}
        </x-slot:header>
        <x-slot:toolbar>
            <div class="d-flex justify-content-end">
                <x-button color="primary" dataBsToggle="modal" dataBsTarget="#kt_modal_1">
                    <x-icon class="fs-1" icon="plus-square" path="3"></x-icon>
                    Tambah
                </x-button>

                <x-modal style="width: " id="kt_modal_1" title="Tambah Jurusan">
                    <x-form :action="route('majors.store')" method="POST">
                        @csrf
                        <x-input type="text" name="name" label="Nama" required />
                    </x-form>
                </x-modal>
            </div>
        </x-slot:toolbar>
        <x-slot:body>
            <input type="hidden" id="table-url" value="{{ route('majors.table') }}">
            <x-table class="fs-6 gy-5" id="kt_majors_table">
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
        </x-slot>
    </x-card>
    @push('scripts')
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/pages/major/table.js') }}"></script>
    @endpush
</x-default-layout>
