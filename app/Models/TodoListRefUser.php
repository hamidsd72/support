<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListRefUser extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function cat()
    {
        return $this->belongsTo('App\Models\TodoListRefUserCat', 'cat_id');
    }
    public function user_create()
    {
        return $this->belongsTo('App\Models\User', 'user_id_create');
    }

}
