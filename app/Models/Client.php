<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabinet_id', 'name', 'legal_name', 'email', 'phone', 'requisites'
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'client_event');
    }
}
