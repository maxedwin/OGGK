<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','lastname','dni','email' , 'password','accountID','telefono','direccion','f_nac','sexo','f_entrada','puesto','f_salida','est_ent','distrito','provincia','departamento'
    ];
    // protected $table = 'usuarios';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'actived',
    ];

}
