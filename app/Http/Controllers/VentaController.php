<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use DB;
use Auth;


class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function searchQuery(Request $request){
        $query= $request['query'];
        $tamano_p=14;
        $pagina=$request['pagina'];
        if(!$pagina){
            $inicio=0;
            $pagina=1;
        }else{
            $inicio=($pagina-1) *$tamano_p;
        }
        $results = DB::select(DB::raw("select p.*, m.nombre as marca , c.descripcion as categoria , c.color from producto p
                                        left join marcas m on p.idmarca = m.idmarca
                                        left join categorias c on c.idcategoria = p.idcategoria
                                         where p.nombre LIKE :query limit :inicio,:tama_pagina"),array(
            'query' => '%'.$query.'%',
            'inicio' => $inicio,
            'tama_pagina' => $tamano_p,

        ));
        return json_encode($results);
    }

    public function searchBarcode(Request $request){
        $query= $request['query'];
        $tamano_p=14;
        $pagina=$request['pagina'];
        if(!$pagina){
            $inicio=0;
            $pagina=1;
        }else{
            $inicio=($pagina-1) *$tamano_p;
        }
        $results = DB::select(DB::raw("select p.*, m.nombre as marca , c.descripcion as categoria , c.color from producto p
                                        left join marcas m on p.idmarca = m.idmarca
                                        left join categorias c on c.idcategoria = p.idcategoria
                                         where p.barcode=:query limit :inicio,:tama_pagina"),array(
            'query' => $query,
            'inicio' => $inicio,
            'tama_pagina' => $tamano_p,

        ));
        return json_encode($results);
    }

    public function productoVentaDetail(Request $request){
        $id= $request['idproducto'];
        $results = DB::select(DB::raw("select p.*, m.nombre as nombre_marca,m.img as imagen_marca , c.color as color from producto p
                                        left join marcas m on m.idmarca=p.idmarca
													 left join categorias c on c.idcategoria= p.idcategoria where idproducto=:id"),array(
            'id' => $id
        ));
        $motos = DB::select(DB::raw("select pm.idmoto, m.nombre,m.imagen,m.serie from productomotos pm
                                        left join motos m on m.idmoto= pm.idmoto
                                        where pm.idproducto=:id"),array(
            'id' => $id
        ));
        $response=array();
        $response['data']=$results;
        $response['motos']=$motos;
        $response['imagenes']=json_decode($results[0]->imagenes);
        return json_encode($response);
    }


}