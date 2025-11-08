<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    public function activityImages()
    {
        return $this->hasMany(ActivityImage::class);
    }
}
