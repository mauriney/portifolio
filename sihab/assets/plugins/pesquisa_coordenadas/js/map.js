// *
// * Pesquisa de coordenadas do Google Maps
// * 2013 - www.marnoto.com
// *

// Váriáveis necessárias
var map;
var marker;
var geocoder;

function initialize() {

  geocoder = new google.maps.Geocoder();

  var mapOptions = {
    center: new google.maps.LatLng(-9.9644469, -67.8296498),
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  codeAddress();

  // Evento que detecta o click no mapa para criar o marcador
  google.maps.event.addListener(map, "click", function (event) {

    $("div#div_latitude").addClass('fg-toggled');
    $("div#div_longitude").addClass('fg-toggled');

    // O evento "click" retorna a posição do click no mapa,
    // através dos métodos latLng.lat() e latLng.lng().

    // Passamos as respectivas coordenadas para as variáveis lat e lng
    // para posterior referência.
    // Utilizamos o método toFixed(6) para limitar o número de casas decimais.
    // A API ignora os valores além da 6ª casa decimal
    var lat = event.latLng.lat().toFixed(6);
    var lng = event.latLng.lng().toFixed(6);

    // A criação do marcador é feita na função createMarker() e
    // passamos os valores das coordenadas do click através
    // dos parâmetros lat e lng.
    createMarker(lat, lng);

    // getCoords() actualiza os valores de Latitue e Longitude
    // das caixas de texto existentes no topo da página
    getCoords(lat, lng);

  });
}

google.maps.event.addDomListener(window, 'load', initialize);

// Função que cria o marcador
function createMarker(lat, lng) {

  // A intenção é criar um único marcador, por isso
  // verificamos se já existe um marcador no mapa.
  // Assim cada vez que é feito um click no mapa
  // o anterior marcador é removido e é criado outro novo.

  // Se a variável marker contém algum valor
  if (marker) {
    // remover esse marcador do mapa
    marker.setMap(null);
    // remover qualquer valor da variável marker
    marker = "";
  }

  // definir a variável marker com os novos valores
  marker = new google.maps.Marker({
    // Define a posição do marcador através dos valores lat e lng
    // que foram definidos através do click no mapa
    position: new google.maps.LatLng(lat, lng),
    // Esta opção permite que o marcador possa ser arrastado
    // para um posicionamento com maior precisão.
    draggable: true,
    map: map
  });


  // Evento que detecta o arrastar do marcador para
  // redefinir as coordenadas lat e lng e
  // actualiza os valores das caixas de texto no topo da página
  google.maps.event.addListener(marker, 'dragend', function () {

    // Actualiza as coordenadas de posição do marcador no mapa
    marker.position = marker.getPosition();

    // Redefine as variáveis lat e lng para actualizar
    // os valores das caixas de texto no topo
    var lat = marker.position.lat().toFixed(6);
    var lng = marker.position.lng().toFixed(6);

    // Chamada da função que actualiza os valores das caixas de texto
    getCoords(lat, lng);

  });
}

// Função que actualiza as caixas de texto no topo da página
function getCoords(lat, lng) {

  // Referência ao elemento HTML (input) com o id 'lat'
  var coords_lat = document.getElementById('lat');

  // Actualiza o valor do input 'lat'
  coords_lat.value = lat;

  // Referência ao elemento HTML (input) com o id 'lng'
  var coords_lng = document.getElementById('lng');

  // Actualiza o valor do input 'lng'
  coords_lng.value = lng;
}

function codeAddress() {

  var address = document.getElementById('tipo_logradouro').value + " " + document.getElementById('logradouro').value + " " + document.getElementById('bairro').value + " " + document.getElementById('numero').value;
  if (address != "   ") {
    geocoder.geocode({
      'address': address
    }, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        map.setZoom(16);
        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          position: results[0].geometry.location
        });

        var latlng = "" + results[0].geometry.location + "";

        latlng = latlng.replace("(", "").replace(")", "");

        var novoArray = latlng.split(',');

        $("div#div_latitude").addClass('fg-toggled');
        $("div#div_longitude").addClass('fg-toggled');
        $("input#lat").val(novoArray[0]);
        $("input#lng").val(novoArray[1]);

      }
    });
  }
}