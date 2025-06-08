<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $title = 'Konten Website';

    public function index()
    {
        $title = $this->title;
        $setting = Setting::first();

        return view('pages.setting.index', compact('setting', 'title'));
    }

    public function update(Request $request, Setting $setting)
    {
        //
    }
}
