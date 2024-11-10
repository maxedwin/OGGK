<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model 
{
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';
    public $timestamps = false;

    public function last_visit()
    {
        return $this->hasOne('App\Models\Visit', 'idcliente', 'idcliente')->orderBy('created_at', 'desc');
    }

}