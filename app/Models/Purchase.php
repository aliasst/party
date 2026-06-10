<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'stage_id', 'name', 'description', 'comment', 'purchase_date', 'file_path'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    protected static function booted()
    {
        static::deleting(function ($purchase) {
            if ($purchase->file_path && Storage::disk('public')->exists($purchase->file_path)) {
                Storage::disk('public')->delete($purchase->file_path);
            }
        });
    }
}
