/*---------------------------------------------------------------------------------------------------------
 DATA: 10/08/2016 ÀS 11:09
 NOME: JS DA CLASSE CADASTRO DE CANDIDATO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
  // ----------------------------------------------------------------------------------------------------------
  $("div.prev").hide();
  // ----------------------------------------------------------------------------------------------------------
  // Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");

  $("#finalizar").click(function () {
    $("#caminho_pagina").val('lista');
  });

  $("#proxima").click(function () {
    $("#caminho_pagina").val('etapa2');
  });

  // ---------------------------------------------------------------------------------------------------------
  // CADASTRO DE CANDIDATO ETAPA 1
  $('form#form_candidato').submit(function () {

    $("#div_loader").show();

    var vf = true;

    if ($("input#retroativo").val() == 1) {
      vf = recuperar_validator_retroativo();
    } else {
      vf = recuperar_validator();
    }

    if (vf) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/candidato/etapa1',
        data: $('#form_candidato').serialize(),
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

              if ($("#caminho_pagina").val() == "lista") {
                if ($("input#retroativo").val() == 1) {
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/lista_morto'", 1);
                } else {
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/lista'", 1);
                }
              } else {
                // SE FOR UNIÃO ESTAVEL OU CASADO VAI PARA ETAPA 2 DO CÔNJUGE
                if ($("input[id='uniao_estavel']:checked").val() == 1 || $("#estado_civil").val() == 5 || $("#estado_civil").val() == 6 || $("#estado_civil").val() == 7 || $("#estado_civil").val() == 8) {
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa2/" + obj.id + "'", 1);
                } else {// SE NÃO PULA A ETAPA DO CÔNJUGE E VAI PARA ETAPA 3 DO FAMILIAR
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa3/" + obj.id + "'", 1);
                }
              }

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
  // ---------------------------------------------------------------------------------------------------------
  // PROGRAMA E SUBPROGRAMA
  $("#programa").change(function () {
    $("#subprograma").html('<option value="0">Carregando...</option>');
    $.post(PORTAL_URL + "hab/dao/basico/combo/subprograma.php", {
      programa: $(this).val()
    }, function (valor) {
      $("#subprograma").html(valor);
      $('#subprograma').selectpicker('refresh');
    });
  });
});
// ---------------------------------------------------------------------------------------------------------
// MENSAGEM DE USUÁRIO UTILIZANDO A MESMA PÁGINA
if ($('input#vf_usuario_pagina').val() > 0) {

  var nome_usuario = $("#nome_usuario").val();

  Messenger().post({
    message: nome_usuario + ' já está realizando atualizações nesta página, deseja mesmo continuar?<br/><br/><center><button onclick="continuar()" id="continuar" class="btn btn-primary">Sim</button>&nbsp;&nbsp;&nbsp;<button onclick="sair()" id="sair" class="btn btn-danger">Não</button></center>',
    type: 'success',
    showCloseButton: true
  });
}

function continuar() {
  $("li.messenger-message-slot").toggle('slow');
}

function sair() {
  window.history.back();
}

// ---------------------------------------------------------------------------------------------------------
// VALIDAÇÃO DO CANDIDATO
function recuperar_validator() {
  var valido = true;
  var nome = $("#nome").val();
  var cpf = $("#cpf").val();
  var cor = $("#cor").val();
  var cep = $("#cep").val();
  var estado = $("#estado").val();
  var municipio = $("#municipio").val();
  var logradouro = $("#logradouro").val();
  var tipo_logradouro = $("#tipo_logradouro").val();
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
  var programa = $("#programa").val();
  var subprograma = $("#subprograma").val();
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
  var estudando_sim = $("input[id='estudando_sim']:checked").val();
  var nome_instituicao = $("#nome_instituicao").val();
  var serie_periodo = $("#serie_periodo").val();
  var grau_escolar = $("#grau_escolar").val();
  var rede_publica_privada = $("#rede_publica_privada").val();
  var financia_nao = $("input[id='financia_nao']:checked").val();
  var programa_social = $("#programa_social").val();
  var porcentagem = $("#porcentagem").val();
  var cad_unico = $("#cad_unico").val();
  var nis = $("#nis").val();
  var capitulo = $("#capitulo").val();
  var grupo = $("#grupo").val();
  var mae_nome = $("#mae_nome").val();
  var mae_nascimento = $("#mae_nascimento").val();
  var mae_contato = $("#mae_contato").val();
  var data_inicio_residencia = $("#data_inicio_residencia").val();
  var benefecio_social = $("#benefecio_social").val();
  var estado_civil = $("#estado_civil").val();
  var casamento_data = $("#casamento_data").val();
  var deficiencia_sim = $("input[id='deficiencia_sim']:checked").val();
  var tipo_deficiencia = $("#tipo_deficiencia").val();
  var tipo_endereco = $("#tipo_endereco").val();
  var alugada_sim = $("input[id='alugada_sim']:checked").val();
  var valor_aluguel = $("#valor_aluguel").val();
  var trabalha_mesmo_endereco = $("input[id='trab_mesmo_endereco_nao']:checked").val();
  var pereferencia_empreendimento = $("input[id='pereferencia_empreendimento_sim']:checked").val();
  var empreendimento = $("#empreendimento").val();
  var loteamento = $("#loteamento_id").val();
  var participar = $("input[id='participar_sim']:checked").val();
  var participar_id = $("#participar_id").val();
  var vinculo_sim = $("input[id='vinculo_sim']:checked").val();
  var vinculo_nome = $("#vinculo_nome").val();
  var trabalha_mesmo_endereco = $("input[id='trab_mesmo_endereco_nao']:checked").val();

  var element = null;

  // LIMPA MENSAGENS DE ERRO
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

  if (participar == 1) {
    if (participar_id == "") {
      $('button[data-id="participar_id"]').after('<label id="erro_participar_id" class="error">O programa é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="participar_id"]');
    }
  }

  if (pereferencia_empreendimento == 1) {
    if (empreendimento == "") {
      $('button[data-id="empreendimento"]').after('<label id="erro_empreendimento" class="error">O programa é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="empreendimento"]');
    } else if (empreendimento > 1 && loteamento == "") {
      $('button[data-id="loteamento_id"]').after('<label id="erro_loteamento_id" class="error">O loteamento é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="loteamento_id"]');
    }
  }

  if (benefecio_social == "") {
    $('button[data-id="benefecio_social"]').after('<label id="erro_benefecio_social" class="error">Benefício social é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="benefecio_social"]');
  }

  if (nis == "") {
    $('div#div_nis').after('<label id="erro_nis" class="error">NIS é obrigatório.</label>');
    valido = false;
    element = $('div#div_nis');
  }

  if (cad_unico == "") {
    $('div#div_cad_unico').after('<label id="erro_cad_unico" class="error">Cad. único é obrigatório.</label>');
    valido = false;
    element = $('div#div_cad_unico');
  }

  // if (categoria == "") {
  // $('button[data-id="categoria"]').after('<label id="erro_categoria" class="error">Categoria é obrigatório.</label>');
  // valido = false;
  // element = $('button[data-id="categoria"]');
  // }

  // if (grupo == "") {
  // $('button[data-id="grupo"]').after('<label id="erro_grupo" class="error">Grupo é obrigatório.</label>');
  // valido = false;
  // element = $('button[data-id="grupo"]');
  // }
  //
  // if (capitulo == "") {
  // $('button[data-id="capitulo"]').after('<label id="erro_capitulo" class="error">Capítulo é obrigatório.</label>');
  // valido = false;
  // element = $('button[data-id="capitulo"]');
  // }

  if (deficiencia_sim == 1) {
    if (tipo_deficiencia == "") {
      $('button[data-id="tipo_deficiencia"]').after('<label id="erro_tipo_deficiencia" class="error">Tipo de deficiência é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="tipo_deficiencia"]');
    }
  }

  if (mae_contato == "") {
    $('div#div_mae_contato').after('<label id="erro_mae_contato" class="error">Contato é obrigatório.</label>');
    valido = false;
    element = $('div#div_mae_contato');
  }

  if (mae_nascimento == "") {
    $('div#div_mae_nascimento').after('<label id="erro_mae_nascimento" class="error">Data de nascimento é obrigatório.</label>');
    valido = false;
    element = $('div#div_mae_nascimento');
  } else {

    var partesData = mae_nascimento.split("/");
    var data_mae = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

    var partesData2 = data_nascimento.split("/");
    var data_titular = new Date(partesData2[2], partesData2[1] - 1, partesData2[0], 23, 59, 59);

    if (data_mae >= data_titular && data_nascimento != "") {
      $('div#div_mae_nascimento').after('<label id="erro_mae_nascimento" class="error">A idade da mãe não pode ser menor do que a idade do titular.</label>');
      valido = false;
      element = $('div#div_mae_nascimento');
    }

  }

  if (mae_nome == "") {
    $('div#div_mae_nome').after('<label id="erro_nis" class="error">Nome da mãe é obrigatório.</label>');
    valido = false;
    element = $('div#div_mae_nome');
  }

  if (estudando_sim == 1) {// ESTUDANDO SIM
    if (rede_publica_privada == 2) {// REDE PRIVADA
      if (financia_nao == 2) {// NÃO FINANCIA

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
    } else {
      var partesData = data_inicio.split("/");
      var data_iso_trab = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

      if (data_iso_trab > new Date()) {
        $('div#div_data_inicio').after('<label id="erro_data_inicio" class="error">A data de início no trabalho não pode ser maior que a data atual.</label>');
        valido = false;
        element = $('div#div_data_inicio');
      }
    }

  }

  // if (contato_telefone == "") {
  // $('div#div_contato_telefone').after('<label id="erro_contato_telefone" class="error">Telefone do contato é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_contato_telefone');
  // }
  //
  // if (contato_nome == "") {
  // $('div#div_contato_nome').after('<label id="erro_contato_nome" class="error">Contato é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_contato_nome');
  // }
  //
  // if (contato_tipo == "") {
  // $('div#div_contato_tipo').after('<label id="erro_contato_tipo" class="error">Tipo do contato é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_contato_tipo');
  // }
  //
  // if (ramal == "") {
  // $('div#div_ramal').after('<label id="erro_ramal" class="error">Ramal é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_ramal');
  // }
  //
  // if (comercial == "") {
  // $('div#div_comercial').after('<label id="erro_comercial" class="error">Comercial é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_comercial');
  // }
  //
  // if (celular == "") {
  // $('div#div_celular').after('<label id="erro_celular" class="error">Celular é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_celular');
  // }
  //
  // if (residencial == "") {
  // $('div#div_residencial').after('<label id="erro_residencial" class="error">Residencial é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_residencial');
  // }
  //
  // if (contato_email == "") {
  // $('div#div_contato_email').after('<label id="erro_contato_email" class="error">E-mail do contato é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_contato_email');
  // }

  // if (complemento == "") {
  // $('div#div_complemento').after('<label id="erro_complemento" class="error">Complemento é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_complemento');
  // }
  //
  // if (casa == "") {
  // $('div#div_casa').after('<label id="erro_casa" class="error">Casa é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_casa');
  // }
  //
  // if (quadra == "") {
  // $('div#div_quadra').after('<label id="erro_quadra" class="error">Quadra é obrigatório.</label>');
  // valido = false;
  // element = $('div#div_quadra');
  // }

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

  if (data_inicio_residencia == "") {
    $('div#div_data_inicio_residencia').after('<label id="erro_data_inicio_residencia" class="error">Data é obrigatório.</label>');
    valido = false;
    element = $('div#div_data_inicio_residencia');
  } else {
    var partesData = data_inicio_residencia.split("/");
    var data_iso = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

    if (data_iso > new Date()) {
      $('div#div_data_inicio_residencia').after('<label id="erro_data_inicio_residencia" class="error">A data de início da residência não pode ser maior que a data atual.</label>');
      valido = false;
      element = $('div#div_data_inicio_residencia');
    }
  }

  if (logradouro == "") {
    $('div#div_logradouro').after('<label id="erro_logradouro" class="error">Logradouro é obrigatório.</label>');
    valido = false;
    element = $('div#div_logradouro');
  }

  if (tipo_logradouro == "") {
    $('button[data-id="tipo_logradouro"]').after('<label id="erro_tipo_logradouro" class="error">Tipo de logradouro é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="tipo_logradouro"]');
  }

  if (municipio == "") {
    $('button[data-id="municipio"]').after('<label id="erro_municipio" class="error">Município é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="municipio"]');
  }

  if (alugada_sim == 1) {
    if (valor_aluguel == "") {
      $('div#div_valor_aluguel').after('<label id="erro_valor_aluguel" class="error">Valor do aluguel é obrigatório.</label>');
      valido = false;
      element = $('div#div_valor_aluguel');
    }
  }

  if (tipo_endereco == "") {
    $('button[data-id="tipo_endereco"]').after('<label id="erro_tipo_endereco" class="error">Tipo de endereço é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="tipo_endereco"]');
  }

  if (cep == "") {
    $('div#div_cep').after('<label id="erro_cep" class="error">Cep é obrigatório.</label>');
    valido = false;
    element = $('div#div_cep');
  }

  if (estado_civil == 6 || estado_civil == 7 || estado_civil == 8) {
    if (casamento_data == "") {
      $('div#div_casamento_data').after('<label id="erro_casamento_data" class="error">Data de casamento é obrigatório.</label>');
      valido = false;
      element = $('div#div_casamento_data');
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
    } else {
      var partesData = validade.split("/");
      var data_iso_validade = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

      if (data_iso_validade < new Date()) {
        $('div#div_validade').after('<label id="erro_validade" class="error">A data de validade não pode ser menor que a data atual.</label>');
        valido = false;
        element = $('div#div_validade');
      }
    }

    if (data_expedicao_1 == "") {
      $('div#div_data_expedicao_1').after('<label id="erro_data_expedicao_1" class="error">Data é obrigatório.</label>');
      valido = false;
      element = $('div#div_data_expedicao_1');
    } else {
      var partesData = data_expedicao_1.split("/");
      var data_iso_expedicao_1 = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

      if (data_iso_expedicao_1 > new Date()) {
        $('div#div_data_expedicao_1').after('<label id="erro_data_expedicao_1" class="error">A data de expedição não pode ser maior que a data atual.</label>');
        valido = false;
        element = $('div#div_data_expedicao_1');
      }
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
    $('button[data-id="documento1"]').after('<label id="erro_nome" class="error space-b-20">Tipo de documento.</label>');
    valido = false;
    element = $('button[data-id="documento1"]');
  } else {

    if (documento1 != "") {

      if (documento1 == 1) {

        if (data_expedicao == "") {
          $('div#div_data_expedicao').after('<label id="erro_data_expedicao" class="error">Data de expedição é obrigatório.</label>');
          valido = false;
          element = $('div#div_data_expedicao');
        } else {
          var partesData = data_expedicao.split("/");
          var data_iso_expedicao = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

          if (data_iso_expedicao > new Date()) {
            $('div#div_data_expedicao').after('<label id="erro_data_expedicao" class="error">A data de expedição não pode ser maior que a data atual.</label>');
            valido = false;
            element = $('div#div_data_expedicao');
          }
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
        } else {
          var partesData = data_validade_2.split("/");
          var data_iso_validade_2 = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

          if (data_iso_validade_2 < new Date()) {
            $('div#div_data_validade_2').after('<label id="erro_data_validade_2" class="error">A data de validade não pode ser menor que a data atual.</label>');
            valido = false;
            element = $('div#div_data_validade_2');
          }
        }

        if (data_expedicao_2 == "") {
          $('div#div_data_expedicao_2').after('<label id="erro_data_expedicao_2" class="error">Data de expedição é obrigatório.</label>');
          valido = false;
          element = $('div#div_data_expedicao_2');
        } else {
          var partesData = data_expedicao_2.split("/");
          var data_iso_expedicao_2 = new Date(partesData[2], partesData[1] - 1, partesData[0], 23, 59, 59);

          if (data_iso_expedicao_2 > new Date()) {
            $('div#div_data_expedicao_2').after('<label id="erro_data_expedicao_2" class="error">A data de expedição não pode ser maior que a data atual.</label>');
            valido = false;
            element = $('div#div_data_expedicao_2');
          }
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
    $('div#div_data_nascimento').after('<label id="erro_data_nascimento" class="error">Data de nascimento é obrigatório.</label>');
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

  if (subprograma == "") {
    $('button[data-id="subprograma"]').after('<label id="erro_subprograma space-b-10" class="error">A especificidade é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="subprograma"]');
  }

  if (programa == "") {
    $('button[data-id="programa"]').after('<label id="erro_programa" class="error">A origem da demanda é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="programa"]');
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
// VALIDAÇÃO DO CANDIDATO RETROATIVO
function recuperar_validator_retroativo() {
  var valido = true;
  var nome = $("#nome").val();
  var data_cadastro_anterior = $("#data_cadastro_anterior").val();

  var element = null;
  // LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  if (nome == "") {
    $('div#div_nome').after('<label id="erro_nome" class="error space-b-20">O campo nome é obrigatório.</label>');
    valido = false;
    element = $('div#div_nome');
  }

  if (data_cadastro_anterior == "") {
    $('div#div_data_cadastro_anterior').after('<label id="erro_data_cadastro_anterior" class="error space-b-20">Data de cadastro anterior é obrigatório.</label>');
    valido = false;
    element = $('div#div_data_cadastro_anterior');
  } else {

    var partesData = data_cadastro_anterior.split("/");
    var data_iso_cadastro_anterior = partesData[2] + "-" + partesData[1] + "-" + partesData[0];

    if (data_iso_cadastro_anterior < "2009-07-01") {
      $('div#div_data_cadastro_anterior').after('<label id="erro_data_cadastro_anterior" class="error space-b-20">Data de cadastro anterior não pode ser menor que 01/07/2009.</label>');
      valido = false;
      element = $('div#div_data_cadastro_anterior');
    }
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
// ADD MÁSCARA DE DINHEIRO
$("input#valor_aluguel").mask("###.###.###.##0,00", {
  reverse: true
});
// ---------------------------------------------------------------------------------------------------------
// ESTADO E MUNICÍPIO DO ENDEREÇO
$("#estado").change(function () {
  $("#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php", {
    estado: $(this).val()
  }, function (valor) {
    $("#municipio").html(valor);
    $('#municipio').selectpicker('refresh');
  });
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
// DEFICIÊNCIA SIM
$("input#deficiencia_sim").click(function () {
  $("#tipo_da_deficiencia").show();
});

// ---------------------------------------------------------------------------------------------------------
// DEFICIÊNCIA NÃO
$("input#deficiencia_nao").click(function () {
  $("#tipo_da_deficiencia").hide();
});

// ---------------------------------------------------------------------------------------------------------
// BENEFÍCIO SIM
$("input#beneficio_sim").click(function () {
  $("#beneficio_social_sim_nao").show();
});

// ---------------------------------------------------------------------------------------------------------
// BENEFÍCIO NÃO
$("input#beneficio_nao").click(function () {
  $("#beneficio_social_sim_nao").hide();
});

// ---------------------------------------------------------------------------------------------------------
// ALUGADO SIM
$("input#alugada_sim").click(function () {
  $("#alugado_sim_nao").show();
});

// ---------------------------------------------------------------------------------------------------------
// ALUGADO NÃO
$("input#alugada_nao").click(function () {
  $("#alugado_sim_nao").hide();
});

// ---------------------------------------------------------------------------------------------------------
// EMPREENDIMENTO SIM
$("input#pereferencia_empreendimento_sim").click(function () {
  $("#pereferencia_empreendimento_sim_nao").show();
  $("#div_deseja_participar").hide();
  $("#participar_sim_nao").hide();
  $("select#empreendimento").selectpicker('deselectAll');
  $("select#empreendimento").change();
  $("select#participar_id").selectpicker('deselectAll');
  $("select#participar_id").change();
  $("input#participar_nao").click();
  $("select#loteamento_id").selectpicker('deselectAll');
  $("select#loteamento_id").change();
});

// ---------------------------------------------------------------------------------------------------------
// EMPREENDIMENTO NÃO
$("input#pereferencia_empreendimento_nao").click(function () {
  $("#pereferencia_empreendimento_sim_nao").hide();
  $("#div_deseja_participar").show();
  $("select#participar_id").selectpicker('deselectAll');
  $("select#participar_id").change();
  $("input#participar_nao").click();
  $("select#loteamento_id").selectpicker('deselectAll');
  $("select#loteamento_id").change();
  $("div#div_loteamento").hide();
});

// ---------------------------------------------------------------------------------------------------------
// AO SELECIONAR UM EMPRRENDIMENTO IRÁ MOSTRAR UM LOTEAMENTO
$("select#empreendimento").livequery('change', function () {
  $("select#loteamento_id").selectpicker('deselectAll');
  $("select#loteamento_id").change();
  if ($(this).val() > 1) {
    $("div#div_loteamento").show();
  } else {
    $("div#div_loteamento").hide();
  }
});

// ---------------------------------------------------------------------------------------------------------
// PARTICIPAR DE ALGUM PROGRAMA HABITACIONAL SIM
$("input#participar_sim").click(function () {
  $("#participar_sim_nao").show();
  $("select#participar_id").selectpicker('deselectAll');
  $("select#participar_id").change();
});

// ---------------------------------------------------------------------------------------------------------
// PARTICIPAR DE ALGUM PROGRAMA HABITACIONAL NÃO
$("input#participar_nao").click(function () {
  $("#participar_sim_nao").hide();
});

// ---------------------------------------------------------------------------------------------------------
// MORADOR DE RUA SIM
$("input#morador_rua_sim").click(function () {
  $("#morador_rua_sim_nao").show();
});

// ---------------------------------------------------------------------------------------------------------
// MORADOR DE RUA NÃO
$("input#morador_rua_nao").click(function () {
  $("#morador_rua_sim_nao").hide();
});

// ---------------------------------------------------------------------------------------------------------
// RETROATIVO SIM
$("#retroativo_sim").click(function () {
  if ($("input#retroativo").val() == 1) {
    $("div.card-header").removeClass("palette-Teal-600");
    $("div.card-header").addClass("palette-Black");
    recuperar_validator_retroativo();
  }
  $("sup#obrigatorio").hide();
  $("#retroativo_sim_nao").show();

});
// ---------------------------------------------------------------------------------------------------------
// RETROATIVO NÃO
$("#retroativo_nao").click(function () {
  if ($("input#retroativo").val() == 0) {
    $("div.card-header").removeClass("palette-Black");
    $("div.card-header").addClass("palette-Teal-600");
    recuperar_validator();
  }
  $("sup#obrigatorio").show();
  $("#retroativo_sim_nao").hide();
});
// ---------------------------------------------------------------------------------------------------------
// PRÓPRIOS MEIOS SIM
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
// ---------------------------------------------------------------------------------------------------------
// PRÓPRIOS MEIOS NÃO
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
// ---------------------------------------------------------------------------------------------------------
// REDE PÚBLICA OU PRIVADA
$("select#rede_publica_privada").change(function () {
  if ($(this).val() == 2) {
    $("#natureza_instituicao").show();
  } else {
    $("#natureza_instituicao").hide();
  }
});
// ---------------------------------------------------------------------------------------------------------
// TRABALHA SIM
$("#trabalha_sim").change(function () {
  if ($(this).val() == 1) {
    $("#trabalha_sim_nao_mesmo_endereco").show();
    $("#trabalha_sim_nao").show();
  } else {
    $("#trabalha_sim_nao").hide();
    $("#trabalha_sim_nao_mesmo_endereco").hide();
  }
});
// ---------------------------------------------------------------------------------------------------------
// TRABALHA NÃO
$("#trabalha_nao").change(function () {
  if ($(this).val() == 0) {
    $("#trabalha_sim_nao").hide();
    $("#trabalha_sim_nao_mesmo_endereco").hide();
  } else {
    $("#trabalha_sim_nao").show();
    $("#trabalha_sim_nao_mesmo_endereco").show();
  }
});
// ---------------------------------------------------------------------------------------------------------
// TRABALHA NO MESMO ENDEREÇO SIM
$("#trab_mesmo_endereco_sim").change(function () {
  if ($(this).val() == 1) {
    $("#trab_mesmo_endereco_sim_nao").hide();
  } else {
    $("#trab_mesmo_endereco_sim_nao").show();
  }
});
// ---------------------------------------------------------------------------------------------------------
// TRABALHA NO MESMO ENDEREÇO NÃO
$("#trab_mesmo_endereco_nao").change(function () {
  if ($(this).val() == 0) {
    $("#trab_mesmo_endereco_sim_nao").show();
  } else {
    $("#trab_mesmo_endereco_sim_nao").hide();
  }
});
// ---------------------------------------------------------------------------------------------------------
// ESTUDANDO SIM
$("#estudando_sim").change(function () {

  $('#nome_instituicao').val('');
  $('#serie_periodo').val('');
  $('#rede_publica_privada').selectpicker('deselectAll');
  $('#rede_publica_privada').change();
  $('#programa_social').selectpicker('deselectAll');
  $('#programa_social').change();
  $('#porcentagem').val('');
  if ($(this).val() == 1) {
    $("#estiver_estudando").show();
  } else {
    $("#estiver_estudando").hide();
  }
});
// ---------------------------------------------------------------------------------------------------------
// ESTUDANDO NÃO
$("#estudando_nao").change(function () {

  $('#nome_instituicao').val('');
  $('#serie_periodo').val('');
  $('#rede_publica_privada').selectpicker('deselectAll');
  $('#rede_publica_privada').change();
  $('#programa_social').selectpicker('deselectAll');
  $('#programa_social').change();
  $('#porcentagem').val('');
  if ($(this).val() == 0) {
    $("#estiver_estudando").hide();
  } else {
    $("#estiver_estudando").show();
  }
});

// ---------------------------------------------------------------------------------------------------------
// VÍNCULO SIM
$("#vinculo_sim").change(function () {
  if ($(this).val() == 1) {
    $("#possuir_vinculo").show();
  } else {
    $("#possuir_vinculo").hide();
  }
});

// ---------------------------------------------------------------------------------------------------------
// VÍNCULO NÃO
$("#vinculo_nao").change(function () {
  if ($(this).val() == 0) {
    $("#possuir_vinculo").hide();
  } else {
    $("#possuir_vinculo").show();
  }
});

// ---------------------------------------------------------------------------------------------------------
// NACIONALIDADE ESTRANGEIRO
$("#nacionalidade_estrangeiro").change(function () {

  if ($(this).val() == 0) {
    $("#estrangeiro").show();
    $("#brasileiro").hide();
    $('#naturalidade_estado').selectpicker('deselectAll');
    $('#naturalidade_estado').change();
    $('#naturalidade_municipio').selectpicker('deselectAll');
    $('#naturalidade_municipio').selectpicker('deselectAll');
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
// ---------------------------------------------------------------------------------------------------------
// NACIONALIDADE BRASILEIRO
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
// ---------------------------------------------------------------------------------------------------------
// DOCUMENTO 1
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

// ---------------------------------------------------------------------------------------------------------
$("#nome").livequery("change", function () {

  if ($("input#retroativo").val() == 0) {

    var id = 0;// Id da pessoa
    var posicao = 0;// Posição do objeto
    var nome = $(this).val();// Nome digitado

    var conjuge = vf_conjuge(nome);
    var familiar = vf_familiar(nome);

    if (conjuge == 0 && familiar == 0) {

      pessoasNome.forEach(function (val, obj) {
        if (nome == val) {
          posicao = obj;// Pegando a posição do obj onde o nome se encontra
        }
      });

      pessoasId.forEach(function (val, obj) {
        if (obj == posicao) {
          id = val;// Pegando o ID da pessoa pesquisada
        }
      });

      if (posicao != 0 && id != 0) {
        swal({
          title: "Formulário de Candidato",
          text: "Foi encontrado no sistema uma pessoa com o mesmo nome selecionado, deseja migrar suas informações para o formulário?",
          type: "info",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + id + "'", 1);
          } else {
            swal("Cancelado", "A migração foi cancelada com sucesso!", "error");
          }
        });

      }
    } else {
      if (conjuge > 0) {
        swal({
          title: "Formulário de Candidato",
          text: "O titular a ser cadastrado está vinculado a um outro cadastro no sistema como conjugê, para continuar o cadastro é necessário primeiro desvinculá-lo. Deseja ir ao cadastro vinculado?",
          type: "error",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + conjuge + "'", 1);
          } else {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1'", 1);
          }
        });
      }

      if (familiar > 0) {
        swal({
          title: "Formulário de Candidato",
          text: "O titular a ser cadastrado está vinculado a um outro cadastro no sistema como grupo familiar, para continuar o cadastro é necessário primeiro desvinculá-lo. Deseja ir ao cadastro vinculado?",
          type: "error",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + familiar + "'", 1);
          } else {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1'", 1);
          }
        });
      }
    }
  }
});
// ---------------------------------------------------------------------------------------------------------
function vf_conjuge(nome) {

  var vf = false;
  var posicao = 0;
  var codigo = 0;

  conjugeNome.forEach(function (val, obj) {
    if (nome == val) {
      vf = true;
      posicao = obj;
    }
  });

  conjugeCandidato.forEach(function (val, obj) {
    if (posicao == obj) {
      codigo = val;
    }
  });

  if (vf) {
    return codigo;
  } else {
    return 0;
  }
}
// ---------------------------------------------------------------------------------------------------------
function vf_familiar(nome) {

  var vf = false;
  var posicao = 0;
  var codigo = 0;

  familiarNome.forEach(function (val, obj) {
    if (nome == val) {
      vf = true;
      posicao = obj;
    }
  });

  familiarCandidato.forEach(function (val, obj) {
    if (posicao == obj) {
      codigo = val;
    }
  });

  if (vf) {
    return codigo;
  } else {
    return 0;
  }

}
// ---------------------------------------------------------------------------------------------------------
$("#cpf").livequery("change", function () {

  if ($("input#retroativo").val() == 0) {

    var id = 0;// Id da pessoa
    var posicao = 0;// Posição do objeto
    var cpf = $(this).val();// Nome digitado

    var conjuge = vf_conjuge_cpf(cpf);
    var familiar = vf_familiar_cpf(cpf);

    pessoasCpf.forEach(function (val, obj) {
      if (cpf == val) {
        posicao = obj;
      }
    });

    pessoasId.forEach(function (val, obj) {
      if (obj == posicao) {
        id = val;
      }
    });

    if (conjuge == 0 && familiar == 0) {

      if (posicao != 0 && id != 0) {

        swal({
          title: "Formulário de Cadastro",
          text: "Foi encontrado no sistema uma pessoa com o mesmo CPF selecionado, deseja migrar suas informações para o formulário?",
          type: "info",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + id + "'", 1);
          } else {
            swal("Cancelado", "A migração foi cancelada com sucesso!", "error");
          }
        });
      }

    } else {

      if (conjuge > 0) {
        swal({
          title: "Formulário de Candidato",
          text: "O titular a ser cadastrado está vinculado a um outro cadastro no sistema como conjugê, para continuar o cadastro é necessário primeiro desvinculá-lo. Deseja ir ao cadastro vinculado?",
          type: "error",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + conjuge + "'", 1);
          } else {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1'", 1);
          }
        });
      }

      if (familiar > 0) {
        swal({
          title: "Formulário de Candidato",
          text: "O titular a ser cadastrado está vinculado a um outro cadastro no sistema como grupo familiar, para continuar o cadastro é necessário primeiro desvinculá-lo. Deseja ir ao cadastro vinculado?",
          type: "error",
          showCancelButton: true,
          confirmButtonColor: "#8CD4F5",
          confirmButtonText: "Desejo sim!",
          cancelButtonText: "Não, desejo!",
          closeOnConfirm: false,
          closeOnCancel: false
        }, function (isConfirm) {
          if (isConfirm) {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + familiar + "'", 1);
          } else {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1'", 1);
          }
        });
      }

      return false;
    }
  }
});
// ---------------------------------------------------------------------------------------------------------
function vf_conjuge_cpf(cpf) {

  var vf = false;
  var posicao = 0;
  var codigo = 0;

  conjugeCpf.forEach(function (val, obj) {
    if (cpf == val) {
      vf = true;
      posicao = obj;
    }
  });

  conjugeCandidato.forEach(function (val, obj) {
    if (posicao == obj) {
      codigo = val;
    }
  });

  if (vf) {
    return codigo;
  } else {
    return 0;
  }
}
// ---------------------------------------------------------------------------------------------------------
function vf_familiar_cpf(cpf) {

  var vf = false;
  var posicao = 0;
  var codigo = 0;

  familiarCpf.forEach(function (val, obj) {
    if (cpf == val) {
      vf = true;
      posicao = obj;
    }
  });

  familiarCandidato.forEach(function (val, obj) {
    if (posicao == obj) {
      codigo = val;
    }
  });

  if (vf) {
    return codigo;
  } else {
    return 0;
  }
}
// ---------------------------------------------------------------------------------------------------------
// FUNÇÃO PARA PESQUISAR DINÂMICO EM INPUT POR NOME
if ($('input#nome')) {
  var nomes = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: pessoasNome
  });

  $('input#nome').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'nomes',
    source: nomes
  });
}
// ---------------------------------------------------------------------------------------------------------
// FUNÇÃO PARA PESQUISAR DINÂMICO EM INPUT POR CPF
if ($('input#cpf')) {
  var cpfs = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: pessoasCpf
  });

  $('input#cpf').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'cpfs',
    source: cpfs
  });
}
// ---------------------------------------------------------------------------------------------------------
// FUNÇÃO PARA PESQUISAR DINÂMICO EM INPUT POR BAIRRO
if ($('input#bairro')) {
  var bairros = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: bairrosAcre
  });

  $('input#bairro').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'bairros',
    source: bairros
  });
}
// ---------------------------------------------------------------------------------------------------------
// FUNÇÃO PARA CARREGAR ENDEREÇOS AUTOMÁTICO PELO CEP DIGITADO
function consultacep() {

  var cep = $("#cep").val();
  if (cep.replace(/[^\d]+/g, '').length == 8) {
    // $("#div_loader").show();
    projetouniversal.util.getjson({
      url: PORTAL_URL + "assets/plugins/busca-cep-correios/cep.php",
      type: "POST",
      data: {
        cep: cep
      },
      enctype: 'multipart/form-data',
      success: onSuccessSendConsultaCep,
      error: onErrorConsultaCep
    });
    return false;
  }
}
// ---------------------------------------------------------------------------------------------------------
// SE A CONSULTA DER SUCESSO
function onSuccessSendConsultaCep(obj) {
  if (obj.msg == 'success') {

    $("div#div_bairro").addClass('fg-toggled');
    $("div#div_logradouro").addClass('fg-toggled');

    document.getElementById('logradouro').value = obj.logradouro;
    document.getElementById('bairro').value = obj.bairro;

    $('#bairro').val(obj.bairro).trigger('change');
    $('#logradouro').val(obj.logradouro).trigger('change');

    initialize();

    var c = document.getElementById("estado"), i = 0;
    for (; i < c.options.length; i++) {
      if (c.options[i].label == obj.uf) {
        var val = c.options[i].value;
        $('#estado').val(val).trigger('change');
        break;
      }
      $("#estado").change();
    }
    if (carrega_municipio(obj.cidade)) {
      $('#municipio').change();
      // $("#div_loader").hide();
    }
  }
  // $("#div_loader").hide();
  return false;
}
// ---------------------------------------------------------------------------------------------------------
// SE A CONSULTA DER ERRADO
function onErrorConsultaCep(args) {
  $.prompt('Cep não encontrado.');
  // $("#div_loader").hide();
  return false;
}
// ---------------------------------------------------------------------------------------------------------
// CARREGA OS MUNICÍPIOS AUTOMÁTICO
function carrega_municipio(municipio) {
  $("#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades.php", {
    estado: $('#estado').val()
  }, function (data) {

    $("#municipio").html(data);
    $('#municipio').change();
    if (isNaN(data)) {
      var e = document.getElementById("municipio"), i = 0;
      for (; i < e.options.length; i++) {

        $('#municipio').change();
        if (e.options[i].label == municipio) {
          var val = e.options[i].value;
          $('#municipio').val(val).trigger('change');
          break;
        }
      }
    }
    return true;
  });
}
// ---------------------------------------------------------------------------------------------------------

$("#capitulo").change(function () {
  $("#grupo").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/grupos.php", {
    catinic1: $(this).find('option:selected').attr('catinic1'),
    catfim1: $(this).find('option:selected').attr('catfim1')
  }, function (valor) {
    $("#grupo").html(valor);
    $('#grupo').selectpicker('refresh');
  });
});
$("#grupo").change(function () {
  $("#categoria").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/categorias.php", {
    catinic2: $(this).find('option:selected').attr('catinic2'),
    catfim2: $(this).find('option:selected').attr('catfim2')
  }, function (valor) {
    $("#categoria").html(valor);
    $('#categoria').selectpicker('refresh');
  });
});
// ---------------------------------------------------------------------------------------------------------
// ADD MÁSCARA DE DINHEIRO
$("input#renda_valor").mask("###.###.###.##0,00", {
  reverse: true
});
// ---------------------------------------------------------------------------------------------------------
$("a#add_renda").livequery("click", function () {
  var clone = $(this).parents("div#clonar").clone();

  $(this).parents("div#clonar").after(clone);

  $(this).parents("div#clonar").next().find("input#renda_valor").val("");
  $(this).parents("div#clonar").next().find("input#renda_valor").mask("###.###.###.##0,00", {
    reverse: true
  });
  $(this).parents("div#clonar").next().find("a#remover_renda").show();

  // REMOVENDO SELECTS E ATUALIZANDO NOVAMENTE
  $(this).parents("div#clonar").next().find('div.bootstrap-select').remove();
  $(this).parents("div#clonar").next().find('select').val("");
  $(this).parents("div#clonar").next().find('select').selectpicker();
});
// ---------------------------------------------------------------------------------------------------------
$("a#remover_renda").livequery("click", function () {
  $(this).parents("div#clonar").remove();
});
// ---------------------------------------------------------------------------------------------------------
$("select#estado_civil").livequery("change", function () {
  if ($(this).val() == 6 || $(this).val() == 7 || $(this).val() == 8) {
    $("div#div_data_do_casamento").show();
  } else {
    $("div#div_data_do_casamento").hide();
  }
});
// ---------------------------------------------------------------------------------------------------------
$("a#carregando_modal").livequery("click", function () {
  $("div#div_mapa").slideToggle();
  initialize();
  $(this).hide();
  $("button#carregando_modal_button").show();
  $("i#mudar_check").removeClass("zmdi-check");
  $("i#mudar_check").addClass("zmdi-close");
});
// ---------------------------------------------------------------------------------------------------------
$("button#carregando_modal_button").livequery("click", function () {
  if ($("div#div_mapa").is(":visible")) {
    $(this).removeClass("btn-danger");
    $(this).addClass("btn-success");
    $("i#mudar_check").removeClass("zmdi-close");
    $("i#mudar_check").addClass("zmdi-check");
  } else if ($("div#div_mapa").is(":hidden")) {
    $(this).removeClass("btn-success");
    $(this).addClass("btn-danger");
    $("i#mudar_check").removeClass("zmdi-check");
    $("i#mudar_check").addClass("zmdi-close");
  }

  $("div#div_mapa").slideToggle();
  initialize();
});
// ---------------------------------------------------------------------------------------------------------
