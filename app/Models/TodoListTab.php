<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListTab extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function checks()
    {
        return $this->hasMany('App\Models\TodoListChek', 'tab_id');
    }
}