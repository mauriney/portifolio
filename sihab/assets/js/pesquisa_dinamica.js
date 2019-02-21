// JavaScript Document
// FUNÇÃO RESPONSÁVEL DE CONECTAR A UMA PAGINA EXTERNA NO NOSSO CASO A BUSCA_NOME.PHP
// E RETORNAR OS RESULTADOS

function ajax(url)
{

//alert(nick);
//alert(dest);
//alert(msg);

  req = null;
  // Procura por um objeto nativo (Mozilla/Safari)
  if (window.XMLHttpRequest) {
    req = new XMLHttpRequest();
    req.onreadystatechange = processReqChange;
    req.open("GET", url, true);
    req.send(null);
    // Procura por uma versão ActiveX (IE)
  } else if (window.ActiveXObject) {
    req = new ActiveXObject("Microsoft.XMLHTTP");
    if (req) {

      req.onreadystatechange = processReqChange;
      req.open("GET", url, true);

      req.send();
    }
  }
}

function processReqChange()
{

  // apenas quando o estado for "completado"
  if (req.readyState == 4) {

    // apenas se o servidor retornar "OK"

    if (req.status == 200) {

      // procura pela div id="pagina" e insere o conteudo
      // retornado nela, como texto HTML
      document.getElementById('pagina').innerHTML = "";//Acrecentado 09-06-2015
      document.getElementById('pagina').innerHTML = req.responseText;

      //$('#example3').DataTable().destroy();

      //datatables_create();//CHAMANDO A FUNÇÃO DATATABLES_EXPORT

      $(".dt-buttons").hide();

    } else {
      alert("Houve um problema ao obter os dados:" + req.statusText);
    }
  }
} 

function ajax2(url)
{

//alert(nick);
//alert(dest);
//alert(msg);

  req2 = null;
  // Procura por um objeto nativo (Mozilla/Safari)
  if (window.XMLHttpRequest) {
    req2 = new XMLHttpRequest();
    req2.onreadystatechange = processReqChange2;
    req2.open("GET", url, true);
    req2.send(null);
    // Procura por uma versão ActiveX (IE)
  } else if (window.ActiveXObject) {
    req2 = new ActiveXObject("Microsoft.XMLHTTP");
    if (req2) {

      req2.onreadystatechange = processReqChange2;
      req2.open("GET", url, true);

      req2.send();
    }
  }
}

function processReqChange2()
{

  // apenas quando o estado for "completado"
  if (req2.readyState == 4) {

    // apenas se o servidor retornar "OK"

    if (req2.status == 200) {

      // procura pela div id="pagina" e insere o conteudo
      // retornado nela, como texto HTML
      document.getElementById('pagina2').innerHTML = "";//Acrecentado 09-06-2015
      document.getElementById('pagina2').innerHTML = req2.responseText;

     // $('#example3').DataTable().destroy();

     // datatables_create();//CHAMANDO A FUNÇÃO DATATABLES_EXPORT

      $(".dt-buttons").hide();

    } else {
      alert("Houve um problema ao obter os dados:" + req2.statusText);
    }
  }
} 