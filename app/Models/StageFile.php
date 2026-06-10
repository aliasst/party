<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StageFile extends Model
{
    use HasFactory;

    protected $fillable = ['stage_id', 'file_path', 'original_name'];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    protected static function booted()
    {
        static::deleting(function ($file) {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        });
    }
}
