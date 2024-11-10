<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class GuiaRemisionH extends Model
{
    protected $table = 'guia_remisionh';
    protected $primaryKey = 'id_guia_remisionh';
    public $timestamps = false;


    public function pending_guiaremisonh($limit){
      $pending_guias = DB::table('guia_remisionh')->join('clientes', 'clientes.idcliente', '=', 'guia_remisionh.idcliente')
                                        ->join('orden_ventah', 'orden_ventah.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                                        ->select('guia_remisionh.id_guia_remisionh','guia_remisionh.numeracion', 'guia_remisionh.codigoNB as codigoNB', 'clientes.razon_social', 'clientes.ruc_dni','orden_ventah.f_entrega','guia_remisionh.f_entregado', 'guia_remisionh.f_reprogramar')
                                        ->whereIn('guia_remisionh.estado_doc', [0, 1, 4])
                                        ->orderBy('orden_ventah.f_entrega', 'desc')
                                        ->get();
      foreach($pending_guias as $guia){
        $guia->productos = $this->product_list($guia->id_guia_remisionh);
      }
      return $pending_guias;
    }

    public function product_list($idGuiaRemisionH){
      return DB::table('guia_remisiond')->join('producto', 'guia_remisiond.idproducto', '=','producto.idproducto')
                                        ->select('guia_remisiond.id_guia_remisionh', 'producto.nombre', 'guia_remisiond.cantidad')
                                        ->where('id_guia_remisionh',$idGuiaRemisionH)
                                        ->get();
    }
}
