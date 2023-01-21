<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoListChek extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function todo_list_reports()
    {
        return $this->hasMany('App\Models\TodoListReportChek', 'check_id');
    }
    public function todo_list_cat()
    {
        return $this->belongsTo('App\Models\TodoListCat', 'cat_id');
    }
    public function todo_list_tab()
    {
        return $this->belongsTo('App\Models\TodoListTab', 'tab_id');
    }
}
