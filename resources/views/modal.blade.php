<div class="container">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" >Crear Nota</button>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nueva Nota</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('store_nota')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

          <div class="modal-body">
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Titulo:</label>
              <input type="text" name="titulo" class="form-control" id="titulo">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Texto:</label>
              <textarea class="form-control" name="texto" id="texto"></textarea>
            </div>
          </div>
        
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar Nota</button>
          </div>
      </form>
    </div>
  </div>
</div>