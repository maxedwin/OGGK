@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Lista Documentos</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
<li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
<li>Documentos</li>
<li class="active">Lista de documentos</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

<li>
    <a href="crear_documento" >
        <i class="icon-box-add position-left"></i>
        Nuevo Documento
    </a>
</li>
@stop

@section('contenido')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{ URL::to('/') }}">

<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="heading-elements">

        </div>

    </div>

    <div class="panel-body">
        <form class="form-inline">
            <div class="form-group">
                <input type="text" id="busqueda_nombre" class="form-control input-lg" id="formGroupExampleInput" placeholder="Numero de placa">
                <button type="button" id="btnbusqueda" class="btn btn-primary"><i class="icon-search4 position-left"></i> Buscar Placa</button>
            </div>
        </form>
        <div class="text-right">
        </div>
        <!--LISTA DE PRODUCTOS -->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" id="products_table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Costo Unitario</th>
                <th>Precio Unitario</th>
                <th>Cantidad Total</th>
                <th>Ubicacion</th>
                <th>Procedencia</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="products">

            </tbody>
        </table>
    </div>
    <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
        <div class="text-right">
        </div>
    </div>

</div>
@stop
