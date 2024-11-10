<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class GuiaTrasladoH extends Model
{
    protected $table = 'guia_trasladoh';
    protected $primaryKey = 'id_guia_trasladoh';
    public $timestamps = false;


    
    public function product_list($idGuiaTrasladoH){
      return DB::table('guia_trasladod')->join('producto', 'guia_trasladod.idproducto', '=','producto.idproducto')
                                        ->select('guia_trasladod.id_guia_trasladoh', 'producto.nombre', 'guia_trasladod.cantidad')
                                        ->where('id_guia_trasladoh',$idGuiaTrasladoH)
                                        ->get();
    }
}
