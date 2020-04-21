<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    function user_permission() {
        return $this->hasMany('App\UserPermission', 'id_permission', 'id');
    }
}
