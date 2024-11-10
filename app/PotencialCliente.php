<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PotencialCliente extends Model
{
    protected $table = 'potencial_clientes';
    protected $primaryKey = 'idpotencial';
    public $timestamps = false;

    public function last_visit()
    {
        return $this->hasOne('App\Models\Visit', 'id_cliente_no_ruc', 'idpotencial')->orderBy('created_at', 'desc');
    }
}
