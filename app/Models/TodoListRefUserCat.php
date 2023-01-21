<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListRefUserCat extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function users_ref()
    {
        return $this->hasMany('App\Models\TodoListRefUser', 'cat_id');
    }
    public function user_create()
    {
        return $this->belongsTo('App\Models\User', 'user_id_create');
    }

}
