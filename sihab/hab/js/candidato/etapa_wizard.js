/*---------------------------------------------------------------------------------------------------------
 DATA: 10/08/2016 ÀS 11:09
 NOME: JS DA CLASSE CADASTRO DE CANDIDATO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
  function salvar_formulario(atual, proxima, formulario) {

    var codigo = $("#id").val();

    var vf = true;
    var pasta = "candidato";

    if ($("input#vf_usuario_pagina").val() == 0) {

      if (atual == "etapa1") {//TITULAR
        if ($("input#retroativo").val() == 1) {
          vf = recuperar_validator_titular_retroativo();
        } else {
          vf = recuperar_validator_titular();
        }
      } else if (atual == "etapa2") {//CÔNJUGE

        if ($("input[id='conjuge_sim']:checked").val() == 1) {
          vf = recuperar_validator_conjuge();

        } else {
          vf = recuperar_validator_2();
        }

      } else if (atual == "etapa3") {//FAMILIAR
        vf = recuperar_validator_familiar();
      } else if (atual == "etapa4") {
        vf = true;
        atual = "salvar_anexo";
        pasta = "pessoa";
      }

      if (vf) {
        $.ajax({
          type: "POST",
          url: PORTAL_URL + 'hab/dao/' + pasta + '/' + atual,
          data: $('#' + formulario + '').serialize(),
          cache: false,
          success: function (obj) {
            obj = JSON.parse(obj);
            if (obj.msg == 'success') {

              //GERANDO O COMPROVANTE
              if (atual == "etapa3" && proxima == "etapa4") {
                window.open(PORTAL_URL + "sistema/candidato/comprovante/" + codigo + "");
              }

              if (codigo != 0 && codigo != "") {
                setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/" + proxima + "/" + codigo + "'", 1);
              } else {
                setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/" + proxima + "'", 1);
              }

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
            return false;
          }
        });
        return false;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
//---------------------------------------------------------------------------------------------------------
  $("div#clicar_titular").livequery("click", function () {
    var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa
    var pagina = retornaUrl(caminho);
    if (pagina == "etapa4") {
      $("input#proxima_etapa").val("etapa1");
      $("form#form_candidato_etapa4").submit();
    } else {
      salvar_formulario(pagina, "etapa1", buscar_formulario(pagina));
    }
  });
//---------------------------------------------------------------------------------------------------------
  $("div#clicar_conjuge").livequery("click", function () {
    var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa
    var pagina = retornaUrl(caminho);
    if (pagina == "etapa4") {
      $("input#proxima_etapa").val("etapa2");
      $("form#form_candidato_etapa4").submit();
    } else {
      salvar_formulario(pagina, "etapa2", buscar_formulario(pagina));
    }
  });
  //---------------------------------------------------------------------------------------------------------
  $("div#clicar_familiar").livequery("click", function () {

    var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa
    var pagina = retornaUrl(caminho);
    if (pagina == "etapa4") {
      $("input#proxima_etapa").val("etapa3");
      $("form#form_candidato_etapa4").submit();
    } else {
      salvar_formulario(pagina, "etapa3", buscar_formulario(pagina));
    }
  });
  //---------------------------------------------------------------------------------------------------------
  $("div#clicar_documentacao").livequery("click", function () {
    var caminho = window.location.href.toString().split(window.location.host)[1];//Pegando a url completa
    var pagina = retornaUrl(caminho);
    if (pagina == "etapa4") {
      $("input#proxima_etapa").val("etapa4");
      $("form#form_candidato_etapa4").submit();
    } else {
      salvar_formulario(pagina, "etapa4", buscar_formulario(pagina));
    }
  });
});
// ---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA BUSCAR O NOME DO FORMULÁRIO DE CADA PÁGINA
function buscar_formulario(pagina) {
  if (pagina == "etapa1") {
    return "form_candidato";
  } else if (pagina == "etapa2") {
    return "form_candidato_etapa2";
  } else if (pagina == "etapa3") {
    return "form_candidato_etapa3";
  } else if (pagina == "etapa4") {
    return "form_candidato_etapa4";
  }
}
// ---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA PEGAR VALOR PASSADO PELA URL
function retornaUrl(url) {
  var urlTratada = "";
  // dar um split para gerar um array para poder tratar a url e poder tirar por exemplo os primeiro dois parâmetros
  var arrUrl = url.split('/');
  // deleta o indice um do array que é um parâmetro vazio
  delete arrUrl[0];
  // deleta o indice dois do array que é o primeiro parâmetro da url e assim por endiante
  delete arrUrl[1];
  delete arrUrl[2];
  delete arrUrl[3];
  delete arrUrl[5];
  // dar um join para refazer a string agora sem os dois primeiros valores
  var joinUrl = arrUrl.join('/');

  urlTratada = joinUrl.substring(2);

  urlTratada.replace('/', '');
  urlTratada = urlTratada.replace('/', '');
  urlTratada = urlTratada.replace('/', '');
  urlTratada = urlTratada.replace('/', '');
  urlTratada = urlTratada.replace('/', '');
  urlTratada = urlTratada.replace('/', '');

  // retornar a url passando por substring 2 para tirar as primeiras duas barras
  return urlTratada;
}
// ---------------------------------------------------------------------------------------------------------
// VALIDAÇÃO DO CANDIDATO
function recuperar_validator_titular() {
  var valido = true;
  var nome = $("#nome").val();
  var cpf = $("#cpf").val();
  var cor = $("#cor").val();
  var cep = $("#cep").val();
  var municipio = $("#municipio").val();
  var logradouro = $("#logradouro").val();
  var tipo_logradouro = $("#tipo_logradouro").val();
  var numero = $("#numero").val();
  var bairro = $("#bairro").val();
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
  var vinculo_sim = $("input[id='vinculo_sim']:checked").val();
  var vinculo_nome = $("#vinculo_nome").val();

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

  if (pereferencia_empreendimento == 1) {
    if (empreendimento == "") {
      $('button[data-id="empreendimento"]').after('<label id="erro_empreendimento" class="error">O empreendimento é obrigatório.</label>');
      valido = false;
      element = $('button[data-id="empreendimento"]');
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
    $('button[data-id="subprograma"]').after('<label id="erro_subprograma space-b-10" class="error">Subprograma é obrigatório.</label>');
    valido = false;
    element = $('button[data-id="subprograma"]');
  }

  if (programa == "") {
    $('button[data-id="programa"]').after('<label id="erro_programa" class="error">Programa é obrigatório.</label>');
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
function recuperar_validator_titular_retroativo() {
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
  var observacao = $("#observacao").val();

  var element = null;

  // LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  if (observacao == "") {
    $('div#div_observacao').after('<label id="erro_observacao" class="error space-b-20">O campo nome é obrigatório.</label>');
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
//VALIDAÇÃO DO CÔNJUGE
function recuperar_validator_conjuge() {
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
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO FAMILIAR
function recuperar_validator_familiar() {

  var valido = true;
  var error = true;
  var element = null;

  //LIMPA MENSAGENS DE ERRO
  $('label.error').each(function () {
    $(this).remove();
  });

  $("div.form-wizard").each(function (a, obj) {

    valido = true;

    var nome = $(obj).find("input#nome").val();
    var cpf = $(obj).find("input#cpf").val();
    var cor = $(obj).find("select#cor").val();
    var cep = $(obj).find("input#cep").val();
    var estado = $(obj).find("select#estado").val();
    var municipio = $(obj).find("select#municipio").val();
    var logradouro = $(obj).find("input#logradouro").val();
    var numero = $(obj).find("input#numero").val();
    var bairro = $(obj).find("input#bairro").val();
    var quadra = $(obj).find("input#quadra").val();
    var casa = $(obj).find("input#casa").val();
    var complemento = $(obj).find("input#complemento").val();
    var residencial = $(obj).find("input#residencial").val();
    var celular = $(obj).find("input#celular").val();
    var comercial = $(obj).find("input#comercial").val();
    var ramal = $(obj).find("input#ramal").val();
    var contato_tipo = $(obj).find("input#contato_tipo").val();
    var contato_nome = $(obj).find("input#contato_nome").val();
    var contato_telefone = $(obj).find("input#contato_telefone").val();
    var contato_email = $(obj).find("input#contato_email").val();
    var data_nascimento = $(obj).find("input#data_nascimento").val();
    var documento1 = $(obj).find("select#documento1").val();
    var orgao_expedidor = $(obj).find("select#orgao_expedidor").val();
    var uf_expedicao = $(obj).find("select#uf_expedicao").val();
    var data_expedicao = $(obj).find("input#data_expedicao").val();
    var numero_registro = $(obj).find("input#numero_registro").val();
    var numero_registro_2 = $(obj).find("input#numero_registro_2").val();
    var uf_expedicao_2 = $(obj).find("select#uf_expedicao_2").val();
    var data_expedicao_2 = $(obj).find("input#data_expedicao_2").val();
    var data_validade_2 = $(obj).find("input#data_validade_2").val();
    var nacionalidade_brasileiro = $(obj).find("input[id='nacionalidade_brasileiro']:checked").val();
    var nacionalidade_estrangeiro = $(obj).find("input[id='nacionalidade_estrangeiro']:checked").val();
    var naturalidade_estado = $(obj).find("select#naturalidade_estado").val();
    var naturalidade_municipio = $(obj).find("select#naturalidade_municipio").val();
    var pais = $(obj).find("select#pais").val();
    var cod_rne = $(obj).find("input#cod_rne").val();
    var classificacao = $(obj).find("select#classificacao").val();
    var data_expedicao_1 = $(obj).find("input#data_expedicao_1").val();
    var validade = $(obj).find("input#validade").val();
    var trabalha_sim = $(obj).find("input[id='trabalha_sim']:checked").val();
    var local_trabalho = $(obj).find("input#local_trabalho").val();
    var trab_endereco = $(obj).find("input#trab_endereco").val();
    var cargo_funcao = $(obj).find("input#cargo_funcao").val();
    var data_inicio = $(obj).find("input#data_inicio").val();
    var tempo_servico = $(obj).find("input#tempo_servico").val();
    var estudando_sim = $(obj).find("input[id='estudando_sim']:checked").val();
    var nome_instituicao = $(obj).find("input#nome_instituicao").val();
    var serie_periodo = $(obj).find("input#serie_periodo").val();
    var estado_civil = $(obj).find("select#estado_civil").val();
    var grau_escolar = $(obj).find("select#grau_escolar").val();
    var rede_publica_privada = $(obj).find("select#rede_publica_privada").val();
    var financia_nao = $(obj).find("input[id='financia_nao']:checked").val();
    var programa_social = $(obj).find("select#programa_social").val();
    var porcentagem = $(obj).find("input#porcentagem").val();
    var cad_unico = $(obj).find("input#cad_unico").val();
    var nis = $(obj).find("input#nis").val();
    var capitulo = $(obj).find("select#capitulo").val();
    var grupo = $(obj).find("select#grupo").val();
    var mora_sim = $(obj).find("input[id='mora_sim']:checked").val();
    var grau_parentesco = $(obj).find("select#grau_parentesco").val();
    var familia_sim = $("input[id='familia_sim']:checked").val();
    var trabalha_mesmo_endereco = $(obj).find("input[id='trab_mesmo_endereco_nao']:checked").val();

    if (familia_sim == 1 && grau_parentesco != "") {

      if (nis == "") {
        $(obj).find('div#div_nis').after('<label id="erro_nis" class="error">Nis é obrigatório.</label>');
        valido = false;
      }

      if (cad_unico == "") {
        $(obj).find('div#div_cad_unico').after('<label id="erro_cad_unico" class="error">Cad. único é obrigatório.</label>');
        valido = false;
      }

//      if (grupo == "") {
//        $(obj).find('button[data-id="grupo"]').after('<label id="erro_grupo" class="error">Grupo é obrigatório.</label>');
//        valido = false;
//      }
//
//      if (capitulo == "") {
//        $(obj).find('button[data-id="capitulo"]').after('<label id="erro_capitulo" class="error">Capítulo é obrigatório.</label>');
//        valido = false;
//      }

      if (estudando_sim == 1) {//ESTUDANDO SIM
        if (rede_publica_privada == 2) {//REDE PRIVADA
          if (financia_nao == 2) {//NÃO FINANCIA

            if (porcentagem == "") {
              $(obj).find('div#div_porcentagem').after('<label id="erro_porcentagem" class="error">Porcentagem é obrigatório.</label>');
              valido = false;
            }

            if (programa_social == "") {
              $(obj).find('div#div_programa_social').after('<label id="erro_programa_social" class="error">Programa social é obrigatório.</label>');
              valido = false;
            }
          }
        }

        if (rede_publica_privada == "") {
          $(obj).find('button[data-id="rede_publica_privada"]').after('<label id="erro_rede_publica_privada" class="error">Natureza da instituição é obrigatório.</label>');
          valido = false;
        }

        if (serie_periodo == "") {
          $(obj).find('div#div_serie_periodo').after('<label id="erro_serie_periodo" class="error">Série/Período é obrigatório.</label>');
          valido = false;
        }

        if (nome_instituicao == "") {
          $(obj).find('div#div_nome_instituicao').after('<label id="erro_nome_instituicao" class="error">Nome da insituição é obrigatório.</label>');
          valido = false;
        }
      }

      if (grau_escolar == "") {
        $(obj).find('button[data-id="grau_escolar"]').after('<label id="erro_grau_escolar" class="error">Grau escolar é obrigatório.</label>');
        valido = false;
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

      if (mora_sim != 1) {

        if (bairro == "") {
          $(obj).find('div#div_bairro').after('<label id="erro_bairro" class="error">Bairro é obrigatório.</label>');
          valido = false;
        }

        if (numero == "") {
          $(obj).find('div#div_numero').after('<label id="erro_numero" class="error">Número é obrigatório.</label>');
          valido = false;
        }

        if (logradouro == "") {
          $(obj).find('div#div_logradouro').after('<label id="erro_logradouro" class="error">Logradouro é obrigatório.</label>');
          valido = false;
        }

        if (municipio == "") {
          $(obj).find('button[data-id="municipio"]').after('<label id="erro_municipio" class="error">Município é obrigatório.</label>');
          valido = false;

        }

        if (estado == "") {
          $(obj).find('button[data-id="estado"]').after('<label id="erro_estado" class="error">Estado é obrigatório.</label>');
          valido = false;
        }

        if (cep == "") {
          $('div#div_cep').after('<label id="erro_cep" class="error">Cep é obrigatório.</label>');
          valido = false;
          element = $('div#div_cep');
        }
      }

      if (estado_civil == "") {
        $(obj).find('button[data-id="estado_civil"]').after('<label id="erro_estado_civil" class="error">Estado civil é obrigatório.</label>');
        valido = false;
      }

      if (nacionalidade_estrangeiro == 0) {

        if (validade == "") {
          $(obj).find('div#div_validade').after('<label id="erro_validade" class="error">Validade é obrigatório.</label>');
          valido = false;
        }

        if (data_expedicao_1 == "") {
          $(obj).find('div#div_data_expedicao_1').after('<label id="erro_data_expedicao_1" class="error">Data é obrigatório.</label>');
          valido = false;
        }

        if (classificacao == "") {
          $(obj).find('div#div_classificacao').after('<label id="erro_classificacao" class="error">Classificação é obrigatório.</label>');
          valido = false;
        }

        if (cod_rne == "") {
          $(obj).find('div#div_cod_rne').after('<label id="erro_cod_rne" class="error">Cód. Rne é obrigatório.</label>');
          valido = false;
        }

        if (pais == "") {
          $(obj).find('button[data-id="pais"]').after('<label id="erro_pais" class="error">Nacionalidade é obrigatório.</label>');
          valido = false;
        }

      } else if (nacionalidade_brasileiro == 1) {

        if (naturalidade_municipio == "") {
          $(obj).find('button[data-id="naturalidade_municipio"]').after('<label id="erro_naturalidade_municipio" class="error">Município é obrigatório.</label>');
          valido = false;
        }

        if (naturalidade_estado == "") {
          $(obj).find('button[data-id="naturalidade_estado"]').after('<label id="naturalidade_estado" class="error">Estado é obrigatório.</label>');
          valido = false;
        }

      }

      if (documento1 == "") {
        $(obj).find('button[data-id="documento1"]').after('<label id="erro_nome" class="error space-b-20">É necessário escolher um tipo de documento.</label>');
        valido = false;
      } else {

        if (documento1 != "") {

          if (documento1 == 1) {

            if (data_expedicao == "") {
              $(obj).find('div#div_data_expedicao').after('<label id="erro_data_expedicao" class="error">Data de expedição é obrigatório.</label>');
              valido = false;
            }

            if (uf_expedicao == "") {
              $(obj).find('button[data-id="uf_expedicao"]').after('<label id="erro_uf_expedicao" class="error">UF de expedição é obrigatório.</label>');
              valido = false;
            }

            if (orgao_expedidor == "") {
              $(obj).find('button[data-id="orgao_expedidor"]').after('<label id="erro_orgao_expedidor" class="error">Órgão expedidor é obrigatório.</label>');
              valido = false;
            }

            if (numero_registro == "") {
              $(obj).find('div#div_numero_registro').after('<label id="erro_numero_registro" class="error">Número de registro é obrigatório.</label>');
              valido = false;
            }

          } else {
            if (data_validade_2 == "") {
              $(obj).find('div#div_data_validade_2').after('<label id="erro_data_validade_2" class="error">Validade é obrigatório.</label>');
              valido = false;
            }

            if (data_expedicao_2 == "") {
              $(obj).find('div#div_data_expedicao_2').after('<label id="erro_data_expedicao_2" class="error">Data de expedição é obrigatório.</label>');
              valido = false;
            }

            if (uf_expedicao_2 == "") {
              $(obj).find('button[data-id="uf_expedicao_2"]').after('<label id="erro_uf_expedicao_2" class="error">UF de expedição é obrigatório.</label>');
              valido = false;
            }

            if (numero_registro_2 == "") {
              $(obj).find('div#div_numero_registro_2').after('<label id="erro_numero_registro_2" class="error">Número de registro é obrigatório.</label>');
              valido = false;
            }
          }

        }
      }

      if (data_nascimento == "") {
        $(obj).find('div#div_data_nascimento').after('<label id="erro_data_nascimento" class="error">Data é obrigatório.</label>');
        valido = false;
      }

      if (cor == "") {
        $(obj).find('button[data-id="cor"]').after('<label id="erro_cor" class="error space-b-20">Cor/Raça é obrigatório.</label>');
        valido = false;
      }

      if (nome == "") {
        $(obj).find('div#div_nome').after('<label id="erro_nome" class="error space-b-20">Nome é obrigatório.</label>');
        valido = false;
      }

      if (cpf == "") {
        $(obj).find('div#div_cpf').after('<label id="erro_cpf" class="error space-b-20">CPF é obrigatório.</label>');
        valido = false;
      }

      if (grau_parentesco == "") {
        $(obj).find('button[data-id="grau_parentesco"]').after('<label id="erro_grau_parentesco" class="error space-b-20">Grau de parentesco é obrigatório.</label>');
        valido = false;
      }

      if (valido == false) {

        error = false;

        $(obj).find("a").attr("error", true);

        element = $(obj).parents("div.row").find("ul#ul_menus");

        $(obj).parents("div.tab-pane").attr("error", true);

        $(obj).parents("div.row").find("ul#ul_menus").find("li").each(function (b, obj2) {
          if ($(obj2).find("a").attr("aria-controls") == $(obj).parents("div.tab-pane").attr("id")) {
            $(obj2).find("a").attr("style", "color: red");//MARCANDO MENU VERMELHO
          }
        });

      } else {
        $(obj).parents("div.row").find("ul#ul_menus").find("li").each(function (b, obj2) {
          if ($(obj2).find("a").attr("aria-controls") == $(obj).parents("div.tab-pane").attr("id")) {
            $(obj2).find("a").removeAttr("style");//TIRANDO MENU VERMELHO
          }
        });
      }

    }

    if (element != null) {
      var topPosition = element.offset().top - 135;
      $('html, body').animate({
        scrollTop: topPosition
      }, 800);
    }

  });

  return error;
}
//---------------------------------------------------------------------------------------------------------
