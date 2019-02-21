/*---------------------------------------------------------------------------------------------------------
 DATA: 15/08/2016 ÀS 11:09
 NOME: JS DA CLASSE CADASTRO DE CANDIDATO ETAPA 3
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
  //---------------------------------------------------------------------------------------------------------  
//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");

  $("#anterior").click(function () {
    $("#caminho_pagina").val('etapa2');
  });

  $("#finalizar").click(function () {
    if ($("input#retroativo").val() == 1) {
      $("#caminho_pagina").val('lista_morto');
    } else {
      $("#caminho_pagina").val('lista');
    }
  });

  $("#proxima").click(function () {
    $("#caminho_pagina").val('etapa4');
  });
//---------------------------------------------------------------------------------------------------------
//CADASTRO DE CANDIDATO ETAPA 3
  $('form#form_candidato_etapa3').submit(function () {

    $("#div_loader").show();

    if (recuperar_validator()) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/candidato/etapa3',
        data: $('#form_candidato_etapa3').serialize(),
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

              //GERANDO O COMPROVANTE
              if ($("#caminho_pagina").val() == "etapa4") {
                window.open(PORTAL_URL + "sistema/candidato/comprovante/" + obj.id + "");
              }

              if ($("#caminho_pagina").val() == "etapa2") {
                //SE FOR UNIÃO ESTAVEL OU CASADO VAI PARA ETAPA 2 DO CÔNJUGE
                if ($("#uniao_estavel_titular").val() == 1 || $("#estado_civil_titular").val() == 2 || $("#estado_civil_titular").val() == 6 ||
                        $("#estado_civil_titular").val() == 7 || $("#estado_civil_titular").val() == 8) {
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa2/" + obj.id + "'", 1);
                } else {//SE NÃO PULA A ETAPA DO CÔNJUGE E VAI PARA ETAPA 1 DO FAMILIAR
                  setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa1/" + obj.id + "'", 1);
                }
              } else {
                setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/" + $("#caminho_pagina").val() + "/" + obj.id + "'", 1);
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
});
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DO CANDIDATO
function recuperar_validator() {

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
//ESTADO E MUNICÍPIO DO ENDEREÇO
$("select#estado").livequery('change', function () {
  var op = this;
  $(op).parent("div").parent("div").find("select#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php",
          {estado: $(op).val()},
  function (valor) {
    $(op).parent("div").parent("div").find("select#municipio").html(valor);
    $(op).parent("div").parent("div").find("select#municipio").selectpicker('refresh');
  });
});

//---------------------------------------------------------------------------------------------------------
//ESTADO E MUNICÍPIO DATA NATURALIDADE
$("select#naturalidade_estado").livequery('change', function () {
  var op = this;
  $(op).parent("div").parent("div").find("select#naturalidade_municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades_mai.php",
          {estado: $(op).val()},
  function (valor) {
    $(op).parent("div").parent("div").find("select#naturalidade_municipio").html(valor);
    $(op).parent("div").parent("div").find('select#naturalidade_municipio').selectpicker('refresh');
  });
});

//---------------------------------------------------------------------------------------------------------
//RETROATIVO SIM
$("#retroativo_sim").livequery('change', function () {
  if ($("input[id='retroativo_sim']:checked").val() == 1) {
    recuperar_validator_retroativo();
  }
});

//---------------------------------------------------------------------------------------------------------
//RETROATIVO NÃO
$("#retroativo_nao").livequery('change', function () {
  if ($("input[id='retroativo_nao']:checked").val() == 0) {
    recuperar_validator();
  }
});

//---------------------------------------------------------------------------------------------------------
//FAMILIAR SIM
$("#familia_sim").livequery('change', function () {

  var qtd_familiar = $("input#qtd_familiar").val();

  if ($(this).val() == 1) {
    $("#div_familiar").show();
    $("#div_possui").hide();
  } else {
    $("#div_familiar").hide();
    if (qtd_familiar > 0) {
      $("#div_possui").show();
    } else {
      $("#div_possui").hide();
    }
  }
});

//---------------------------------------------------------------------------------------------------------
//FAMILIAR NÃO
$("#familia_nao").livequery('change', function () {

  var qtd_familiar = $("input#qtd_familiar").val();

  if ($(this).val() == 0) {
    $("#div_familiar").hide();
    if (qtd_familiar > 0) {
      $("#div_possui").show();
    } else {
      $("#div_possui").hide();
    }
  } else {
    $("#div_familiar").show();
    $("#div_possui").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//PRÓPRIOS MEIOS SIM
$("input#financia_sim").livequery('change', function () {
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#programa_social').selectpicker('deselectAll');
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#programa_social').change();
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#porcentagem').val('');
  if ($(this).val() == 1) {
    $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").hide();
  } else {
    $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//PRÓPRIOS MEIOS NÃO
$("input#financia_nao").livequery('change', function () {
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#programa_social').selectpicker('deselectAll');
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#programa_social').change();
  $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").find('#porcentagem').val('');
  if ($(this).val() == 1) {
    $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").hide();
  } else {
    $(this).parents("div").parent("div#natureza_instituicao").find("div#proprios_meios").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//REDE PÚBLICA OU PRIVADA
$("select#rede_publica_privada").livequery('change', function () {
  if ($(this).val() == 2) {
    $(this).parents("div").parent("div#estiver_estudando").find("div#natureza_instituicao").show();
  } else {
    $(this).parents("div").parent("div#estiver_estudando").find("div#natureza_instituicao").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//ESTUDANDO SIM
$("input#estudando_sim").livequery('change', function () {

  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#nome_instituicao').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#serie_periodo').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#rede_publica_privada').selectpicker('deselectAll');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#rede_publica_privada').change();
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#programa').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#porcentagem').val('');

  if ($(this).val() == 1) {
    $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").show();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//ESTUDANDO NÃO
$("input#estudando_nao").livequery('change', function () {

  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#nome_instituicao').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#serie_periodo').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#rede_publica_privada').selectpicker('deselectAll');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#rede_publica_privada').change();
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#programa').val('');
  $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").find('#porcentagem').val('');

  if ($(this).val() == 0) {
    $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").hide();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#estiver_estudando").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA SIM
$("input#trabalha_sim").livequery('change', function () {
  if ($(this).val() == 1) {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").show();
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao_mesmo_endereco").show();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").hide();
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao_mesmo_endereco").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NÃO
$("input#trabalha_nao").livequery('change', function () {
  if ($(this).val() == 0) {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").hide();
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao_mesmo_endereco").hide();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").show();
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao_mesmo_endereco").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NO MESMO ENDEREÇO SIM
$("input#trab_mesmo_endereco_sim").livequery('change', function () {
  if ($(this).val() == 1) {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").find("div#trab_mesmo_endereco_sim_nao").hide();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").find("div#trab_mesmo_endereco_sim_nao").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//TRABALHA NO MESMO ENDEREÇO NÃO
$("input#trab_mesmo_endereco_nao").livequery('change', function () {
  if ($(this).val() == 0) {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").find("div#trab_mesmo_endereco_sim_nao").show();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#trabalha_sim_nao").find("div#trab_mesmo_endereco_sim_nao").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//MORA NO MESMO ENDEREÇO SIM
$("input#mora_sim").livequery('change', function () {

//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#cep').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#estado').selectpicker('deselectAll');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#estado').change();
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#municipio').selectpicker('deselectAll');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#municipio').change();
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#logradouro').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#numero').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#bairro').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#quadra').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#casa').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#complemento').val('');
  if ($(this).val() == 1) {
    $(this).parents("div.form-wizard").find("div#mesmo_endereco").hide();
  } else {
    $(this).parents("div.form-wizard").find("div#mesmo_endereco").show();
  }
});

//---------------------------------------------------------------------------------------------------------
//MORA NO MESMO ENDEREÇO NÃO
$("input#mora_nao").livequery('change', function () {
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#cep').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#estado').selectpicker('deselectAll');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#estado').change();
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#municipio').selectpicker('deselectAll');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#municipio').change();
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#logradouro').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#numero').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#bairro').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#quadra').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#casa').val('');
//  $(this).parents("div.row").find("div#mesmo_endereco").parents("div").find('#complemento').val('');

  if ($(this).val() == 0) {
    $(this).parents("div.form-wizard").find("div#mesmo_endereco").show();
  } else {
    $(this).parents("div.form-wizard").find("div#mesmo_endereco").hide();
  }
});

//---------------------------------------------------------------------------------------------------------
//NACIONALIDADE ESTRANGEIRO
$("input#nacionalidade_estrangeiro").livequery('change', function () {

  if ($(this).val() == 0) {
    $(this).parents("div.form-wizard").find("div.row").find("div#estrangeiro").show();
    $(this).parents("div.form-wizard").find("div.row").find("div#brasileiro").hide();
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_estado').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_estado').change();
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_municipio').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_municipio').change();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#estrangeiro").hide();
    $(this).parents("div.form-wizard").find("div.row").find("div#brasileiro").show();
    $(this).parents("div.form-wizard").find("div.row").find("#data_expedicao_1").val('');
    $(this).parents("div.form-wizard").find("div.row").find("#validade").val('');
    $(this).parents("div.form-wizard").find("div.row").find("#cod_rne").val('');
    $(this).parents("div.form-wizard").find("div.row").find('#classificacao').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#classificacao').change();
    $(this).parents("div.form-wizard").find("div.row").find('#pais').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#pais').change();
  }

});

//---------------------------------------------------------------------------------------------------------
//NACIONALIDADE BRASILEIRO
$("input#nacionalidade_brasileiro").livequery('change', function () {

  if ($(this).val() == 1) {
    $(this).parents("div.form-wizard").find("div.row").find("div#estrangeiro").hide();
    $(this).parents("div.form-wizard").find("div.row").find("div#brasileiro").show();
    $(this).parents("div.form-wizard").find("div.row").find("#data_expedicao_1").val('');
    $(this).parents("div.form-wizard").find("div.row").find("#validade").val('');
    $(this).parents("div.form-wizard").find("div.row").find("#cod_rne").val('');
    $(this).parents("div.form-wizard").find("div.row").find('#classificacao').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#classificacao').change();
    $(this).parents("div.form-wizard").find("div.row").find('#pais').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#pais').change();
  } else {
    $(this).parents("div.form-wizard").find("div.row").find("div#estrangeiro").show();
    $(this).parents("div.form-wizard").find("div.row").find("div#brasileiro").hide();
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_estado').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_estado').change();
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_municipio').selectpicker('deselectAll');
    $(this).parents("div.form-wizard").find("div.row").find('#naturalidade_municipio').change();
  }

});
//---------------------------------------------------------------------------------------------------------
//REMOVER FORMULÁRIO
$("a#remover_formulario").livequery('click', function () {
  var op = this;
  swal({
    title: "Você realmente deseja remover este familiar?",
    text: "Você perderá todos os dados preenchidos no familiar!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, remova!",
    cancelButtonText: "Não, cancele!",
    closeOnConfirm: false,
    closeOnCancel: false
  }, function (isConfirm) {
    if (isConfirm) {
      $("#" + $(op).attr("rel")).remove();
      $(op).parent("li").remove();
      $(op).remove();
      swal("Remoção", "Familiar removido com sucesso!", "success");
    } else {
      swal("Cancelado", "Sua solicitação foi cancelada com sucesso!", "error");
    }
  });

});

//---------------------------------------------------------------------------------------------------------
//GRAU PARENTESCO
$("select#grau_parentesco").livequery('change', function () {

  var op = this;

  if ($("li#formulario_add_novo").find("a").attr("aria-expanded") == "true") {

    var contador = $("#contador").val();

    var clone_menu = $("#formulario_add_novo").clone();
    var clone_formulario = $("div#formulario_novo").clone();

    //REMOVENDO OS RADIOS E ATUALIZANDO NOVAMENTE
    $("#formulario_novo").find('input[type="radio"]').each(function (i, obj) {
      if ($(obj).attr("name").match(/_0/)) {
        $(obj).attr("name", $(obj).attr("name").replace("_0", "_" + (contador + 1)));
      }
    });

    $("#formulario_add_novo").after(clone_menu);
    $("div#formulario_novo").after(clone_formulario);

    $("#formulario_add_novo").find("a").attr("href", "#formulario_novo_" + (contador + 1));
    $("#formulario_add_novo").find("a").attr("aria-controls", "formulario_novo_" + (contador + 1));
    $("#formulario_add_novo").find("li").find("a#remover_formulario").attr("rel", "formulario_novo_" + (contador + 1));

    $("#formulario_add_novo").attr("id", "");
    $("#formulario_add_novo").attr("class", "");
    $("#formulario_add_novo").attr("role", "presentation");

    $("#formulario_novo").attr("id", "formulario_novo_" + (contador + 1));
    $("#formulario_novo").removeClass("active");
    $("li#formulario_add_novo").find("a").attr("aria-expanded", "false");

    $("#formulario_novo_" + (contador + 1)).find("input#array_familiar_contador").val(contador + 1);
    $("#formulario_novo_" + (contador + 1)).find("select#tipo_renda").attr("name", "tipo_renda_" + (contador + 1) + "[]");
    $("#formulario_novo_" + (contador + 1)).find("input#renda_valor").attr("name", "renda_valor_" + (contador + 1) + "[]");

    $("#contador").val((contador + 1));

    var grau_parentesco_atualizado = '';

    if ($(op).val() == 3) {//PAI
      $("#formulario_novo_" + (contador + 1)).find("input#masculino").attr("checked", "true");
      $("#formulario_novo_" + (contador + 1)).find("input#feminino").removeAttr("checked");
      grau_parentesco_atualizado = $("#formulario_novo").find("select#grau_parentesco").html().replace('<option rel="Pai" value="3">Pai</option>', '');
    } else if ($(op).val() == 4) {//MÃE
      $("#formulario_novo_" + (contador + 1)).find("input#feminino").attr("checked", "true");
      $("#formulario_novo_" + (contador + 1)).find("input#masculino").removeAttr("checked");
      grau_parentesco_atualizado = $("#formulario_novo").find("select#grau_parentesco").html().replace('<option rel="Mãe" value="4">Mãe</option>', '');
    }

    //VERIFICANDO SE O PAI OU MÃE JÁ FORAM ESCOLHIDOS
    if (grau_parentesco_atualizado != '') {
      $("#formulario_novo").find("select#grau_parentesco").html(grau_parentesco_atualizado);
    }

    //REMOVENDO SELECTS E ATUALIZANDO NOVAMENTE
    $("#formulario_novo").find('div.bootstrap-select').remove();
    $("#formulario_novo").find('select').selectpicker();
    $("#formulario_novo").find("div#clonar").find("input#renda_valor").val("");
    $("#formulario_novo").find("div#clonar").find("input#renda_valor").mask("###.###.###.##0,00", {reverse: true});
    $("#formulario_novo").find("div#clonar").next().remove();

    //ADD AS MÁSCARAS DO FORMULÁRIO
    $("#formulario_novo").find(".date-picker").each(function () {
      $(this).mask('00/00/0000');
      $(this).datetimepicker({format: 'DD/MM/YYYY'});
    });

    $("#formulario_novo").find('input#cpf').mask('000.000.000-00');
    $("#formulario_novo").find('input#cep').mask('00.000-000');
    $("#formulario_novo").find('input#numero').mask('#####');
    $("#formulario_novo").find('input#residencial').mask('(00) 00000-0000');
    $("#formulario_novo").find('input#celular').mask('(00) 00000-0000');
    $("#formulario_novo").find('input#comercial').mask('(00) 00000-0000');
    $("#formulario_novo").find('input#ramal').mask('000000"');

    $("ul#ul_menus").find("li").each(function () {
      if ($(this).find("a").attr("aria-expanded") == "true") {
        $(this).find("a").html($(op).find('option:selected').attr('rel'));
        $(this).find("a").after("<a id='remover_formulario' rel='formulario_novo_" + (contador + 1) + "' href='#' class='excluir-membro'><i class='zmdi zmdi-close-circle'></i></a>");
      }
    });

  } else {

    var contador_pg = $(op).parents("div.form-wizard").find("input#array_familiar_contador").val();

    $("ul#ul_menus").find("li").each(function () {

      $(this).find("a").each(function (a, obj) {
        if ($(obj).attr("href") == "#formulario_" + contador_pg || $(obj).attr("href") == "#formulario_novo_" + contador_pg) {
          $(obj).html($(op).find('option:selected').attr('rel'));
        }
      });
    });
  }

});

//---------------------------------------------------------------------------------------------------------
//DOCUMENTO 1
$("select#documento1").livequery('change', function () {

  if ($(this).val() == 1) {
    $(this).parent("div").parent("div.row").find("div#documento_1_1").show();
    $(this).parent("div").parent("div.row").find("#numero_registro_2").val('');
    $(this).parent("div").parent("div.row").find("#uf_expedicao_2").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#uf_expedicao_2').change();
    $(this).parent("div").parent("div.row").find("#data_expedicao_2").val('');
    $(this).parent("div").parent("div.row").find("#data_validade_2").val('');
    $(this).parent("div").parent("div.row").find("div#documento_1_2").hide();
  } else if ($(this).val() == 2) {
    $(this).parent("div").parent("div.row").find("div#documento_1_1").hide();
    $(this).parent("div").parent("div.row").find("#numero_registro").val('');
    $(this).parent("div").parent("div.row").find("#orgao_expedidor").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#orgao_expedidor').change();
    $(this).parent("div").parent("div.row").find("#uf_expedicao").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#uf_expedicao').change();
    $(this).parent("div").parent("div.row").find("#data_expedicao").val('');
    $(this).parent("div").parent("div.row").find("div#documento_1_2").show();
  } else { 
    $(this).parent("div").parent("div.row").find("div#documento_1_1").hide();
    $(this).parent("div").parent("div.row").find("div#documento_1_2").hide();
    $(this).parent("div").parent("div.row").find("#numero_registro").val('');
    $(this).parent("div").parent("div.row").find("#orgao_expedidor").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#orgao_expedidor').change();
    $(this).parent("div").parent("div.row").find("#uf_expedicao").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#uf_expedicao').change();
    $(this).parent("div").parent("div.row").find("#data_expedicao").val('');
    $(this).parent("div").parent("div.row").find("#numero_registro_2").val('');
    $(this).parent("div").parent("div.row").find("#uf_expedicao_2").selectpicker('deselectAll');
    $(this).parent("div").parent("div.row").find('#uf_expedicao_2').change();
    $(this).parent("div").parent("div.row").find("#data_expedicao_2").val('');
    $(this).parent("div").parent("div.row").find("#data_validade_2").val('');
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
function consultacep(op) {

  var cep = $(op).val();

  if (cep.replace(/[^\d]+/g, '').length == 8) {
    //$("#div_loader").show();
    projetouniversal.util.getjson({
      url: PORTAL_URL + "assets/plugins/busca-cep-correios/cep.php",
      type: "POST",
      data: {cep: cep},
      enctype: 'multipart/form-data',
      success: function (obj) {

        if (obj.msg == 'success') {
          $(op).parents("div#mesmo_endereco").find("#logradouro").val = obj.logradouro;
          $(op).parents("div#mesmo_endereco").find("#bairro").val = obj.bairro;

          $(op).parents("div#mesmo_endereco").find("#bairro").val(obj.bairro).trigger("change");
          $(op).parents("div#mesmo_endereco").find("#logradouro").val(obj.logradouro).trigger("change");

          var c = $(op).parents("div#mesmo_endereco").find("#estado option");

          for (var i = 0; i < c.length; i++)
          {
            if (c[i].label == obj.uf)
            {
              var val = c[i].value;
              $(op).parents("div#mesmo_endereco").find("#estado").val(val).trigger("change");
              break;
            }
            $(op).parents("div#mesmo_endereco").find("#estado").change();
          }

          if (carrega_municipio(op, obj.cidade)) {
            $(op).parents("div#mesmo_endereco").find("#municipio").change();
            //$("#div_loader").hide();
          }
        }
        //$("#div_loader").hide();
        return false;
      },
      error: onErrorConsultaCep
    });
    return false;
  }
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
function carrega_municipio(op, municipio) {

  $(op).parents("div#mesmo_endereco").find("select#municipio").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/cidades.php",
          {estado: $(op).parents("div#mesmo_endereco").find("#estado").val()},
  function (data) {

    $(op).parents("div#mesmo_endereco").find("select#municipio").html(data);
    $(op).parents("div#mesmo_endereco").find("select#municipio").change();

    if (isNaN(data)) {

      var e = $(op).parents("div#mesmo_endereco").find("#municipio option");

      for (var i = 0; i < e.length; i++) {

        $(op).parents("div#mesmo_endereco").find("select#municipio").change();

        if (e[i].label == municipio)
        {
          var val = e[i].value;
          $(op).parents("div#mesmo_endereco").find("select#municipio").val(val).trigger("change");
          break;
        }
      }
    }
    return true;
  });
}
//---------------------------------------------------------------------------------------------------------

$("select#capitulo").livequery('change', function () {
  var op = this;
  $(op).parent("div").parent("div").find("select#grupo").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/grupos.php",
          {catinic1: $(op).find('option:selected').attr('catinic1'), catfim1: $(op).find('option:selected').attr('catfim1')},
  function (valor) {
    $(op).parent("div").parent("div").find("select#grupo").html(valor);
    $(op).parent("div").parent("div").find('select#grupo').selectpicker('refresh');
  });
});

$("select#grupo").livequery('change', function () {
  var op = this;
  $(op).parent("div").parent("div").find("select#categoria").html('<option value="0">Carregando...</option>');
  $.post(PORTAL_URL + "hab/dao/basico/combo/categorias.php",
          {catinic2: $(op).find('option:selected').attr('catinic2'), catfim2: $(op).find('option:selected').attr('catfim2')},
  function (valor) {
    $(op).parent("div").parent("div").find("select#categoria").html(valor);
    $(op).parent("div").parent("div").find('select#categoria').selectpicker('refresh');
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
  $(this).parents("div#clonar").next().find("div#div_tipo_renda").find('div.bootstrap-select').remove();
  $(this).parents("div#clonar").next().find("div#div_tipo_renda").find('select#tipo_renda').val("");
  $(this).parents("div#clonar").next().find("div#div_tipo_renda").find('select#tipo_renda').selectpicker();

});
//--------------------------------------------------------------------------------------------------------- 
$("a#remover_renda").livequery("click", function () {
  $(this).parents("div#clonar").remove();
});
//---------------------------------------------------------------------------------------------------------  