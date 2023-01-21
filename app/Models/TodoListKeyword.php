<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListKeyword extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_list()
    {
        return $this->belongsTo('App\Models\TodoList', 'todo_list_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
