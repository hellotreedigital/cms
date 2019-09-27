<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = 'admin_roles';

    protected $guarded = ['id'];

 //    public function permissions()
	// {
	// 	return $this->hasMany('App\AdminRolePermission');
	// }
}