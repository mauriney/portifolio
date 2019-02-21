/*---------------------------------------------------------------------------------------------------------
 DATA: 31/08/2016 ÀS 09:09
 NOME: JS DA CLASSE CADASTRO DE CANDIDATO ETAPA 4
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//---------------------------------------------------------------------------------------------------------
//VALIDAÇÃO DE ENVIO DE FORMULÁRIO
  $('form#form_candidato_etapa4').submit(function () {

    var qtd = 0;

    $("div#form_titular").find("div.card-body").find("div.row").find("div#div_anexo").find("div.fileinput-preview").each(function () {
      if ($(this).html() != "") {
        qtd++;
      }
    });

    $("div#form_titular").find("div.card-body").find("div.row").find("div#div_anexo").find("input#anexo_qtd").val(qtd - 1);

  });
//---------------------------------------------------------------------------------------------------------
//FOTO
  $('input[type="file"]').livequery("change", function () {
    if ($(this).val() != "") {
      $(this).parents("div#div_anexo").find("input#foto_caminho").val('');
    }
    return false;
  });
//---------------------------------------------------------------------------------------------------------
  $("a#remover_foto").livequery("click", function () {
    if ($(this).parents("div#div_anexo").find("#foto_caminho").val() != "") {
      var pessoa_id = $(this).parents("div#div_anexo").find("input#pessoa_id").val();
      var campo = $(this).parents("div#div_anexo").find("input#tabela_campo").val();
      swal({
        title: "Você deseja realmente remover o anexo?",
        text: "Você não será capaz de recuperar mais este arquivo!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sim, remova!",
        cancelButtonText: "Não, remova!",
        closeOnConfirm: false,
        closeOnCancel: false
      }, function (isConfirm) {
        if (isConfirm) {
          $.ajax({
            type: "POST",
            url: PORTAL_URL + 'hab/dao/pessoa/remover_anexo',
            data: {pessoa_id: pessoa_id, campo: campo},
            cache: false,
            success: function (obj) {
              obj = JSON.parse(obj);
              if (obj.msg == 'success') {
                swal({
                  title: "Formulário de Candidato",
                  text: "Anexo removido com sucesso!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#8CD4F5",
                  confirmButtonText: "OK",
                  closeOnConfirm: false
                }, function () {

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
          swal({
            title: "Formulário de Candidato",
            text: "Sua solicitação foi efetuada com sucesso!",
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          }, function () {
            setTimeout("location.href='" + PORTAL_URL + "sistema/candidato/etapa4/" + $("#candidato_id").val() + "'", 1);
          });
        } else {
          swal("Cancelado", "Sua solicitação foi cancelada com sucesso!", "error");
        }
      });
      return false;
    }
  });
//---------------------------------------------------------------------------------------------------------
//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");
});
//---------------------------------------------------------------------------------------------------------
$('span#arquivo_enviar').find('input#outro_anexo').livequery("change", function () {
  $(this).parents('div#clonado').find('div#botoes').find('input#anexo_nome').val($(this).val());
});

//---------------------------------------------------------------------------------------------------------
$('a#anexo_foto_add').livequery('click', function () {

  $(this).parent('div#botoes').find('a#anexo_foto_remove').show();
  $(this).parent('div#botoes').find('a#anexo_foto_add').hide();

  var clone = $(this).parents('div#clone_principal').find('div#clonado').clone();

  $(this).parent('div#botoes').find('a#anexo_foto_remove').hide();
  $(this).parent('div#botoes').find('a#anexo_foto_add').show();

  $(this).parents('div#clonado').parent('div').find('div#clones').html(clone);

  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find('.fileinput').removeClass('fileinput-exists');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find('.fileinput').addClass('fileinput-new');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find(".fileinput-filename").html('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("input#arquivos").val('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("input#anexo_id").val('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("input#anexo_nome").val('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("input#anexo_endereco").val('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("a#remover_foto_temp").hide();
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find("input#nome_digitado").val('');
  $(this).parents('div#clonado').parent('div').find('div#clones').find('div#clonado:first').find('label#erro_nome_digitado').hide();

  $(this).parent('div#botoes').find('input#index_anexo_outro').val(this);

  return false;

});
//---------------------------------------------------------------------------------------------------------
$('a#anexo_foto_remove').livequery('click', function () {

  if ($(this).parents('div').find('div#clonado').length > 0) {
    $(this).parents('div#clonado').remove();
  }

  $(this).parents('div#clonado').find("input#anexo_id").val('');
  $(this).parents('div#clonado').find("input#anexo_nome").val('');
  $(this).parents('div#clonado').find("input#anexo_endereco").val('');

  return false;
});
//---------------------------------------------------------------------------------------------------------
$('a#remover_foto_temp').livequery('click', function () {

  $(this).parents('div#clonado').find("input#anexo_id").val('');
  $(this).parents('div#clonado').find("input#anexo_nome").val('');
  $(this).parents('div#clonado').find("input#anexo_endereco").val('');

  return false;
});
//---------------------------------------------------------------------------------------------------------
$('a#obs_add').livequery('click', function () {

  $(this).parent('div#botoes_obs').find('a#obs_remove').show();
  $(this).parent('div#botoes_obs').find('a#obs_add').hide();

  var clone = $(this).parents('div#clone_principal_obs').find('div#clonado_obs').clone();

  $(this).parent('div#botoes_obs').find('a#obs_remove').hide();
  $(this).parent('div#botoes_obs').find('a#obs_add').show();

  $(this).parents('div#clonado_obs').parent('div').find('div#clones_obs').html(clone);

  $(this).parents('div#clonado_obs').parent('div').find('div#clones_obs').find('div#clonado_obs:first').find("input#observacao_id").val('');
  $(this).parents('div#clonado_obs').parent('div').find('div#clones_obs').find('div#clonado_obs:first').find("textarea#observacao").val('');

  $(this).parent('div#botoes_obs').find('input#index_anexo_outro_obs').val(this);

  return false;

});
//---------------------------------------------------------------------------------------------------------
$('a#obs_remove').livequery('click', function () {

  if ($(this).parents('div').find('div#clonado_obs').length > 0) {
    $(this).parents('div#clonado_obs').remove();
  }

  $(this).parents('div#clonado_obs').find("input#observacao_id").val('');
  $(this).parents('div#clonado_obs').find("textarea#observacao").val('');

  return false;
});
//---------------------------------------------------------------------------------------------------------