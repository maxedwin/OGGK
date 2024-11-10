<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PagoEfectuado extends Model 
{
    protected $table = 'pagos_efectuados';
    protected $primaryKey = 'id_pago_efectuado';
    public $timestamps = false;
}