<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\ActivityImage;
use App\Traits\SiteTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    use SiteTrait;

    protected $title = 'Dokumentasi Kegiatan';
    protected $imageFolder = 'activities';

    public function index()
    {
        $title = $this->title;
        return view('pages.activity.documentation.index', compact('title'));
    }

    public function table()
    {
        return DataTables::of(Activity::query())
            ->addIndexColumn()
            ->addColumn('action', fn($data) => $data->id)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(ActivityRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $activity = Activity::create($data);

            if ($request->hasFile('images')) {
                $uploadedImages = $this->uploadMultipleFiles($request->file('images'), $this->imageFolder);

                foreach ($uploadedImages as $imagePath) {
                    $activity->activityImages()->create([
                        'activity_id' => $activity->id,
                        'file' => $imagePath,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Kegiatan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data Dokumentasi Kegiatan gagal disimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Activity $activity)
    {
        return response()->json($activity->load('activityImages'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $activity->update($data);

            if ($request->hasFile('images')) {
                $this->deleteMultipleFiles(
                    $activity->activityImages->pluck('image_path')->toArray()
                );
                $activity->activityImages()->delete();

                $uploadedImages = $this->uploadMultipleFiles($request->file('images'), $this->imageFolder);

                foreach ($uploadedImages as $imagePath) {
                    $activity->activityImages()->create([
                        'activity_id' => $activity->id,
                        'image_path' => $imagePath,
                        'image_url' => $this->getFileUrl($imagePath)
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Kegiatan berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data Dokumentasi Kegiatan gagal diperbarui: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Activity $activity)
    {
        try {
            DB::beginTransaction();

            $this->deleteMultipleFiles(
                $activity->activityImages->pluck('image_path')->toArray()
            );
            $activity->activityImages()->delete();

            $activity->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Kegiatan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data Dokumentasi Kegiatan gagal dihapus: ' . $e->getMessage()
            ], 500);
        }
    }
}