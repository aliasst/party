<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Cabinet.php
class Cabinet extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'cabinet_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }



}
