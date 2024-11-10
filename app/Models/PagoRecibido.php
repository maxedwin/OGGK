<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PagoRecibido extends Model 
{
    protected $table = 'pagos_recibidos';
    protected $primaryKey = 'id_pago_recibido';
    public $timestamps = false;
}