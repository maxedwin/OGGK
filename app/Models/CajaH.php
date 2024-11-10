<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class CajaH extends Model 
{
    protected $table = 'cajah';
    protected $primaryKey = 'idcajah';
    public $timestamps = false;

    public function guias(){
        return $this->hasMany('App\Models\CajaGuia','idcaja')
                ->join('guia_remisionh', 'guia_remisionh.id_guia_remisionh', '=', 'cajaguiaventa.idguia')
                ->select('cajaguiaventa.*', 'guia_remisionh.numeracion', 'guia_remisionh.f_entregado');
    }

    public function pending_cajah($limit){
      $pending_facts = DB::table('cajah')->leftJoin('pagos_recibidos', 'pagos_recibidos.idcajah', '=', 'cajah.idcajah')
                                        ->join('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                                        ->select('cajah.idcliente', 'cajah.estado_doc', 'clientes.razon_social', 'cajah.codigoNB', 'clientes.ruc_dni', 'cajah.numeracion', 'cajah.id_orden_ventah', 'cajah.idcajah', 'cajah.total', 'cajah.total_nc', DB::raw('sum(pagos_recibidos.pagado) as pagos_recibidos'), 'cajah.moneda', 'cajah.f_cobro')
                                        ->whereIn('cajah.estado_doc', [0, 4, 6])
                                        ->groupBy('idcajah')
                                        ->orderBy('cajah.f_cobro', 'desc')
                                        ->get();
      return $pending_facts;
    }

    public function cmp($a, $b){
        if ($a->retraso == $b->retraso) {
            return 0;
        }
        return ($a->retraso > $b->retraso) ? -1 : 1;
    }

    public function historial_deudores($state = null){
        if( $state == 'pending' ){ // returning ranking of debtors based on their current debt, that means current debtors since the moment the deliver was made until today
            $debtors = DB::table('cajah')
                         ->select(DB::raw('guia_remisionh.id_guia_remisionh, orden_ventah.id_orden_ventah, clientes.direccion, clientes.ruc_dni, clientes.contacto_telefono, clientes.idcliente, idcajah, cajah.codigoNB, guia_remisionh.f_entregado,(DATEDIFF (CURDATE() ,guia_remisionh.f_entregado)) as retraso, clientes.razon_social,cajah.total, cajah.estado_doc as pay_state, guia_remisionh.estado_doc as deliver_state, null as f_pagado'))
                         ->join('clientes', 'cajah.idcliente','=','clientes.idcliente')
                         ->leftjoin('orden_ventah', 'cajah.id_orden_ventah','=','orden_ventah.id_orden_ventah')
                         ->leftjoin('guia_remisionh', 'guia_remisionh.id_orden_ventah','=','orden_ventah.id_orden_ventah')
                         ->where('guia_remisionh.estado_doc', '=', 2)
                          ->whereIn('cajah.estado_doc', [0, 4, 6])
                         ->orderBy('retraso', 'desc')
                         ->get();
        }
        else{  // otherwise we return historial debtors since the beginning of time orderer by the days they took since the moment the deliver was made until they payed
            $debtors = DB::table('cajah')
                         ->select(DB::raw('guia_remisionh.id_guia_remisionh, orden_ventah.id_orden_ventah, clientes.ruc_dni, clientes.direccion, clientes.contacto_telefono, clientes.idcliente, idcajah, cajah.codigoNB, guia_remisionh.f_entregado,(DATEDIFF (CURDATE() ,guia_remisionh.f_entregado)) as retraso, clientes.razon_social,cajah.total, cajah.estado_doc as pay_state, guia_remisionh.estado_doc as deliver_state'))
                         ->join('clientes', 'cajah.idcliente','=','clientes.idcliente')
                         ->leftjoin('orden_ventah', 'cajah.id_orden_ventah','=','orden_ventah.id_orden_ventah')
                         ->leftjoin('guia_remisionh', 'guia_remisionh.id_orden_ventah','=','orden_ventah.id_orden_ventah')
                         ->where('guia_remisionh.estado_doc', '=', 2)
                          ->whereIn('cajah.estado_doc', [2, 5])
                         ->get();

            foreach( $debtors as $debtor ){
                $f_pagado = DB::table('pagos_recibidos')->select(DB::raw('DATE(created_at) as created_at'))
                                            ->where([['pagos_recibidos.idcajah','=', $debtor->idcajah], ['pagos_recibidos.por_pagar', '=', 0]])
                                            ->first();
                $debtor->f_pagado = $f_pagado->created_at;
                $f_pagado = date_create($debtor->f_pagado);
                $f_entregado = date_create($debtor->f_entregado);

                $debtor->retraso = date_diff($f_entregado,$f_pagado)->format('%a');
            }
            usort($debtors, array($this,'cmp'));
        }
      return $debtors;
    }
}