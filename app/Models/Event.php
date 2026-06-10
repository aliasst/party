<?php

namespace App\Models;

use App\Data\DefaultStagesData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabinet_id', 'title', 'cover', 'description',
        'start_date', 'end_date', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // Единый метод booted() – вызывается при инициализации модели
    protected static function booted()
    {
        // Автоматическое обновление статуса перед сохранением
        static::saving(function ($event) {
            $today = now()->startOfDay();
            $event->status = ($event->start_date <= $today) ? 'past' : 'future';
        });

        // Удаление файла обложки при удалении записи
        static::deleting(function ($event) {
            if ($event->cover && Storage::disk('public')->exists($event->cover)) {
                Storage::disk('public')->delete($event->cover);
            }
        });
    }

    // Отношения
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function acts()
    {
        return $this->hasMany(Act::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allStages()
    {
        return $this->hasMany(Stage::class);
    }

    public function contractors()
    {
        return $this->hasMany(Contractor::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function loadDefaultStages()
    {
        $data = DefaultStagesData::get();
        $sort = 1;
        foreach ($data as $parentData) {
            $parent = $this->stages()->create([
                'name'       => $parentData['name'],
                'status'     => 'planned',
                'sort_order' => $sort++,
            ]);
            $childSort = 1;
            foreach ($parentData['children'] as $childName) {
                $parent->children()->create([
                    'event_id'   => $this->id,
                    'name'       => $childName,
                    'status'     => 'planned',
                    'sort_order' => $childSort++,
                ]);
            }
        }
    }
}
