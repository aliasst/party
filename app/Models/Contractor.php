<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'stage_id',
        'name',
        'comment',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Get the event that owns the contractor.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the stage associated with the contractor.
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
