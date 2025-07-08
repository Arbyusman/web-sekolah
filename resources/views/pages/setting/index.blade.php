<x-default-layout>
    <x-alert-toast />
    <x-alert-modal />
    <x-card>
        <x-slot name="body">
            <x-form :action="route('settings.update', ['setting' => $setting])" method="POST">
                @csrf
                @method('PUT')
                <x-slot name="header">
                    Settings
                </x-slot>
                <x-slot name="title">
                    Settings
                </x-slot>

                <x-input type="text" name="phone" label="Phone" :value="$setting->phone" required />
                <x-input type="email" name="email" label="Email" :value="$setting->email" required />

                <x-textarea label="Address" name="address" class="col-12 my-2" :value="$setting->address" />

                <x-textarea label="Maps" name="maps" class="col-12 my-2" :value="$setting->maps" />

                @if (!empty($setting->maps))
                    <div class="mt-4">
                        <label class="form-label fw-semibold">Map Preview</label>
                        <div class="ratio ratio-16x9">
                            {!! $setting->maps !!}
                        </div>
                    </div>
                @endif

            </x-form>
        </x-slot>
    </x-card>

</x-default-layout>
