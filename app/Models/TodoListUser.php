<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListUser extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_list_cat()
    {
        return $this->belongsTo('App\Models\TodoList', 'cat_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function user_create()
    {
        return $this->belongsTo('App\Models\User', 'user_create_id');
    }

}
