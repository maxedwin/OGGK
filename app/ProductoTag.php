<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoTag extends Model
{
    protected $table = 'productotag';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function tags_de_producto($idproducto){
        $tags = DB::table('productotag')->where('idproducto','=',$idproducto)->get();
        return $tags;
    }
}
