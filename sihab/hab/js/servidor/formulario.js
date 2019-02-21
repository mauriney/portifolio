/*---------------------------------------------------------------------------------------------------------
 DATA: 06/06/2017 ÀS 12:09
 NOME: JS DA CLASSE CADASTRO DE FORMULÁRIO DO SERVIDOR
 ---------------------------------------------------------------------------------------------------------*/
// CADASTRO DO SERVIDOR
$('form#form_servidor').submit(function () {

  if (validator()) {
    $.ajax({
      type: "POST",
      url: PORTAL_URL + 'hab/dao/servidor/formulario',
      data: $('#form_servidor').serialize(),
      cache: false,
      success: function (obj) {
        obj = JSON.parse(obj);
        if (obj.msg == 'success') {
          swal({
            title: "Formulário do Servidor",
            text: obj.retorno,
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          }, function () {
            setTimeout("location.href='" + PORTAL_URL + "hab/servidor/informacoes/" + obj.id + "'", 1);
          });
          return false;
        } else if (obj.msg == 'error') {
          swal({
            title: "Formulário do Servidor",
            text: obj.retorno,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
          $("#div_loader").hide();
          return false;
        }
      },
      error: function (obj) {
        swal({
          title: "Formulário do Servidor",
          text: obj.retorno,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "OK",
          closeOnConfirm: false
        });
        $("#div_loader").hide();
        return false;
      }
    });
    return false;
  } else {
    return false;
  }
});
// ---------------------------------------------------------------------------------------------------------
// VALIDAÇÃO DO SERVIDOR
function validator() {
  var valido = true;
  var nome = $("#nome").val();
  var cpf = $("#cpf").val();
  var identidade = $("#identidade").val();
  var orgao_expedidor = $("#orgao_expedidor").val();
  var data_expedicao = $("#data_expedicao").val();
  var data_nascimento = $("#data_nascimento").val();
  var estado_civil = $("#estado_civil").val();
  var residencial = $("#residencial").val();
  var bairro = $("#bairro").val();
  var servidor_estado = $("select#servidor_estado").val();
  var servidor_cidade = $("#servidor_cidade").val();
  var tel_residencial = $("#tel_residencial").val();
  var celular = $("#celular").val();
  var email = $("#email").val();
  var nacionalidade_brasileiro = $("input[id='nacionalidade_brasileiro']:checked").val();
  var nacionalidade_estrangeiro = $("input[id='nacionalidade_estrangeiro']:checked").val();
  var naturalidade_estado = $("#naturalidade_estado").val();
  var naturalidade_municipio = $("#naturalidade_municipio").val();
  var servidor_pais = $("#servidor_pais").val();
  var dependentes = $("#dependentes").val();
  var ano_reside_cidade = $("#ano_reside_cidade").val();
  var mes_reside_cidade = $("#mes_reside_cidade").val();
  var paga_aluguel = $("input[id='aluguel_sim']:checked").val();
  var valor_aluguel = $("#valor_aluguel").val();
  var mae = $("#mae").val();
  //CÔJUGE
  var uniao_estavel = $("input[id='uniao_estavel']:checked").val();
  var conjuge_nome = $("#conjuge_nome").val();
  var conjuge_cpf = $("#conjuge_cpf").val();
  var conjuge_rg = $("#conjuge_rg").val();
  var conjuge_data_nasc = $("#conjuge_data_nasc").val();
  var conjuge_profissao = $("#conjuge_profissao").val();
  var conjuge_nacionalidade_brasileiro = $("input[id='conjuge_nacionalidade_brasileiro']:checked").val();
  var conjuge_nacionalidade_estrangeiro = $("input[id='conjuge_nacionalidade_estrangeiro']:checked").val();
  var conjuge_naturalidade_estado = $("#conjuge_naturalidade_estado").val();
  var conjuge_naturalidade_municipio = $("#conjuge_naturalidade_municipio").val();
  var conjuge_servidor_pais = $("#conjuge_servidor_pais").val();
  //IMÓVEL
  var tipo_edificacao = $("#tipo_edificacao").val();
  //DADOS FUNCIONAIS
  var lotacao = $("#lotacao").val();
  var funcional_endereco = $("#funcional_endereco").val();
  var funcional_bairro = $("#funcional_bairro").val();
  var funcional_estado = $("#funcional_estado").val();
  var funcional_cidade = $("#funcional_cidade").val();
  var funcional_vinculacao = $("#funcional_vinculacao").val();
  var funcional_matricula = $("#funcional_matricula").val();
//  var funcional_telefone_comercial = $("#funcional_telefone_comercial").val();
//  var funcional_ramal = $("#funcional_ramal").val();
  var funcional_data_admissao = $("#funcional_data_admissao").val();
  var funcional_renumeracao = $("#funcional_renumeracao").val();
  var funcional_cargo = $("#funcional_cargo").val();
  var funcional_entidade = $("#funcional_entidade").val();
  //DECLARAÇÃO
  var declaracao = $("input[id='declaracao']:checked").val();
  var element = null;
  // LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });
  if (declaracao != 1) {
    $('input#declaracao').parents('div.checkbox').after('<label id="erro_declaracao" class="error space-b-20">A declaração é obrigatória para salvar o cadastro.</label>');
    valido = false;
    element = $('input#declaracao');
  }

  if (tipo_edificacao == "") {
    $('select#tipo_edificacao').parents('div.col-md-5').find('div.bootstrap-select').after('<label id="erro_tipo_edificacao" class="error space-b-20">Tipo de edificação é obrigatório.</label>');
    valido = false;
    element = $('select#tipo_edificacao');
  }

  if (funcional_renumeracao == "") {
    $('select#funcional_renumeracao').parents('div.col-md-5').find('div.bootstrap-select').after('<label id="erro_funcional_renumeracao" class="error space-b-20">Renumeração é obrigatório.</label>');
    valido = false;
    element = $('select#funcional_renumeracao');
  }

  if (funcional_vinculacao == "") {
    $('select#funcional_vinculacao').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_funcional_vinculacao" class="error space-b-20">Vinculação é obrigatório.</label>');
    valido = false;
    element = $('select#funcional_vinculacao');
  }

  if (funcional_data_admissao == "") {
    $('input#funcional_data_admissao').after('<label id="erro_funcional_data_admissao" class="error space-b-20">Data de admissão é obrigatório.</label>');
    valido = false;
    element = $('input#funcional_data_admissao');
  } else {
    var partesData = funcional_data_admissao.split("/");
    var data_iso_admissao_1 = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);
    if (data_iso_admissao_1 > new Date()) {
      $('input#funcional_data_admissao').after('<label id="erro_funcional_data_admissao" class="error space-b-20">A data de admissão não pode ser maior que a data atual.</label>');
      valido = false;
      element = $('input#funcional_data_admissao');
    }
  }

//  if (funcional_ramal == "") {
//    $('input#funcional_ramal').after('<label id="erro_funcional_ramal" class="error space-b-20">Ramal é obrigatório.</label>');
//    valido = false;
//    element = $('input#funcional_ramal');
//  }
//
//  if (funcional_telefone_comercial == "") {
//    $('input#funcional_telefone_comercial').after('<label id="erro_funcional_telefone_comercial" class="error space-b-20">Telefone comercial é obrigatório.</label>');
//    valido = false;
//    element = $('input#funcional_telefone_comercial');
//  }

  if (funcional_matricula == "") {
    $('input#funcional_matricula').after('<label id="erro_funcional_matricula" class="error space-b-20">Matrícula é obrigatório.</label>');
    valido = false;
    element = $('input#funcional_matricula');
  }

  if (funcional_cargo == "") {
    $('input#funcional_cargo').after('<label id="erro_funcional_cargo" class="error space-b-20">Cargo é obrigatório.</label>');
    valido = false;
    element = $('input#funcional_cargo');
  }

  if (funcional_cidade == "") {
    $('select#funcional_cidade').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_funcional_cidade" class="error space-b-20">Cidade é obrigatório.</label>');
    valido = false;
    element = $('select#funcional_cidade');
  }

  if (funcional_estado == "") {
    $('select#funcional_estado').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_funcional_estado" class="error space-b-20">Estado é obrigatório.</label>');
    valido = false;
    element = $('select#funcional_estado');
  }

  if (funcional_bairro == "") {
    $('input#funcional_bairro').after('<label id="erro_funcional_bairro" class="error space-b-20">Bairro é obrigatório.</label>');
    valido = false;
    element = $('input#funcional_bairro');
  }

  if (funcional_endereco == "") {
    $('input#funcional_endereco').after('<label id="erro_funcional_endereco" class="error space-b-20">Endereço é obrigatório.</label>');
    valido = false;
    element = $('input#funcional_endereco');
  }

  if (lotacao == "") {
    $('input#lotacao').after('<label id="erro_lotacao" class="error space-b-20">Lotação é obrigatório.</label>');
    valido = false;
    element = $('input#lotacao');
  }

  if (funcional_entidade == "") {
    $('select#funcional_entidade').parents('div.col-md-6').find('div.bootstrap-select').after('<label id="erro_funcional_entidade" class="error space-b-20">Entidade pública é obrigatório.</label>');
    valido = false;
    element = $('select#funcional_entidade');
  }

  if (uniao_estavel == 1 || estado_civil == 9) {

    if (conjuge_nacionalidade_estrangeiro == 0) {
      if (conjuge_servidor_pais == "") {
        $('select#conjuge_servidor_pais').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_conjuge_servidor_pais" class="error space-b-20">País é obrigatório.</label>');
        valido = false;
        element = $('select#conjuge_servidor_pais');
      }
    } else if (conjuge_nacionalidade_brasileiro == 1) {
      if (conjuge_naturalidade_municipio == "") {
        $('select#conjuge_naturalidade_municipio').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_conjuge_naturalidade_municipio" class="error space-b-20">Cidade é obrigatório.</label>');
        valido = false;
        element = $('select#conjuge_naturalidade_municipio');
      }

      if (conjuge_naturalidade_estado == "") {
        $('select#conjuge_naturalidade_estado').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_conjuge_naturalidade_estado" class="error space-b-20">Estado é obrigatório.</label>');
        valido = false;
        element = $('select#conjuge_naturalidade_estado');
      }
    }

    if (conjuge_profissao == "") {
      $('input#conjuge_profissao').after('<label id="erro_conjuge_profissao" class="error space-b-20">Profissão é obrigatório.</label>');
      valido = false;
      element = $('input#conjuge_profissao');
    }

    if (conjuge_data_nasc == "") {
      $('input#conjuge_data_nasc').after('<label id="erro_conjuge_data_nasc" class="error space-b-20">Data de nascimento é obrigatório.</label>');
      valido = false;
      element = $('input#conjuge_data_nasc');
    }

    if (conjuge_rg == "") {
      $('input#conjuge_rg').after('<label id="erro_conjuge_rg" class="error space-b-20">Identidade é obrigatório.</label>');
      valido = false;
      element = $('input#conjuge_rg');
    }

    if (conjuge_cpf == "") {
      $('input#conjuge_cpf').after('<label id="erro_conjuge_cpf" class="error space-b-20">CPF é obrigatório.</label>');
      valido = false;
      element = $('input#conjuge_cpf');
    }

    if (conjuge_nome == "") {
      $('input#conjuge_nome').after('<label id="erro_conjuge_nome" class="error space-b-20">Nome é obrigatório.</label>');
      valido = false;
      element = $('input#conjuge_nome');
    }
  }

  if (paga_aluguel == 1 && valor_aluguel == "") {
    $('input#valor_aluguel').after('<label id="erro_valor_aluguel" class="error space-b-20">Valor do aluguel é obrigatório.</label>');
    valido = false;
    element = $('input#valor_aluguel');
  }

  if (mes_reside_cidade == "") {
    $('select#mes_reside_cidade').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_mes_reside_cidade" class="error space-b-20">Meses que reside nesta cidade é obrigatório.</label>');
    valido = false;
    element = $('select#mes_reside_cidade');
  }

  if (ano_reside_cidade == "") {
    $('select#ano_reside_cidade').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_ano_reside_cidade" class="error space-b-20">Anos que reside nesta cidade é obrigatório.</label>');
    valido = false;
    element = $('select#ano_reside_cidade');
  }

  if (dependentes == "") {
    $('input#dependentes').after('<label id="erro_dependentes" class="error space-b-20">Número de dependentes é obrigatório.</label>');
    valido = false;
    element = $('input#dependentes');
  }

  if (mae == "") {
    $('input#mae').after('<label id="erro_mae" class="error space-b-20">Nome da mãe é obrigatório.</label>');
    valido = false;
    element = $('input#mae');
  }

  if (nacionalidade_estrangeiro == 0) {
    if (servidor_pais == "") {
      $('select#servidor_pais').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_servidor_pais" class="error space-b-20">País é obrigatório.</label>');
      valido = false;
      element = $('select#servidor_pais');
    }
  } else if (nacionalidade_brasileiro == 1) {
    if (naturalidade_municipio == "") {
      $('select#naturalidade_municipio').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_naturalidade_municipio" class="error space-b-20">Cidade é obrigatório.</label>');
      valido = false;
      element = $('select#naturalidade_municipio');
    }

    if (naturalidade_estado == "") {
      $('select#naturalidade_estado').parents('div.col-md-4').find('div.bootstrap-select').after('<label id="erro_naturalidade_estado" class="error space-b-20">Estado é obrigatório.</label>');
      valido = false;
      element = $('select#naturalidade_estado');
    }
  }

  if (email == "") {
    $('input#email').after('<label id="erro_email" class="error space-b-20">E-mail é obrigatório.</label>');
    valido = false;
    element = $('input#email');
  }

  if (celular == "") {
    $('input#celular').after('<label id="erro_celular" class="error space-b-20">Celular é obrigatório.</label>');
    valido = false;
    element = $('input#celular');
  }

  if (tel_residencial == "") {
    $('input#tel_residencial').after('<label id="erro_tel_residencial" class="error space-b-20">Telefone residencial é obrigatório.</label>');
    valido = false;
    element = $('input#tel_residencial');
  }

  if (servidor_cidade == "") {
    $('select#servidor_cidade').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_servidor_cidade" class="error space-b-20">Cidade é obrigatório.</label>');
    valido = false;
    element = $('select#servidor_cidade');
  }

  if (servidor_estado == "") {
    $('select#servidor_estado').parents('div.col-md-2').find('div.bootstrap-select').after('<label id="erro_servidor_estado" class="error space-b-20">Estado é obrigatório.</label>');
    valido = false;
    element = $('select#servidor_estado');
  }

  if (bairro == "") {
    $('input#bairro').after('<label id="erro_bairro" class="error space-b-20">Bairro é obrigatório.</label>');
    valido = false;
    element = $('input#bairro');
  }

  if (residencial == "") {
    $('input#residencial').after('<label id="erro_residencial" class="error space-b-20">Endereço residencial é obrigatório.</label>');
    valido = false;
    element = $('input#residencial');
  }

  if (estado_civil == "") {
    $('select#estado_civil').parents('div.col-md-3').find('div.bootstrap-select').after('<label id="erro_estado_civil" class="error space-b-20">Estado civil é obrigatório.</label>');
    valido = false;
    element = $('select#estado_civil');
  }

  if (data_nascimento == "") {
    $('input#data_nascimento').after('<label id="erro_data_nascimento" class="error space-b-20">Data de nascimento é obrigatório.</label>');
    valido = false;
    element = $('input#data_nascimento');
  } else {
    if (calcIdade(data_nascimento) < 18) {
      $('input#data_nascimento').after('<label id="erro_data_nascimento" class="error space-b-20">Não é permitido servidor menor que 18 anos.</label>');
      valido = false;
      element = $('input#data_nascimento');
    }
  }

  if (data_expedicao == "") {
    $('input#data_expedicao').after('<label id="erro_data_expedicao" class="error space-b-20">Data de expedição é obrigatório.</label>');
    valido = false;
    element = $('input#data_expedicao');
  } else {
    var partesData = data_expedicao.split("/");
    var data_iso_expedicao_1 = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);
    if (data_iso_expedicao_1 > new Date()) {
      $('input#data_expedicao').after('<label id="erro_data_expedicao" class="error space-b-20">A data de expedição não pode ser maior que a data atual.</label>');
      valido = false;
      element = $('input#data_expedicao');
    }
  }

  if (orgao_expedidor == "") {
    $('select#orgao_expedidor').parents('div.col-md-5').find('div.bootstrap-select').after('<label id="erro_orgao_expedidor" class="error space-b-20">Órgão expedidor é obrigatório.</label>');
    valido = false;
    element = $('select#orgao_expedidor');
  }

  if (identidade == "") {
    $('input#identidade').after('<label id="erro_identidade" class="error space-b-20">Identidade é obrigatório.</label>');
    valido = false;
    element = $('input#identidade');
  }

  if (cpf == "") {
    $('input#cpf').after('<label id="erro_cpf" class="error space-b-20">CPF é obrigatório.</label>');
    valido = false;
    element = $('input#cpf');
  }

  if (nome == "") {
    $('input#nome').after('<label id="erro_nome" class="error space-b-20">Nome é obrigatório.</label>');
    valido = false;
    element = $('input#nome');
  }


  if (element != null) {
    var topPosition = element.offset().top - 135;
    $('html, body').animate({
      scrollTop: topPosition
    }, 800);
  }

  return valido;
}
// ---------------------------------------------------------------------------------------------------------
// ESTADO E MUNICÍPIO DO ENDEREÇO
$("#servidor_estado").change(function () {
  $("#servidor_cidade").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php", {
    estado: $(this).val()
  }, function (valor) {
    $("#servidor_cidade").html(valor);
    $('#servidor_cidade').selectpicker('refresh');
  });
});
// ---------------------------------------------------------------------------------------------------------
// ESTADO E MUNICÍPIO DOS DADOS FUNCIONAIS
$("#funcional_estado").change(function () {
  $("#funcional_cidade").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php", {
    estado: $(this).val()
  }, function (valor) {
    $("#funcional_cidade").html(valor);
    $('#funcional_cidade').selectpicker('refresh');
  });
});
// ---------------------------------------------------------------------------------------------------------
// ADD MÁSCARA DE DINHEIRO
$("input#valor_aluguel").mask("###.###.###.##0,00", {
  reverse: true
});
// ---------------------------------------------------------------------------------------------------------
// ESTADO E MUNICÍPIO DATA NATURALIDADE
$("#naturalidade_estado").change(function () {
  $("#naturalidade_municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php", {
    estado: $(this).val()
  }, function (valor) {
    $("#naturalidade_municipio").html(valor);
    $('#naturalidade_municipio').selectpicker('refresh');
  });
});
// ---------------------------------------------------------------------------------------------------------
// CÔJUGE ESTADO E MUNICÍPIO DATA NATURALIDADE
$("#conjuge_naturalidade_estado").change(function () {
  $("#conjuge_naturalidade_municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php", {
    estado: $(this).val()
  }, function (valor) {
    $("#conjuge_naturalidade_municipio").html(valor);
    $('#conjuge_naturalidade_municipio').selectpicker('refresh');
  });
});
// ---------------------------------------------------------------------------------------------------------
// NACIONALIDADE ESTRANGEIRO
$("#nacionalidade_estrangeiro").change(function () {

  if ($(this).val() == 0) {
    $("#estrangeiro").show();
    $("#brasileiro").hide();
    $("#div_naturalidade").hide();
    $('#naturalidade_estado').selectpicker('deselectAll');
    $('#naturalidade_estado').change();
    $('#naturalidade_municipio').selectpicker('deselectAll');
    $('#naturalidade_municipio').change();
  } else {
    $("#estrangeiro").hide();
    $("#brasileiro").show();
    $("#div_naturalidade").show();
    $('#servidor_pais').selectpicker('deselectAll');
    $('#servidor_pais').change();
  }

});
// ---------------------------------------------------------------------------------------------------------
// NACIONALIDADE BRASILEIRO
$("#nacionalidade_brasileiro").change(function () {

  if ($(this).val() == 1) {
    $("#estrangeiro").hide();
    $("#brasileiro").show();
    $("#div_naturalidade").show();
    $('#servidor_pais').selectpicker('deselectAll');
    $('#servidor_pais').change();
  } else {
    $("#estrangeiro").show();
    $("#brasileiro").hide();
    $("#div_naturalidade").hide();
    $('#naturalidade_estado').selectpicker('deselectAll');
    $('#naturalidade_estado').change();
    $('#naturalidade_municipio').selectpicker('deselectAll');
    $('#naturalidade_municipio').change();
  }
});
// ---------------------------------------------------------------------------------------------------------
// CÔNJUGE NACIONALIDADE ESTRANGEIRO
$("#conjuge_nacionalidade_estrangeiro").change(function () {

  if ($(this).val() == 0) {
    $("#conjuge_estrangeiro").show();
    $("#conjuge_brasileiro").hide();
    $("#div_conjuge_naturalidade").hide();
    $('#conjuge_naturalidade_estado').selectpicker('deselectAll');
    $('#conjuge_naturalidade_estado').change();
    $('#conjuge_naturalidade_municipio').selectpicker('deselectAll');
    $('#conjuge_naturalidade_municipio').change();
  } else {
    $("#conjuge_estrangeiro").hide();
    $("#conjuge_brasileiro").show();
    $("#div_conjuge_naturalidade").show();
    $('#conjuge_servidor_pais').selectpicker('deselectAll');
    $('#conjuge_servidor_pais').change();
  }

});
// ---------------------------------------------------------------------------------------------------------
// CÔNJUGE NACIONALIDADE BRASILEIRO
$("#conjuge_nacionalidade_brasileiro").change(function () {

  if ($(this).val() == 1) {
    $("#conjuge_estrangeiro").hide();
    $("#conjuge_brasileiro").show();
    $("#div_conjuge_naturalidade").show();
    $('#conjuge_servidor_pais').selectpicker('deselectAll');
    $('#conjuge_servidor_pais').change();
  } else {
    $("#conjuge_estrangeiro").show();
    $("#conjuge_brasileiro").hide();
    $("#div_conjuge_naturalidade").hide();
    $('#conjuge_naturalidade_estado').selectpicker('deselectAll');
    $('#conjuge_naturalidade_estado').change();
    $('#conjuge_naturalidade_municipio').selectpicker('deselectAll');
    $('#conjuge_naturalidade_municipio').change();
  }
});
// ---------------------------------------------------------------------------------------------------------
// PAGA ALUGUEL SIM
$("#aluguel_sim").change(function () {
  $("div#div_aluguel").show();
});
// ---------------------------------------------------------------------------------------------------------
// PAGA ALUGUEL NÃO
$("#aluguel_nao").change(function () {
  $("div#div_aluguel").hide();
});
// ---------------------------------------------------------------------------------------------------------
// UNIÃO ESTÁVEL
$("#uniao_estavel").change(function () {
  $("div#card_conjuge").toggle();
});
// ---------------------------------------------------------------------------------------------------------
// ESTADO CIVIL
$("#estado_civil").change(function () {

  $("input#uniao_estavel").removeAttr('checked');

  if ($(this).val() == 9) {
    $("div#div_uniao_estavel").hide();
    $("div#card_conjuge").show();
  } else {
    $("div#div_uniao_estavel").show();
    $("div#card_conjuge").hide();
  }
});
//------------------------------------------------------------------------
// DECLARAÇÃO DE INFORMAÇÕES VERDADEIRAS
$('input#declaracao').change(function () {

  var declaracao = $("input[id='declaracao']:checked").val();
  if (declaracao == 1) {
    $("button#salvar").show();
  } else {
    $("button#salvar").hide();
  }

  $('html, body').animate({
    scrollTop: $("#div_geral").height()
  }, 800);
});
//------------------------------------------------------------------------