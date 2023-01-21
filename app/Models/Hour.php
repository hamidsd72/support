<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'id',  'ticket_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
