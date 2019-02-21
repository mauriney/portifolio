<form method="post">
  <h1>Pessoal</h1>
  <div class="row">
    <div class="col-md-8">
      <div class="form-group">
        <label for="name">Nome<sup>*</sup></label>
        <input type="text" class="form-control" id="name" placeholder="Nome completo">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="name">Data de Nascimento<sup>*</sup></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="co-online-shop"></i></span>
          <input type="text" class="form-control" placeholder="00/00/0000">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <span class="sex">Sexo</span>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" id="male" value="option1"> Masculino
      </label>
      <label class="radio-inline">
        <input type="radio" name="inlineRadioOptions" id="female" value="option2"> Feminino
      </label>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="rg">RG<sup>*</sup></label>
        <input type="text" class="form-control" id="rg" placeholder="Número do RG">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="cpf">CPF<sup>*</sup></label>
        <input type="text" class="form-control" id="cpf" placeholder="Número do CPF">
      </div>
    </div>
  </div>
  <h1>Contato</h1>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="email">Email<sup>*</sup></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="co-new-email-outline"></i></span>
          <input type="text" class="form-control" placeholder="exemplo@exemplo.com">
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="contact">Contato</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="co-phone-call"></i></span>
          <input type="text" class="form-control" placeholder="(99) 9999-9999">
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="cellphone">Celular<sup>*</sup></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="co-online-shop"></i></span>
          <input type="text" class="form-control" placeholder="(99) 99999-9999">
        </div>
      </div>
    </div>
  </div>
  <h1>Endereço</h1>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="cep">Cep<sup>*</sup></label>
        <input type="text" class="form-control" id="cep" placeholder="00000-000">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="address">Endereço<sup>*</sup></label>
        <input type="text" class="form-control" id="address" placeholder="Rua, Travesa, Avenida">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label for="number">Número<sup>*</sup></label>
        <input type="text" class="form-control" id="number" placeholder="">
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="complement">Complemento</label>
        <input type="text" class="form-control" id="complement" placeholder="Bloco, Apartamento">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <label for="state">Estado<sup>*</sup></label>
        <select class="selectpicker">
          <option>AC</option>
          <option>AM</option>
          <option>SP</option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="address">Cidade<sup>*</sup></label>
        <select class="selectpicker">
          <option value="">Acrelândia</option>
          <option value="">Cruzeiro do Sul</option>
          <option value="">Marechal Thaumaturgo</option>
          <option value="">Rio Branco</option>
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="address">País<sup>*</sup></label>
        <select class="selectpicker">
          <option value="">Brasil</option>
          <option value="">Portugal</option>
          <option value="">Espanha</option>
          <option value="">Alemanha</option>
        </select>
      </div>
    </div>
  </div>
  <small class="mensage">Por gentileza, preencha corretamente os campos obrigatórios <span class="error">(*)</span>.</small>
  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
