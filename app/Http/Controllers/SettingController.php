<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $title = 'Konten Website';

    public function index()
    {
        $title = $this->title;
        $setting = Setting::first();

        return view('pages.setting.index', compact('setting', 'title'));
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        try {

            $validated = $request->validated();
            $setting->update($validated);

            return redirect()->back()->with('success', 'Konten Website berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Konten Website gagal diperbarui: ' . $e->getMessage());
        }
    }
}
