/*---------------------------------------------------------------------------------------------------------
 DATA: 14/06/2017 ÀS 16:54
 NOME: JS DA CLASSE CADASTRO DE CASA
 DESENVOLVEDOR: NIRO LIMA
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {

//Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/casa/lista");

//---------------------------------------------------------------------------------------------------------
//CADASTRO DE CASA
  $('form#form_casa').submit(function () {

    $("#div_loader").show();

      $.ajax({
        type: "POST",
        url: PORTAL_URL + 'hab/dao/casa/apto',
        data: $('#form_casa').serialize(),
        cache: false,
        success: function (obj) {
          obj = JSON.parse(obj);
          if (obj.msg == 'success') {

            swal({
              title: "Formulário de Casa",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
              setTimeout("location.href='" + PORTAL_URL + "sistema/casa/apto'", 1);
            });

            return false;

          } else if (obj.msg == 'error') {
            swal({
              title: "Formulário de Casa",
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
            title: "Formulário de Casa",
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
  });
//---------------------------------------------------------------------------------------------------------
});