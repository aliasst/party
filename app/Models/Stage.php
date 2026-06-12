<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'parent_id', 'name', 'start_date', 'end_date', 'status', 'comment', 'sort_order'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function parent()
    {
        return $this->belongsTo(Stage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Stage::class, 'parent_id')->orderBy('sort_order');
    }

    public function files()
    {
        return $this->hasMany(StageFile::class);
    }

    protected static function booted()
    {
        static::saved(function ($stage) {
            if ($stage->event) {
                $stage->event->updateProgressPercent();
            }
        });

        static::deleted(function ($stage) {
            if ($stage->event) {
                $stage->event->updateProgressPercent();
            }
        });
    }
}
