@extends('index')
@section('contenido')
<?PHP
    header("Access-Control-Allow-Origin:*");
 ?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{ URL::to('/') }}">
<div class="header">
<div class="page-bar align-right" style="background-color: #fff; margin-bottom: 10px;">
          <ul class="page-breadcrumb">
              <li>
                  <a href="index.html">Panel</a>
                  <i class="fa fa-angle-right"></i>
              </li>
              <li>
                  <span>Lista de Productos</span>
                  <i class="fa fa-angle-right"></i>
              </li>
              <li>
                  <span class="{{ $color }}" >{{ $title }}</span>
                  <i class="fa fa-angle-right"></i>
              </li>
              <li>
               Producto: <b><small><?PHP echo strtoupper($product->name); ?></small></b>
              </li>
          </ul>
      </div>
      <div class="navbar navbar-default" role="navigation">
      <ul class="horizontal">
            <li class="item"><a id="guardar" data-id={{ $product->productID }}><i class="fa fa-save"></i>Guardar</a></li>
            <li class="item"><a id="regresar" href="list_product"><i class="fa fa-angle-double-left"></i>Regresar</a></li>
        </ul>
      </div>
     
    </div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 ">
                                <!-- BEGIN SAMPLE FORM PORTLET-->
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption {{ $color }}">
                                            <i class="icon-settings {{ $color }}"></i>
                                            <span class="caption-subject bold uppercase {{ $color }}"> Datos del Producto</span>
                                        </div>
                                       
                                    </div>

                                    <div class="portlet-body form">
                                        <form role="form">
                                            <div class="form-body">
                                              <div class="form-group form-md-line-input has-error">
                                                  <label for="form_control_1">Categoria</label>
                                                    <input type="text" class="form-control" id="category" placeholder="Escoger categoria del lado izquierdo" value="{{ $category->description}}" disabled>
                                                    <input type="hidden" id="type" value="{{ $type }}">                                                 
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                   <label for="form_control_1">Nombre del Producto</label>
                                                    <input type="text" class="form-control"  id="name" placeholder="Nombre del Producto" value="{{$product->name}}" disabled>
                                                    
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                  <label for="form_control_1">Cantidad Actual</label>
                                                    <input type="number" class="form-control"  placeholder="Cantidad del producto" id="quantityTotal" value="{{$product->quantityTotal}}" disabled>    
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                  <label for="form_control_1">Cantidad {{ $cant }}</label>
                                                    <input type="number" class="form-control"  placeholder="Cantidad del producto" id="quantity" value="{{$product->quantityTotal}}">    
                                                </div>                                                
                                        </form>
                                    </div>
                                </div>
                                 
                            </div>
</div>
<script type="text/javascript">
</script>
<script src="{{ URL::asset('/javascript/jstree.min.js') }}" type="text/javascript"></script>


<script src="{{ URL::asset('/javascript/transacts.js') }}" type="text/javascript"></script>

    @stop