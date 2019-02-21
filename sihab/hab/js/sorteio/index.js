/*---------------------------------------------------------------------------------------------------------
 DATA: 10/04/2017 ÀS 10:40
 NOME: JS DA CLASSE INDEX
 ---------------------------------------------------------------------------------------------------------*/
$(document).ready(function () {
//MACHINE 1 REFERENTE A CASA 1
  var machine1 = $("#machine1").slotMachine({
    active: 1,
    delay: 500
  });
  function onComplete(active) {
    switch (this.element[0].id) {
      case 'machine1':
        $("#machine1Result").text("Index: " + this.active);
        break;
    }
  }

//AO CLICAR PARA SORTEAR VAI CAIR NO CLICK ABAIXO
  $("#ranomizeButton").click(function () {

    machine1.shuffle(5, onComplete); //COMEÇA A GIRAR A MACHINE 1

    //ABAIXO ABRE O CAMINHO 'hab/dao/sorteio/index' E GERA O SORTEIO E SALVAMENTO NO BANCO DE DADOS
    $.ajax({
      type: "POST",
      url: PORTAL_URL + 'hab/dao/sorteio/index',
      cache: false,
      success: function (obj) {
        obj = JSON.parse(obj);
        if (obj.msg == 'success') {

          setTimeout(function () {
            setTimeout("location.href='" + PORTAL_URL + "hab/view/sorteio/index'", 1);
          }, 1500);
          return false;
          
        } else if (obj.msg == 'error') {
          swal({
            title: "Sorteio de Casas",
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
          title: "Sorteio de Casas",
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
  });
//---------------------------------------------------------------------------------------------------------
//  $("button#imprimir").click(function () {
//    //pega o Html da DIV
//    var divElements = document.getElementById("tabela_impressao").innerHTML;
//    //pega o HTML de toda tag Body
//    var oldPage = document.body.innerHTML;
//
//    //Alterna o body 
//    document.body.innerHTML =
//            "<html><head><title></title></head><body>" +
//            divElements + "</body>";
//
//    //Imprime o body atual
//    window.print();
//
//    //Retorna o conteudo original da página. 
//    document.body.innerHTML = oldPage;
//    
//  });
});
//---------------------------------------------------------------------------------------------------------