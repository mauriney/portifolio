/*---------------------------------------------------------------------------------------------------------
 DATA: 15/08/2016 ÀS 11:09
 NOME: JS DA CLASSE CADASTRO DE CANDIDATO ETAPA 2
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//--------------------------------------------------------------------------------------------------------- 
//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");

  $("#anterior").click(function () {
    $("#caminho_pagina").val('etapa1');
  });

  $("#finalizar").click(function () {
    if ($("input#retroativo").val() == 1) {
      $("#caminho_pagina").val('lista_morto');
    } else {
      $("#caminho_pagina").val('lista');
    }
  });

  $("#proxima").click(function () {
    $("#caminho_pagina").val('etapa3');
  });

  //---------------------------------------------------------------------------------------------------------
//CÔNJUGE SIM
  $("#conjuge_sim").livequery('change', function () {

    var conjuge_id = $("input#conjuge_id").val();

    if ($(this).val() == 1) {
      $("#div_conjuge").show();
      $("#div_possui").hide();
    } else {
      $("#div_conjuge").hide();
      if (conjuge_id > 0) {
        $("#div_possui").show();
      } else {
        $("#div_possui").hide();
      }
    }
  });

//---------------------------------------------------------------------------------------------------------
//CÔNJUGE NÃO
  $("#conjuge_nao").livequery('change', function () {

    var conjuge_id = $("input#conjuge_id").val();

    if ($(this).val() == 0) {
      $("#div_conjuge").hide();
      if (conjuge_id > 0) {
        $("#div_possui").show();
      } else {
        $("#div_possui").hide();
      }
    } else {
      $("#div_conjuge").show();
      $("#div_possui").hide();
    }
  });

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE CANDIDATO ETAPA 2
  $('form#form_candidato_etapa2').submit(function () {

    $("#div_loader").show();

    var vf_validacao = true;

    if ($("input[id='conjuge_sim']:checked").val() == 1) {
      vf_validacao = recuperar_validator();

    } else {
      vf_validacao = recuperar_validator_2();
    }

    if (vf_validacao) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/candidato/etapa2',
        data: $('#form_candidato_etapa2').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Candidato",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/" + $("#caminho_pagina").val() + "/" + obj.id + "'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Candidato",
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
            title: "Formulário de Candidato",
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
});
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO CANDIDATO
function recuperar_validator() {
  var valido = true;
  var nome = $("#nome").val();
  var cpf = $("#cpf").val();
  var cor = $("#cor").val();
  var cep = $("#cep").val();
  var estado = $("#estado").val();
  var municipio = $("#municipio").val();
  var logradouro = $("#logradouro").val();
  var numero = $("#numero").val();
  var bairro = $("#bairro").val();
  var quadra = $("#quadra").val();
  var casa = $("#casa").val();
  var complemento = $("#complemento").val();
  var residencial = $("#residencial").val();
  var celular = $("#celular").val();
  var comercial = $("#comercial").val();
  var ramal = $("#ramal").val();
  var contato_tipo = $("#contato_tipo").val();
  var contato_nome = $("#contato_nome").val();
  var contato_telefone = $("#contato_telefone").val();
  var contato_email = $("#contato_email").val();
  var data_nascimento = $("#data_nascimento").val();
  var documento1 = $("#documento1").val();
  var orgao_expedidor = $("#orgao_expedidor").val();
  var uf_expedicao = $("#uf_expedicao").val();
  var data_expedicao = $("#data_expedicao").val();
  var numero_registro = $("#numero_registro").val();
  var numero_registro_2 = $("#numero_registro_2").val();
  var uf_expedicao_2 = $("#uf_expedicao_2").val();
  var data_expedicao_2 = $("#data_expedicao_2").val();
  var data_validade_2 = $("#data_validade_2").val();
  var nacionalidade_brasileiro = $("input[id='nacionalidade_brasileiro']:checked").val();
  var nacionalidade_estrangeiro = $("input[id='nacionalidade_estrangeiro']:checked").val();
  var naturalidade_estado = $("#naturalidade_estado").val();
  var naturalidade_municipio = $("#naturalidade_municipio").val();
  var pais = $("#pais").val();
  var cod_rne = $("#cod_rne").val();
  var classificacao = $("#classificacao").val();
  var data_expedicao_1 = $("#data_expedicao_1").val();
  var validade = $("#validade").val();
  var trabalha_sim = $("input[id='trabalha_sim']:checked").val();
  var local_trabalho = $("#local_trabalho").val();
  var trab_endereco = $("#trab_endereco").val();
  var cargo_funcao = $("#cargo_funcao").val();
  var data_inicio = $("#data_inicio").val();
  var tempo_servico = $("#tempo_servico").val();
  var estudando_sim = $("input[id='estudando_sim']:checked").val();
  var nome_instituicao = $("#nome_instituicao").val();
  var serie_periodo = $("#serie_periodo").val();
  var estado_civil = $("#estado_civil").val();
  var grau_escolar = $("#grau_escolar").val();
  var rede_publica_privada = $("#rede_publica_privada").val();
  var financia_nao = $("input[id='financia_nao']:checked").val();
  var programa_social = $("#programa_social").val();
  var porcentagem = $("#porcentagem").val();
  var cad_unico = $("#cad_unico").val();
  var nis = $("#nis").val();
  var capitulo = $("#capitulo").val();
  var grupo = $("#grupo").val();
  var mora_sim = $("input[id='mora_sim']:checked").val();
  var vinculo_sim = $("input[id='vinculo_sim']:checked").val();
  var vinculo_nome = $("#vinculo_nome").val();
  var trabalha_mesmo_endereco = $("input[id='trab_mesmo_endereco_nao']:checked").val();

  var element = null;

  //LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  if (vinculo_sim == 1) {
    if (vinculo_nome == "") {
      $('div#div_vinculo_nome').after('<label id="erro_vinculo_nome" class="error">Nome é obrigatório.</label>');
      valido = false;
      element = $('div#div_vinculo_nome');
    }
  }

  if (nis == "") {
    $('div#div_nis').after('<label id="erro_nis" class="error">Nis é obrigatório.</label>');
    valido = false;
    element = $('div#div_nis');
  }

  if (cad_unico == "") {
    $('div#div_cad_unico').after('<label id="erro_cad_unico" class="error">Cad. único é obrigatório.</label>');
    valido = false;
    element = $('div#div_cad_unico');
  }

//  if (categoria == "") {
//    $('button[data-id="categoria"]').after('<label id="erro_categoria" class="error">Categoria é obrigatório.</label>');
//    valido = false;
//    element = $('button[data-id="categoria"]');
//  }

//  if (grupo == "") {
//    $('button[data-id="grupo"]').after('<label id="erro_grupo" class="error">Grupo é obrigatório.</label>');
//    valido = false;
//    element = $('button[data-id="grupo"]');
//  }
//
//  if (capitulo == "") {
//    $('button[data-id="capitulo"]').after('<label id="erro_capitulo" class="error">Capítulo é obrigatório.</label>');
//    valido = false;
//    element = $('button[data-id="capitulo"]');
//  }

  if (estudando_sim == 1) {//ESTUDANDO SIM
    if (rede_publica_privada == 2) {//REDE PRIVADA
      if (financia_nao == 2) {//NÃO FINANCIA

        if (porcentagem == "") {
          $('div#div_porcentagem').after('<label id="erro_porcentagem" class="error">Porcentagem é obrigatório.</label>');
          valido = false;
          element = $('div#div_porcentagem');
        }

        if (programa_social == "") {
          $('div#div_programa_social').after('<label id="erro_programa_social" class="error">Programa social é obrigatório.</label>');
          valido = false;
          element = $('div#div_programa_social');
        }
      }
    }

    if (rede_publica_privada == "") {
      $('button[data-id="rede_publica_privada"]').after('<label id="erro_rede_publica_privada" class="error">Natureza da instituição é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="rede_publica_privada"]');
    }

    if (serie_periodo == "") {
      $('div#div_serie_periodo').after('<label id="erro_serie_periodo" class="error">Série/Período é obrigatório.</label>');
      valido = false;
      element = $('div#div_serie_periodo');
    }

    if (nome_instituicao == "") {
      $('div#div_nome_instituicao').after('<label id="erro_nome_instituicao" class="error">Nome da insituição é obrigatório.</label>');
      valido = false;
      element = $('div#div_nome_instituicao');
    }

  }

  if (grau_escolar == "") {
    $('button[data-id="grau_escolar"]').after('<label id="erro_grau_escolar" class="error">Grau escolar é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="grau_escolar"]');
  }

  if (trabalha_sim == 1) {

    if (trabalha_mesmo_endereco == 0) {
      if (local_trabalho == "") {
        $('div#div_local_trabalho').after('<label id="erro_local_trabalho" class="error">Local de trabalho é obrigatório.</label>');
        valido = false;
        element = $('div#div_local_trabalho');
      }

      if (trab_endereco == "") {
        $('div#div_trab_endereco').after('<label id="erro_trab_endereco" class="error">Endereço de trabalho é obrigatório.</label>');
        valido = false;
        element = $('div#div_trab_endereco');
      }
    }

    if (cargo_funcao == "") {
      $('div#div_cargo_funcao').after('<label id="erro_cargo_funcao" class="error">Cargo/Função é obrigatório.</label>');
      valido = false;
      element = $('div#div_cargo_funcao');
    }

    if (data_inicio == "") {
      $('div#div_data_inicio').after('<label id="erro_data_inicio" class="error">Data de inicío é obrigatório.</label>');
      valido = false;
      element = $('div#div_data_inicio');
    }

  }

//  if (contato_telefone == "") {
//    $('div#div_contato_telefone').after('<label id="erro_contato_telefone" class="error">Telefone do contato é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_contato_telefone');
//  }
//
//  if (contato_nome == "") {
//    $('div#div_contato_nome').after('<label id="erro_contato_nome" class="error">Nome do contato é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_contato_nome');
//  }
//
//  if (contato_tipo == "") {
//    $('div#div_contato_tipo').after('<label id="erro_contato_tipo" class="error">Tipo do contato é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_contato_tipo');
//  }
//  if (ramal == "") {
//    $('div#div_ramal').after('<label id="erro_ramal" class="error">Ramal é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_ramal');
//  }
//
//  if (comercial == "") {
//    $('div#div_comercial').after('<label id="erro_comercial" class="error">Comercial é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_comercial');
//  }
//
//  if (celular == "") {
//    $('div#div_celular').after('<label id="erro_celular" class="error">Celular é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_celular');
//  }
//
//  if (residencial == "") {
//    $('div#div_residencial').after('<label id="erro_residencial" class="error">Residencial é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_residencial');
//  }
//
//  if (contato_email == "") {
//    $('div#div_contato_email').after('<label id="erro_contato_email" class="error">E-mail do contato é obrigatório.</label>');
//    valido = false;
//    element = $('div#div_contato_email');
//  }

  if (mora_sim != 1) {
//    if (complemento == "") {
//      $('div#div_complemento').after('<label id="erro_complemento" class="error">O campo complemento é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_complemento');
//    }
//
//    if (casa == "") {
//      $('div#div_casa').after('<label id="erro_casa" class="error">Casa é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_casa');
//    }
//
//    if (quadra == "") {
//      $('div#div_quadra').after('<label id="erro_quadra" class="error">Quadra é obrigatório.</label>');
//      valido = false;
//      element = $('div#div_quadra');
//    }


    if (bairro == "") {
      $('div#div_bairro').after('<label id="erro_bairro" class="error">Bairro é obrigatório.</label>');
      valido = false;
      element = $('div#div_bairro');
    }

    if (numero == "") {
      $('div#div_numero').after('<label id="erro_numero" class="error">Número é obrigatório.</label>');
      valido = false;
      element = $('div#div_numero');
    }

    if (logradouro == "") {
      $('div#div_logradouro').after('<label id="erro_logradouro" class="error">Logradouro é obrigatório.</label>');
      valido = false;
      element = $('div#div_logradouro');
    }

    if (municipio == "") {
      $('button[data-id="municipio"]').after('<label id="erro_municipio" class="error">Município é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="municipio"]');

    }

    if (estado == "") {
      $('button[data-id="estado"]').after('<label id="erro_estado" class="error">Estado é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="estado"]');
    }

    if (cep == "") {
      $('div#div_cep').after('<label id="erro_cep" class="error">Cep é obrigatório.</label>');
      valido = false;
      element = $('div#div_cep');
    }
  }

  if (estado_civil == "") {
    $('button[data-id="estado_civil"]').after('<label id="erro_estado_civil" class="error">Estado civil é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="estado_civil"]');
  }

  if (nacionalidade_estrangeiro == 0) {

    if (validade == "") {
      $('div#div_validade').after('<label id="erro_validade" class="error">Validade é obrigatório.</label>');
      valido = false;
      element = $('div#div_validade');
    }

    if (data_expedicao_1 == "") {
      $('div#div_data_expedicao_1').after('<label id="erro_data_expedicao_1" class="error">Data é obrigatório.</label>');
      valido = false;
      element = $('div#div_data_expedicao_1');
    }

    if (classificacao == "") {
      $('div#div_classificacao').after('<label id="erro_classificacao" class="error">Classificação é obrigatório.</label>');
      valido = false;
      element = $('div#div_classificacao');
    }

    if (cod_rne == "") {
      $('div#div_cod_rne').after('<label id="erro_cod_rne" class="error">Cód. Rne é obrigatório.</label>');
      valido = false;
      element = $('div#div_cod_rne');
    }

    if (pais == "") {
      $('button[data-id="pais"]').after('<label id="erro_pais" class="error">Nacionalidade é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="pais"]');
    }

  } else if (nacionalidade_brasileiro == 1) {

    if (naturalidade_municipio == "") {
      $('button[data-id="naturalidade_municipio"]').after('<label id="erro_naturalidade_municipio" class="error">Município é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="naturalidade_municipio"]');
    }

    if (naturalidade_estado == "") {
      $('button[data-id="naturalidade_estado"]').after('<label id="naturalidade_estado" class="error">Estado é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="naturalidade_estado"]');
    }

  }

  if (documento1 == "") {
    $('button[data-id="documento1"]').after('<label id="erro_nome" class="error space-b-20">É necessário escolher um tipo de documento.</label>');
    valido = false;
    element = $('button[data-id="documento1"]');
  } else {

    if (documento1 != "") {

      if (documento1 == 1) {

        if (data_expedicao == "") {
          $('div#div_data_expedicao').after('<label id="erro_data_expedicao" class="error">Data de expedição é obrigatório.</label>');
          valido = false;
          element = $('div#div_data_expedicao');
        }

        if (uf_expedicao == "") {
          $('button[data-id="uf_expedicao"]').after('<label id="erro_uf_expedicao" class="error">UF de expedição é obrigatório.</label>');
          valido = false;
          element = $('button[data-id="uf_expedicao"]');
        }

        if (orgao_expedidor == "") {
          $('button[data-id="orgao_expedidor"]').after('<label id="erro_orgao_expedidor" class="error">Órgão expedidor é obrigatório.</label>');
          valido = false;
          element = $('button[data-id="orgao_expedidor"]');
        }

        if (numero_registro == "") {
          $('div#div_numero_registro').after('<label id="erro_numero_registro" class="error">Número de registro é obrigatório.</label>');
          valido = false;
          element = $('div#div_numero_registro');
        }

      } else {
        if (data_validade_2 == "") {
          $('div#div_data_validade_2').after('<label id="erro_data_validade_2" class="error">Validade é obrigatório.</label>');
          valido = false;
          element = $('div#div_data_validade_2');
        }

        if (data_expedicao_2 == "") {
          $('div#div_data_expedicao_2').after('<label id="erro_data_expedicao_2" class="error">Data de expedição é obrigatório.</label>');
          valido = false;
          element = $('div#div_data_expedicao_2');
        }

        if (uf_expedicao_2 == "") {
          $('button[data-id="uf_expedicao_2"]').after('<label id="erro_uf_expedicao_2" class="error">UF de expedição é obrigatório.</label>');
          valido = false;
          element = $('button[data-id="uf_expedicao_2"]');
        }

        if (numero_registro_2 == "") {
          $('div#div_numero_registro_2').after('<label id="erro_numero_registro_2" class="error">Número de registro é obrigatório.</label>');
          valido = false;
          element = $('div#div_numero_registro_2');
        }
      }

    }
  }

  if (data_nascimento == "") {
    $('div#div_data_nascimento').after('<label id="erro_data_nascimento" class="error">Data é obrigatório.</label>');
    valido = false;
    element = $('div#div_data_nascimento');
  }

  if (cor == "") {
    $('button[data-id="cor"]').after('<label id="erro_cor" class="error space-b-20">Cor/Raça é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="cor"]');
  }

  if (nome == "") {
    $('div#div_nome').after('<label id="erro_nome" class="error space-b-20">Nome é obrigatório.</label>');
    valido = false;
    element = $('div#div_nome');
  }

  if (cpf == "") {
    $('div#div_cpf').after('<label id="erro_cpf" class="error space-b-20">CPF é obrigatório.</label>');
    valido = false;
    element = $('div#div_cpf');
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
// VALIDAÇÃO DO SEM CÔNJUGE
function recuperar_validator_2() {
  var valido = true;
  var conjuge_id = $("input#conjuge_id").val();
  var observacao = $("#observacao").val();

  var element = null;

  // LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  if (observacao == "" && conjuge_id > 0) {
    $('div#div_observacao').after('<label id="erro_observacao" class="error space-b-20">O campo observação é obrigatório.</label>');
    valido = false;
    element = $('div#div_observacao');
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
//ESTADO E MUNICÍPIO DO ENDEREÇO
$("#estado").change(function () {
  $("#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php",
          {estado: $(this).val()},
  function (valor) {
    $("#municipio").html(valor);
    $('#municipio').selectpicker('refresh');
  });
});

//---------------------------------------------------------------------------------------------------------
//ESTADO E MUNICÍPIO DATA NATURALIDADE
$("#naturalidade_estado").change(function () {
  $("#naturalidade_municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php",
          {estado: $(this).val()},
  function (valor) {
    $("#naturalidade_municipio").html(valor);
    $('#naturalidade_municipio').selectpicker('refresh');
  });
});

//---------------------------------------------------------------------------------------------------------
//RETROATIVO SIM
$("#retroativo_sim").click(function () {
  if ($("input[id='retroativo_sim']:checked").val() == 1) {
    recuperar_validator_retroativo();
  }
});

//---------------------------------------------------------------------------------------------------------
//RETROATIVO NÃO
$("#retroativo_nao").click(function () {
  if ($("input[id='retroativo_nao']:checked").val() == 0) {
    recuperar_validator();
  }
});

//---------------------------------------------------------------------------------------------------------
//PRÓPRIOS MEIOS SIM
$("#financia_sim").change(function () {
  $('#programa_social').selectpicker('deselectAll');
  $('#programa_social').change();
  $('#porcentagem').val('');
  if ($(this).val() == 1) {
    $("#proprios_meios").hide();
  } else {
    $("#proprios_meios").show();
  }
});
//---------------------------------------------------------------------------------------------------------
//PRÓPRIOS MEIOS NÃO
$("#financia_nao").change(function () {
  $('#programa_social').selectpicker('deselectAll');
  $('#programa_social').change();
  $('#porcentagem').val('');
  if ($(this).val() == 1) {
    $("#proprios_meios").hide();
  } else {
    $("#proprios_meios").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//REDE PÚBLICA OU PRIVADA
$("select#rede_publica_privada").change(function () {
  if ($(this).val() == 2) {
    $("#natureza_instituicao").show();
  } else {
    $("#natureza_instituicao").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA SIM
$("#trabalha_sim").change(function () {
  if ($(this).val() == 1) {
    $("#trabalha_sim_nao_mesmo_endereco").show();
    $("#trabalha_sim_nao").show();
  } else {
    $("#trabalha_sim_nao").hide();
    $("#trabalha_sim_nao_mesmo_endereco").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NÃO
$("#trabalha_nao").change(function () {
  if ($(this).val() == 0) {
    $("#trabalha_sim_nao").hide();
    $("#trabalha_sim_nao_mesmo_endereco").hide();
  } else {
    $("#trabalha_sim_nao").show();
    $("#trabalha_sim_nao_mesmo_endereco").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NO MESMO ENDEREÇO SIM
$("#trab_mesmo_endereco_sim").change(function () {
  if ($(this).val() == 1) {
    $("#trab_mesmo_endereco_sim_nao").hide();
  } else {
    $("#trab_mesmo_endereco_sim_nao").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NO MESMO ENDEREÇO NÃO
$("#trab_mesmo_endereco_nao").change(function () {
  if ($(this).val() == 0) {
    $("#trab_mesmo_endereco_sim_nao").show();
  } else {
    $("#trab_mesmo_endereco_sim_nao").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//ESTUDANDO SIM
$("#estudando_sim").change(function () {

  $('#nome_instituicao').val('');
  $('#serie_periodo').val('');
  $('#rede_publica_privada').selectpicker('deselectAll');
  $('#rede_publica_privada').change();
  $('#programa').val('');
  $('#porcentagem').val('');

  if ($(this).val() == 1) {
    $("#estiver_estudando").show();
  } else {
    $("#estiver_estudando").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//ESTUDANDO NÃO
$("#estudando_nao").change(function () {

  $('#nome_instituicao').val('');
  $('#serie_periodo').val('');
  $('#rede_publica_privada').selectpicker('deselectAll');
  $('#rede_publica_privada').change();
  $('#programa').val('');
  $('#porcentagem').val('');

  if ($(this).val() == 0) {
    $("#estiver_estudando").hide();
  } else {
    $("#estiver_estudando").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//VÍNCULO SIM
$("#vinculo_sim").change(function () {
  if ($(this).val() == 1) {
    $("#possuir_vinculo").show();
  } else {
    $("#possuir_vinculo").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//VÍNCULO NÃO
$("#vinculo_nao").change(function () {
  if ($(this).val() == 0) {
    $("#possuir_vinculo").hide();
  } else {
    $("#possuir_vinculo").show();
  }
});
//---------------------------------------------------------------------------------------------------------
//MORA NO MESMO ENDEREÇO SIM
$("#mora_sim").change(function () {

  $('#cep').val('');
  $('#estado').selectpicker('deselectAll');
  $('#estado').change();
  $('#municipio').selectpicker('deselectAll');
  $('#municipio').change();
  $('#logradouro').val('');
  $('#numero').val('');
  $('#bairro').val('');
  $('#quadra').val('');
  $('#casa').val('');
  $('#complemento').val('');

  if ($(this).val() == 1) {
    $("#mesmo_endereco").hide();
  } else {
    $("#mesmo_endereco").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//MORA NO MESMO ENDEREÇO NÃO
$("#mora_nao").change(function () {

  $('#cep').val('');
  $('#estado').selectpicker('deselectAll');
  $('#estado').change();
  $('#municipio').selectpicker('deselectAll');
  $('#municipio').change();
  $('#logradouro').val('');
  $('#numero').val('');
  $('#bairro').val('');
  $('#quadra').val('');
  $('#casa').val('');
  $('#complemento').val('');

  if ($(this).val() == 0) {
    $("#mesmo_endereco").show();
  } else {
    $("#mesmo_endereco").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//NACIONALIDADE ESTRANGEIRO
$("#nacionalidade_estrangeiro").change(function () {

  if ($(this).val() == 0) {
    $("#estrangeiro").show();
    $("#brasileiro").hide();
    $('#naturalidade_estado').selectpicker('deselectAll');
    $('#naturalidade_estado').change();
    $('#naturalidade_municipio').selectpicker('deselectAll');
    $('#naturalidade_municipio').change();
  } else {
    $("#estrangeiro").hide();
    $("#brasileiro").show();
    $("#data_expedicao_1").val('');
    $("#validade").val('');
    $("#cod_rne").val('');
    $('#classificacao').selectpicker('deselectAll');
    $('#classificacao').change();
    $('#pais').selectpicker('deselectAll');
    $('#pais').change();
  }

});

//---------------------------------------------------------------------------------------------------------
//NACIONALIDADE BRASILEIRO
$("#nacionalidade_brasileiro").change(function () {

  if ($(this).val() == 1) {
    $("#estrangeiro").hide();
    $("#brasileiro").show();
    $("#data_expedicao_1").val('');
    $("#validade").val('');
    $("#cod_rne").val('');
    $('#classificacao').selectpicker('deselectAll');
    $('#classificacao').change();
    $('#pais').selectpicker('deselectAll');
    $('#pais').change();
  } else {
    $("#estrangeiro").show();
    $("#brasileiro").hide();
    $('#naturalidade_estado').selectpicker('deselectAll');
    $('#naturalidade_estado').change();
    $('#naturalidade_municipio').selectpicker('deselectAll');
    $('#naturalidade_municipio').change();
  }

});

//---------------------------------------------------------------------------------------------------------
//DOCUMENTO 1
$("#documento1").change(function () {

  if ($(this).val() == 1) {
    $("#documento_1_1").show();
    $("#numero_registro_2").val('');
    $("#uf_expedicao_2").selectpicker('deselectAll');
    $('#uf_expedicao_2').change();
    $("#data_expedicao_2").val('');
    $("#data_validade_2").val('');
    $("#documento_1_2").hide();
  } else if ($(this).val() == 2) {
    $("#documento_1_1").hide();
    $("#numero_registro").val('');
    $("#orgao_expedidor").selectpicker('deselectAll');
    $('#orgao_expedidor').change();
    $("#uf_expedicao").selectpicker('deselectAll');
    $('#uf_expedicao').change();
    $("#data_expedicao").val('');
    $("#documento_1_2").show();
  } else {
    $("#documento_1_1").hide();
    $("#documento_1_2").hide();
    $("#numero_registro").val('');
    $("#orgao_expedidor").selectpicker('deselectAll');
    $('#orgao_expedidor').change();
    $("#uf_expedicao").selectpicker('deselectAll');
    $('#uf_expedicao').change();
    $("#data_expedicao").val('');
    $("#numero_registro_2").val('');
    $("#uf_expedicao_2").selectpicker('deselectAll');
    $('#uf_expedicao_2').change();
    $("#data_expedicao_2").val('');
    $("#data_validade_2").val('');
  }

});

//---------------------------------------------------------------------------------------------------------
//BUSCA AUTOCOMPLETE
function busca_autocomplete() {

//  $("#nome").autocomplete(PORTAL_URL + "hab/dao/basico/autocomplete/pessoas.php", {
//    width: 260,
//    matchContains: true,
//    selectFirst: false
//  });

}
//---------------------------------------------------------------------------------------------------------
//BUSCA AUTOCOMPLETE BAIRRO
function bairro_autocomplete() {

  $("#bairro").autocomplete(PORTAL_URL + "hab/dao/basico/autocomplete/bairros.php", {
    width: 260,
    matchContains: true,
    selectFirst: false
  });

}
//---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA CARREGAR ENDEREÇOS AUTOMÁTICO PELO CEP DIGITADO
function consultacep() {

  var cep = $("#cep").val();

  if (cep.replace(/[^\d]+/g, '').length == 8) {
    //$("#div_loader").show();
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
    $('#bairro').val(obj.bairro).trigger('change');
    $('#logradouro').val(obj.logradouro).trigger('change');

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

    if (carrega_municipio(obj.cidade)) {
      $('#municipio').change();
      //$("#div_loader").hide();
    }
  }
  //$("#div_loader").hide();
  return false;
}
//---------------------------------------------------------------------------------------------------------
//SE A CONSULTA DER ERRADO
function onErrorConsultaCep(args) {
  $.prompt('Cep não encontrado.');
  //$("#div_loader").hide();
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
      for (; i < e.options.length; i++) {

        $('#municipio').change();

        if (e.options[i].label == municipio)
        {
          var val = e.options[i].value;
          $('#municipio').val(val).trigger('change');
          break;
        }
      }
    }
    return true;
  });
}
//---------------------------------------------------------------------------------------------------------

$("#capitulo").change(function () {
  $("#grupo").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/grupos.php",
          {catinic1: $(this).find('option:selected').attr('catinic1'), catfim1: $(this).find('option:selected').attr('catfim1')},
  function (valor) {
    $("#grupo").html(valor);
    $('#grupo').selectpicker('refresh');
  });
});

$("#grupo").change(function () {
  $("#categoria").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/categorias.php",
          {catinic2: $(this).find('option:selected').attr('catinic2'), catfim2: $(this).find('option:selected').attr('catfim2')},
  function (valor) {
    $("#categoria").html(valor);
    $('#categoria').selectpicker('refresh');
  });
});
//--------------------------------------------------------------------------------------------------------- 
//ADD MÁSCARA DE DINHEIRO
$("input#renda_valor").mask("###.###.###.##0,00", {reverse: true});
//--------------------------------------------------------------------------------------------------------- 
$("a#add_renda").livequery("click", function () {
  var clone = $(this).parents("div#clonar").clone();

  $(this).parents("div#clonar").after(clone);

  $(this).parents("div#clonar").next().find("input#renda_valor").val("");
  $(this).parents("div#clonar").next().find("input#renda_valor").mask("###.###.###.##0,00", {reverse: true});
  $(this).parents("div#clonar").next().find("a#remover_renda").show();

  //REMOVENDO SELECTS E ATUALIZANDO NOVAMENTE
  $(this).parents("div#clonar").next().find('div.bootstrap-select').remove();
  $(this).parents("div#clonar").next().find('select').val("");
  $(this).parents("div#clonar").next().find('select').selectpicker();

});
//--------------------------------------------------------------------------------------------------------- 
$("a#remover_renda").livequery("click", function () {
  $(this).parents("div#clonar").remove();
});
//--------------------------------------------------------------------------------------------------------- 