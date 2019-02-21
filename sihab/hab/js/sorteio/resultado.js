/*---------------------------------------------------------------------------------------------------------
 DATA: 29/07/2016 ÀS 14:52
 NOME: JS DA CLASSE DE LISTA DE RESULTADO DO SORTEIO
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
  //Basic Example
  $("#data-table-basic").bootgrid({
    css: {
      icon: 'zmdi icon',
      iconColumns: 'zmdi-view-module',
      iconDown: 'zmdi-expand-more',
      iconRefresh: 'zmdi-refresh',
      iconUp: 'zmdi-expand-less'
    },
    formatters: {
      "exclui": function (column, row) {
        if (row.status == "Ativo") {
          return "<button onclick='remover_sorteio(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
        }
      }
    }
  });
});

$("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");

//---------------------------------------------------------------------------------------------------------
//CARREGAR RESULTADO DO SORTEIO PELA DATA
$("select#data_cadastro").livequery('change', function () {
  $("form#form_resultado").submit();
});
//---------------------------------------------------------------------------------------------------------
//REMOVER SORTEIO
function remover_sorteio(id) {

  swal({
    title: "Você realmente deseja realizar a desistência desse candidato?",
    text: "Você não será capaz de recuperar essa ação após a sua confirmação!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, realize!",
    cancelButtonText: "Não, cancele!",
    closeOnConfirm: false,
    closeOnCancel: false
  }, function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/sorteio/desistencia',
        data: {id: id},
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Desistência de Candidato",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/sorteio/resultado'", 1);
            });

          } else if (obj.msg == 'error') {
            swal({
              title: "Desistência de Candidato",
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
            title: "Desistência de Candidato",
            text: obj.retorno,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
        }
      });
    } else {
      swal("Cancelado", "Sua solicitação foi cancelada com sucesso!", "error");
    }
  });


}
//---------------------------------------------------------------------------------------------------------


