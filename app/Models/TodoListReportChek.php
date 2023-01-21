<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListReportChek extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function check_id()
    {
        return $this->belongsTo('App\Models\TodoListChek', 'check_id');
    }
    public function todo_list()
    {
        return $this->belongsTo('App\Models\TodoList', 'todo_list_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}