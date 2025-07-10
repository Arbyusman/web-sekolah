<?php

namespace App\Http\Controllers;

use App\Http\Requests\MajorRequest;
use App\Models\Major;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MajorController extends Controller
{
    protected $title = 'Jurusan';

    public function index()
    {
        $title = $this->title;

        return view('pages.major.index', compact('title'));
    }

    public function table()
    {
        return DataTables::of(Major::query())
            ->addIndexColumn()
            ->addColumn('action', fn ($data) => $data->id)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(MajorRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            Major::create($data);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Jurusan berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Jurusan gagal disimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show(Major $major)
    {
        return response()->json($major);
    }

    public function update(MajorRequest $request, Major $major)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $major->update($data);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Jurusan berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Jurusan gagal diperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Major $major)
    {
        try {
            DB::beginTransaction();
            $major->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Jurusan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Data Jurusan gagal dihapus: ' . $e->getMessage()], 500);
        }
    }
}
