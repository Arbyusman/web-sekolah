<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityCategoryRequest;
use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ActivityCategoryController extends Controller
{
    protected $title = 'Kategori Kegiatan';

    public function index()
    {
        $title = $this->title;

        return view('pages.activity.category.index', compact('title'));
    }

    public function table()
    {
        return DataTables::of(ActivityCategory::query())
            ->addIndexColumn()
            ->addColumn('action', fn($data) => $data->id)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(ActivityCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            ActivityCategory::create($data);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Kategori Kegiatan berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Kategori Kegiatan gagal disimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show(ActivityCategory $activityCategory)
    {
        return response()->json($activityCategory);
    }

    public function update(ActivityCategoryRequest $request, ActivityCategory $activityCategory)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $activityCategory->update($data);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Kategori Kegiatan berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Kategori Kegiatan gagal diperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(ActivityCategory $activityCategory)
    {
        try {
            DB::beginTransaction();
            $activityCategory->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Kategori Kegiatan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Kategori Kegiatan gagal dihapus: ' . $e->getMessage()], 500);
        }
    }


    public function search(Request $request)
    {
        $search = $request->get('search');

        $activityCategories = ActivityCategory::search($search);

        return response()->json($activityCategories);
    }
}
