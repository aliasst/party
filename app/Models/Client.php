<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['cabinet_id', 'name', 'email', 'phone', 'company', 'address'];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
