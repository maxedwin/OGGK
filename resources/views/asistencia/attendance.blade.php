@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="icon-pen6 position-left"></i><span class="text-semibold">Registro de Asistencia</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active"></i>Registro de Asistencia</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')


@stop

<!-- CONTENIDO DE LA PAGINA -->

@section('contenido')

<style type="text/css">
        .hide-loader{
            display:none;
        }

}


button{
    margin:2em;
}

.col-md-4{
    text-align:right!important;
}

label{
    font-size:1.5vw;
}
</style>


<div class="container">
<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <?php 
                        $ingreso="0";
                        $salida="0";
                        $idasistencia=0;
                        if($asistencia){
                            $ingreso=$asistencia->check_in;
                            $salida=$asistencia->check_out;
                            $idasistencia=$asistencia->id;
                        }
                        if($salida){   
                            $check=$salida;
                        }
                        else{
                            $check="  - - : - -";
                        }
                ?>
                <div class="panel-heading" ><h5 style="font-size:2vw!important;">Registrar Asistencia </h5></div>
                <div class="panel-body">

                     <form class="form-horizontal" action="{{route('asistencia-save')}}" method="post" role="form" id="form0">
                         <input  id="input_ingreso" type="hidden" value="{{$ingreso}}" />
                        <input  id="input_salida" type="hidden" value="{{$salida}}"/>

                     </form>
                    <form class="form-horizontal" action="{{route('asistencia-save')}}" method="post" role="form" id="form0">
                        <input  name="input_id" id="input_id" type="hidden" value="{{$idasistencia}}" />
                        @if ($asistencia)
                        <div>
                            <label class="col-md-4 control-label">Ingreso:</label>

                            <label class="col-md-6 control-label">{{$ingreso}}</label>
                        </div>

                        <div >
                            <label class="col-md-4 control-label">Salida:</label>

                             <label class="col-md-6 control-label">{{$check}}</label>
                        </div>

                        @else

                        <div >
                            <label class="col-md-12 control-label" style="text-align:center" >Aún no se registró el ingreso</label>

                             
                        </div>

                        @endif




                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4" style="padding-top:2em;">
                                <button id="buton_in" type="submit" class="btn btn-info" onclick="enviar_form()">
                                    <i class="fa fa-btn fa-check position-left"></i>Ingreso
                                </button>

                                <button id="buton_out" type="submit" class="btn btn-info" onclick="enviar_form()">
                                    <i class="fa fa-btn fa-sign-out position-left"></i>Salida
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


          
</div>













<script rel="script" type="text/javascript">
   //Program to disable or enable a button using javascript

let cin = document.getElementById('buton_in');
let cout = document.getElementById('buton_out');

let ingreso = document.getElementById("input_ingreso");
let salida = document.getElementById("input_salida");

let id = document.getElementById("input_id");


    if (ingreso.value === "0" ) {
        cin.disabled = false;
        cout.disabled = true; 
        
    }
    else{
        if(salida.value === "0"){
            cout.disabled = false;
            cin.disabled= true; 

        }
        else{
            cout.disabled = true;
            cin.disabled= true; 
        }

        //button is enabled
    }

function enviar_form(){
    
    $( "#form0" ).submit();
       
};


</script>

@stop
