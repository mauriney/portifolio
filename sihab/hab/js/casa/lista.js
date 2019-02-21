/*---------------------------------------------------------------------------------------------------------
 DATA: 14/06/2017 ÀS 16:56
 NOME: JS DA CLASSE DE LISTA DE CASA
 DESENVOLVEDOR: NIRO LIMA
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
        if ($("tr#casa_tr_" + row.id).attr('rel') != 2 && $("tr#casa_tr_" + row.id).attr('sorteio') == 0) {
          return "<button onclick='remover_casa(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
        }
      },
      "edita": function (column, row) {
        if ($("tr#casa_tr_" + row.id).attr('rel') != 2) {
          return "<a href='" + PORTAL_URL + "sistema/casa/cadastro/" + row.id + "'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a> ";
        }
      },
      "geral": function (column, row) {
        if ($("tr#casa_tr_" + row.id).attr('rel') != 2) {
          var resultado = "";

          resultado = "<a href='" + PORTAL_URL + "sistema/casa/cadastro/" + row.id + "'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a> ";

          if ($("tr#casa_tr_" + row.id).attr('sorteio') == 0) {
            resultado += "<button onclick='remover_casa(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
          }

          return resultado;
        }
      }
    }
  });
});

//---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA PESQUISAR DINÂMICO
function pesquisa(valor, modulo, objeto) {
  url = PORTAL_URL + "hab/dao/casa/busca.php?valor=" + valor + "&modulo=" + modulo + "&objeto=" + objeto;
  ajax(url);
}
//---------------------------------------------------------------------------------------------------------
//REMOVER CASA
function remover_casa(id) {

  swal({
    title: "Você realmente deseja remover esta casa?",
    text: "Você não será capaz de recuperar essa casa após a sua remoção!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Sim, remova!",
    cancelButtonText: "Não, cancele!",
    closeOnConfirm: false,
    closeOnCancel: false
  }, function (isConfirm) {
    if (isConfirm) {
      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/casa/remover',
        data: {id: id},
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Remoção de Casa",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/casa/lista'", 1);
            });

          } else if (obj.msg == 'error') {
            swal({
              title: "Remoção de Casa",
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
            title: "Remoção de Casa",
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


