<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'number', 'is_paid', 'file_path'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Удаление файла при удалении записи
    protected static function booted()
    {
        static::deleting(function ($invoice) {
            if ($invoice->file_path && Storage::disk('public')->exists($invoice->file_path)) {
                Storage::disk('public')->delete($invoice->file_path);
            }
        });
    }
}
