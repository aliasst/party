<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Act extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'number', 'status', 'file_path'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function booted()
    {
        static::deleting(function ($act) {
            if ($act->file_path && Storage::disk('public')->exists($act->file_path)) {
                Storage::disk('public')->delete($act->file_path);
            }
        });
    }
}
