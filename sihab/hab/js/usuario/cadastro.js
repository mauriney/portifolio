/*---------------------------------------------------------------------------------------------------------
 DATA: 28/07/2016 ÀS 11:39
 NOME: JS DA CLASSE DE CADASTRO DE USUÁRIO
 ---------------------------------------------------------------------------------------------------------*/
//COMBO ESTADO E MUNICÍPIO
$(document).ready(function () {

  //Jogando a busca para a lista de usuário
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/usuario/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE USUÁRIO
  $('form#form_usuario').submit(function () {

    $("#div_loader").show();

    if (usuario_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/usuario/cadastro.php',
        data: $('#form_usuario').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Usuário",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/usuario/lista'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Usuário",
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
            title: "Formulário de Usuário",
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
      $("#div_loader").hide();
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DA USUÁRIO
  function usuario_validator() {
    var valido = true;
    var nome = $("#nome").val();
    var sexo = $("#sexo").val();
    var cpf = $("#cpf").val();
    var nascimento = $("#nascimento").val();
    var rg = $("#rg").val();
    var uf_expedicao = $("#uf_expedicao").val();
    var cnh = $("#cnh").val();
    var estado_nascimento = $("#estado_nascimento").val();
    var municipio_nascimento = $("#municipio_nascimento").val();
    var email_pessoal = $("#email_pessoal").val();
    var celular = $("#celular").val();
    var telefone = $("#telefone").val();
    var cep = $("#cep").val();
    var logradouro = $("#logradouro").val();
    var numero = $("#numero").val();
    var bairro = $("#bairro").val();
    var complemento = $("#complemento").val();
    var estado = $("#estado").val();
    var municipio = $("#municipio").val();
    var orgao = $("#orgao").val();
    var telefone_institucional = $("#telefone_institucional").val();
    var setor = $("#setor").val();
    var cargo = $("#cargo").val();
    var data_admissao = $("#data_admissao").val();
    var email_institucional = $("#email_institucional").val();

    var element = null;

    //LIMPA MENSAGENS DE ERRO
    $('label.error').each(function () {
      $(this).remove();
    });

    //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS

    if (email_institucional == "") {
      $('div#div_email_institucional').after('<label id="erro_email_institucional" class="error">O campo e-mail institucional é obrigatório.</label>');
      valido = false;
      element = $('div#div_email_institucional');
    }

    if (data_admissao == "") {
      $('div#div_data_admissao').after('<label id="erro_data_admissao" class="error">O campo data é obrigatório.</label>');
      valido = false;
      element = $('div#div_data_admissao');
    }

    if (cargo == "") {
      $('div#div_cargo').after('<label id="erro_cargo" class="error">O campo cargo é obrigatório.</label>');
      valido = false;
      element = $('div#div_cargo');
    }

    if (setor == "") {
      $('div#div_setor').after('<label id="erro_setor" class="error">O campo setor é obrigatório.</label>');
      valido = false;
      element = $('div#div_setor');
    }

    if (telefone_institucional == "") {
      $('div#div_telefone_institucional').after('<label id="erro_telefone_institucional" class="error">O campo telefone é obrigatório.</label>');
      valido = false;
      element = $('div#div_telefone_institucional');
    }

    if (orgao == "") {
      $('div#div_orgao').after('<label id="erro_orgao" class="error">O campo órgão é obrigatório.</label>');
      valido = false;
      element = $('div#div_orgao');
    }

//    if (municipio == "") {
//      $('div#div_municipio').after('<label id="erro_municipio" class="error">O campo município é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_municipio');
//    }
//
//    if (estado == "") {
//      $('div#div_estado').after('<label id="erro_estado" class="error">O campo estado é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_estado');
//    }

//    if (complemento == "") {
//      $('div#div_complemento').after('<label id="erro_complemento" class="error">O campo complemento é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_complemento');
//    }

    if (bairro == "") {
      $('div#div_bairro').after('<label id="erro_bairro" class="error">O campo bairro é obrigatório.</label>');
      valido = false;
      element = $('div#div_bairro');
    }

    if (numero == "") {
      $('div#div_numero').after('<label id="erro_numero" class="error">O campo número é obrigatório.</label>');
      valido = false;
      element = $('div#div_numero');
    }

    if (logradouro == "") {
      $('div#div_logradouro').after('<label id="erro_logradouro" class="error">O campo logradouro é obrigatório.</label>');
      valido = false;
      element = $('div#div_logradouro');
    }

    if (cep == "") {
      $('div#div_cep').after('<label id="erro_cep" class="error">O campo CEP é obrigatório.</label>');
      valido = false;
      element = $('div#div_cep');
    }

    if (telefone == "") {
      $('div#div_telefone').after('<label id="erro_telefone" class="error">O campo telefone é obrigatório.</label>');
      valido = false;
      element = $('div#div_telefone');
    }

    if (celular == "") {
      $('div#div_celular').after('<label id="erro_celular" class="error">O campo celular é obrigatório.</label>');
      valido = false;
      element = $('div#div_celular');
    }

    if (email_pessoal == "") {
      $('div#div_email_pessoal').after('<label id="erro_email_pessoal" class="error">O campo e-mail pessoal é obrigatório.</label>');
      valido = false;
      element = $('div#div_email_pessoal');
    }

//    if (municipio_nascimento == "") {
//      $('div#div_municipio_nascimento').after('<label id="erro_municipio_nascimento" class="error">O campo município é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_municipio_nascimento');
//    }
//
//    if (estado_nascimento == "") {
//      $('div#div_estado_nascimento').after('<label id="erro_estado_nascimento" class="error">O campo estado é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_estado_nascimento');
//    }

//    if (cnh == "") {
//      $('div#div_cnh').after('<label id="erro_cnh" class="error">O campo CNH é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_cnh');
//    }

    if (uf_expedicao == "") {
      $('div#div_uf_expedicao').after('<label id="erro_uf_expedicao" class="error">O campo UF de expedição é obrigatório.</label>');
      valido = false;
      element = $('div#div_uf_expedicao');
    }

    if (rg == "") {
      $('div#div_rg').after('<label id="erro_rg" class="error">O campo rg é obrigatório.</label>');
      valido = false;
      element = $('div#div_rg');
    }

    if (nascimento == "") {
      $('div#div_nascimento').after('<label id="erro_nascimento" class="error">O campo nascimento é obrigatório.</label>');
      valido = false;
      element = $('div#div_nascimento');
    }

    if (cpf == "") {
      $('div#div_cpf').after('<label id="erro_cpf" class="error">O campo CPF é obrigatório.</label>');
      valido = false;
      element = $('div#div_cpf');
    }

//    if (sexo == "") {
//      $('div#div_sexo').after('<label id="erro_sexo" class="error">O campo sexo é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_sexo');
//    }

    if (nome == "") {
      $('div#div_nome').after('<label id="erro_nome" class="error">O campo nome é obrigatório.</label>');
      valido = false;
      element = $('div#div_nome');
    }

    if (element != null) {
      var topPosition = element.offset().top - 135;
      $('html, body').animate({
        scrollTop: topPosition
      }, 800);
    }

    return valido;
  }
  //---------------------------------------------------------------------------------------------------------
  //ESTADO E MUNICÍPIO DO NASCIMENTO
  $("#estado_nascimento").change(function () {
    $("#municipio_nascimento").html('<option value="0">Carregando...</option>');
    $.post(PORTAL_URL + "hab/dao/basico/combo/cidades.php",
            {estado: $(this).val()},
    function (valor) {
      $("#municipio_nascimento").html(valor);
      $('#municipio_nascimento').selectpicker('refresh');
    });
  });

//ESTADO E MUNICÍPIO DO ENDEREÇO
  $("#estado").change(function () {
    $("#municipio").html('<option value="0">Carregando...</option>');
    $.post(PORTAL_URL + "hab/dao/basico/combo/cidades.php",
            {estado: $(this).val()},
    function (valor) {
      $("#municipio").html(valor);
      $('#municipio').selectpicker('refresh');
    });
  });

});
//---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR ENDEREÇOS AUTOMÁTICO PELO CEP DIGITADO
function consultacep() {

  var cep = $("#cep").val();

  if (cep.replace(/[^\d]+/g, '').length == 8) {
    $("#div_loader").show();
    projetouniversal.util.getjson({
      url: PORTAL_URL + "assets/plugins/busca-cep-correios/cep.php",
      type: "POST",
      data: {cep: cep},
      enctype: 'multipart/form-data',
      success: onSuccessSendConsultaCep,
      error: onErrorConsultaCep
    });
    return false;
  }
}
//---------------------------------------------------------------------------------------------------------
//SE A CONSULTA DER SUCESSO
function onSuccessSendConsultaCep(obj) {
  if (obj.msg == 'success') {

    document.getElementById('logradouro').value = obj.logradouro;
    document.getElementById('bairro').value = obj.bairro;

    var c = document.getElementById("estado"), i = 0;
    for (; i < c.options.length; i++)
    {
      if (c.options[i].label == obj.uf)
      {
        var val = c.options[i].value;
        $('#estado').val(val).trigger('change');
        break;
      }
      $("#estado").change();
    }
    carrega_municipio(obj.cidade);
    $('#municipio').change();
  }
  $("#div_loader").hide();
  return false;
}
//---------------------------------------------------------------------------------------------------------
//SE A CONSULTA DER ERRADO
function onErrorConsultaCep(args) {
  $.prompt('Cep não encontrado.');
  $("#div_loader").hide();
  return false;
}
//---------------------------------------------------------------------------------------------------------
//CARREGA OS MUNICÍPIOS AUTOMÁTICO
function carrega_municipio(municipio) {
  $("#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades.php",
          {estado: $('#estado').val()},
  function (data) {

    $("#municipio").html(data);
    $('#municipio').change();

    if (isNaN(data)) {
      var e = document.getElementById("municipio"), i = 0;
      for (; i < e.options.length; i++)
      {

        $('#municipio').change();

        if (e.options[i].label == municipio)
        {
          var val = e.options[i].value;
          $('#municipio').val(val).trigger('change');
          break;
        }
      }
    }

  });
}
//---------------------------------------------------------------------------------------------------------
//MARCAR TODOS OS CAMPOS DO MÓDULO DO FORMULÁRIO DE USUÁRIOS
function marcar(obj) {
  if ($(obj).attr("checked")) {
    $(obj).parents('div#div_objetos').find('input').each(function (i, acao) {
      $(acao).attr('checked', 'checked');
      $(acao).prop("checked", true);
    });
  } else {
    $(obj).parents('div#div_objetos').find('input').each(function (i, acao) {
      $(acao).removeAttr('checked');
      $(acao).prop("checked", false);
    });
  }
}
//---------------------------------------------------------------------------------------------------------
$('div#check').livequery('change', function () {

  if ($(this).find('.checkbox').find('input[type="checkbox"]').attr('checked') != 'checked') {
    $(this).find('.checkbox').find('input[type="checkbox"]').attr('checked', 'checked');
    $(this).find('.checkbox').find('input[type="checkbox"]').prop("checked", true);
  } else {
    $(this).find('.checkbox').find('input[type="checkbox"]').removeAttr('checked');
    $(this).find('.checkbox').find('input[type="checkbox"]').prop("checked", false);
  }

  if ($(this).find('.checkbox').find('input[type="checkbox"]').attr('checked') != 'checked') {

    var rel = $(this).find('input[type="checkbox"]').attr('rel');
    $('#todos_' + rel).removeAttr('checked');
    $('#todos_' + rel).prop("checked", false);
  } else {

    var modulo_id = $(this).find('input[type="checkbox"]').attr('rel');
    var checado = true;

    $(this).parents('div#div_objetos').find('input[rel="' + modulo_id + '"]').each(function (key, value) {
      if ($(value).attr('checked') != 'checked' && ($(value).attr("id") != ('todos_' + modulo_id)) && (!($(value).hasClass("grupo_check")))) {
        checado = false;
      }
    });

    if (checado) {
      $('#todos_' + modulo_id).attr('checked', 'checked');
      $('#todos_' + modulo_id).prop("checked", true);
    }
  }
});
//---------------------------------------------------------------------------------------------------------
//CHECK PARA MARCAR TODOS OU REMOVER TODOS
$('div#grupo_todos').livequery('change', function () {
  if ($(this).find('.checkbox').find('input[type="checkbox"]').attr('checked') != 'checked') {
    $(this).parents('div#div_objetos').find('input[type="checkbox"]').each(function (key, value) {
      $(value).attr('checked', 'checked');
      $(value).prop("checked", true);
    });
  } else {
    $(this).parents('div#div_objetos').find('input[type="checkbox"]').each(function (key, value) {
      $(value).removeAttr('checked');
      $(value).prop("checked", false);
    });
  }
});
//---------------------------------------------------------------------------------------------------------
//VERIFICA SE TODOS OS CHECKS ESTÃO MARCADOS, CASO ESTEJAM ENTÃO MARCA O CHECK TODOS
function verificar_checks() {

  var vf_checks = true;

  $("div#div_objetos").each(function () {

    vf_checks = true;

    $(this).find('div#check').find('input[type="checkbox"]').each(function () {
      if ($(this).attr('checked') != 'checked') {
        vf_checks = false;
      }
    });

    if (vf_checks == true) {
      $(this).parents('div').find('#div_objetos').find('input[type="checkbox"]').attr('checked', 'checked');
    }
  });
}
//---------------------------------------------------------------------------------------------------------

