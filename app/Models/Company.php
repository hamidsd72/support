<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user__id');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commendable');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($check) {
            $check->comments()->delete();
        });
    }

}
