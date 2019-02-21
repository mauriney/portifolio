/*---------------------------------------------------------------------------------------------------------
 DATA: 20/02/2017 ÀS 16:56
 NOME: JS DA CLASSE DE LISTA DE LOTEAMENTO
 DESENVOLVEDOR: MAURINEY R. DA COSTA
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
      // "commands": function (column, row) {
      //   return "<a href='" + PORTAL_URL + "sistema/acao/cadastro/" + row.id + "'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a> " +
      //           "<button onclick='remover_acao(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
      // }
      "exclui" : function(column, row) {
            return "<button onclick='remover_loteamento(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
        },
        "edita" : function(column, row) {
            return "<a href='" + PORTAL_URL + "sistema/loteamento/cadastro/" + row.id + "'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a> ";
        },
        "geral" : function(column, row) {
            return "<a href='" + PORTAL_URL + "sistema/loteamento/cadastro/" + row.id + "'><button type=\"button\" class=\"btn btn-icon palette-Cyan bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button></a> " +
                "<button onclick='remover_loteamento(" + row.id + ")' type=\"button\" class=\"btn btn-icon btn-danger bg waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
        }
      }
    });
  });

//---------------------------------------------------------------------------------------------------------
//FUNÇÃO PARA PESQUISAR DINÂMICO
function pesquisa(valor, modulo, objeto) {
  url = PORTAL_URL + "hab/dao/loteamento/busca.php?valor=" + valor + "&modulo=" + modulo + "&objeto=" + objeto;
  ajax(url);
}
//---------------------------------------------------------------------------------------------------------
//REMOVER AÇÃO
function remover_loteamento(id) {

  swal({
    title: "Você realmente deseja remover este loteamento?",
    text: "Você não será capaz de recuperar esse loteamento após a sua remoção!",
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
        url: PORTAL_URL + 'hab/dao/loteamento/remover',
        data: {id: id},
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {
            swal({
              title: "Remoção de Loteamento",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/loteamento/lista'", 1);
            });

          } else if (obj.msg == 'error') {
            swal({
              title: "Remoção de Loteamento",
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
            title: "Remoção de Loteamento",
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


