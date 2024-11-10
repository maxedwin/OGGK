<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarcaCliente extends Model
{
    protected $table = 'marca_clientes';
    protected $primaryKey = 'idmarcaclientes';
    public $timestamps = false;
}
