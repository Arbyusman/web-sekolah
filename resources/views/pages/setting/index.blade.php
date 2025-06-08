<x-default-layout>

    <main class="card p-4">

        <x-title :title="$title" />
        <x-form :action="route('settings.update', ['setting' => $setting])" method="POST">
            <x-input class="col-12" type="text" name="Phone" label="phone" value="{{ $setting->phone }}"
                required="true" />
            <x-input class="col-12" type="email" name="Email" label="email" value="{{ $setting->email }}"
                required="true" />

            <textarea class="form-control col-12" name="Address" id="Address" rows="3" placeholder="Address">{{ $setting->address }}</textarea>
            <textarea class="form-control col-12" name="maps" id="maps" rows="3" placeholder="Maps">{{ $setting->maps }}</textarea>

            <button type="submit" class="btn btn-primary mt-3"> @include('partials/general/_button-indicator', ['label' => 'Update'])
        </x-form>
    </main>

</x-default-layout>
