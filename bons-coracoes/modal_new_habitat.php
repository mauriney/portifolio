<!-- Modal -->
<div class="modal fade modal-default" id="new-habit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="co-close"></i></button>
      </div>
      <div class="modal-body">
        <h1>Novo Hábito/Desafiar Amigo</h1>
        <form class="" action="" method="post">
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="form-group">
                <label for="name">Título do Hábito<sup>*</sup></label>
                <input type="text" class="form-control" id="name" placeholder="Informe o título do hábito">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="form-group">
                <label for="resilience">Resiliência<sup>*</sup></label>
                <textarea name="resilience" rows="3" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="form-group">
                <label for="rules">Regras<sup>*</sup></label>
                <textarea name="rules" rows="5" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5 col-md-offset-1">
              <div class="form-group">
                <label for="start">Início<sup>*</sup></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="co-calendar"></i></span>
                  <input type="text" class="form-control" placeholder="00/00/0000">
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label for="finish">Término<sup>*</sup></label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="co-calendar"></i></span>
                  <input type="text" class="form-control" placeholder="00/00/0000">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="form-group">
                <label for="friends">Amigos</label>
                <select id="friends" class="selectpicker" data-live-search="true">
                  <option data-tokens="jacob">Jacob Gomes de Almeida Júnior</option>
                  <option data-tokens="Rogerio">Rogério Moura Rosas Marinho</option>
                  <option data-tokens="Mauriney">Mauriney da Costa Dias</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-primary btn-new btn-lg">Salvar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
