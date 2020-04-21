<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'users_permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user', 'id_permission',
    ];

    function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

    function permission() {
        return $this->belongsTo('App\Permission', 'id_permission', 'id');
    }
}
