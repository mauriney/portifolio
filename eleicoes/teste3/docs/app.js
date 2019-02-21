var app = new Vue({
  el: '#app',
  data: {
    scanner: null,
    activeCameraId: null,
    cameras: [],
    scans: []
  },
  mounted: function () {
    var self = this;
    self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
    self.scanner.addListener('scan', function (content, image) {
      self.scans.unshift({ date: +(Date.now()), content: content });
      document.getElementById("yourInputFieldId").value = content;
      
      //Pegando as Informações do QRBU
      var retorno = content.split(" ");
      
      var has = content.split("HASH:");
      var has_antigo = $('input#hash').val();
      
      if(has_antigo == "" || has_antigo.indexOf(has[1]) == -1){
         $('input#hash').val(""+retorno[0]+": "+has[1]+", "+has_antigo);
      }
      
       //Pegando as Informações da Zona
        var zon = content.split("ZONA:");
        var zona = "";
        
        if(zon[1] !== undefined){
            
            zona = zon[1].split(' '); 
            
            if(!isNaN(zona[0])){
              $('strong#zona').html(zona[0] < 10 ? '000'+zona[0] : (zona[0] < 100 ? '00'+zona[0] : (zona[0] < 1000 ? '0'+zona[0] : zona[0])));
            }
        }
        
        //Pegando as Informações da Seção
        var sec = content.split("SECA:");
        var secao = "";
        
         if(sec[1] !== undefined){
            
            secao = sec[1].split(' '); 
            
            if(!isNaN(secao[0])){
              $('strong#secao').html(secao[0] < 10 ? '000'+secao[0] : (secao[0] < 100 ? '00'+secao[0] : (secao[0] < 1000 ? '0'+secao[0] : secao[0])));
            }
        }
        
       //Pegando as Informações do Local
        var loc = content.split("LOCA:");
        var local = "";
        
         if(loc[1] !== undefined){
            
            local = loc[1].split(' '); 
            
            if(!isNaN(local[0])){
              $('strong#local').html(local[0] < 10 ? '000'+local[0] : (local[0] < 100 ? '00'+local[0] : (local[0] < 1000 ? '0'+local[0] : local[0])));
            }
        }
        
        //Pegando as Informações do Eleitores Aptos
        var apt = content.split("APTO:");
        var aptos = "";
        
         if(apt[1] !== undefined){
            
            aptos = apt[1].split(' '); 
            
            if(!isNaN(aptos[0])){
              $('strong#aptos').html(aptos[0] < 10 ? '000'+aptos[0] : (aptos[0] < 100 ? '00'+aptos[0] : (aptos[0] < 1000 ? '0'+aptos[0] : aptos[0])));
            }
        }
        
        //Pegando as Informações do Eleitores Comparecimento
        var comp = content.split("COMP:");
        var comparecimentos = "";
        
         if(comp[1] !== undefined){
            
            comparecimentos = comp[1].split(' '); 
            
            if(!isNaN(comparecimentos[0])){
              $('strong#comparecimentos').html(comparecimentos[0] < 10 ? '000'+comparecimentos[0] : (comparecimentos[0] < 100 ? '00'+comparecimentos[0] : (comparecimentos[0] < 1000 ? '0'+comparecimentos[0] : comparecimentos[0])));
            }
        }
        
        //Pegando as Informações do Eleitores Faltosos
        var falt = content.split("FALT:");
        var faltosos = "";
        
         if(falt[1] !== undefined){
            
            faltosos = falt[1].split(' '); 
            
            if(!isNaN(faltosos[0])){
              $('strong#faltosos').html(faltosos[0] < 10 ? '000'+faltosos[0] : (faltosos[0] < 100 ? '00'+faltosos[0] : (faltosos[0] < 1000 ? '0'+faltosos[0] : faltosos[0])));
            }
        }
        
        //Pegando as Informações do Governador 13
        var gov_13 = content.split(" 93:");
        var governador_13 = "";
        
         if(gov_13[1] !== undefined){
            
            governador_13 = gov_13[1].split(' '); 
            
            if(!isNaN(governador_13[0])){
              $('div#gov_13').html(governador_13[0]);
            }
        }
        //Pegando as Informações do Governador 17
        var gov_17 = content.split(" 94:");
        var governador_17 = "";
        
         if(gov_17[1] !== undefined){
            
            governador_17 = gov_17[1].split(' '); 
            
            if(!isNaN(governador_17[0])){
              $('div#gov_17').html(governador_17[0]);
            }
        }
       //Pegando as Informações do Governador 18
        var gov_18 = content.split(" 89:");
        var governador_18 = "";
        
         if(gov_18[1] !== undefined){
            
            governador_18 = gov_18[1].split(' '); 
            
            if(!isNaN(governador_18[0])){
              $('div#gov_18').html(governador_18[0]);
            }
        }
        //Pegando as Informações do Governador 11
        var gov_11 = content.split(" 92:");
        var governador_11 = "";
        
         if(gov_11[1] !== undefined){
            
            governador_11 = gov_11[1].split(' '); 
            
            if(!isNaN(governador_11[0])){
              $('div#gov_11').html(governador_11[0]);
            }
        }
       //Pegando as Informações do Governador 70
        var gov_70 = content.split(" 91:");
        var governador_07 = "";
        
         if(gov_70[1] !== undefined){
            
            governador_70 = gov_70[1].split(' '); 
            
            if(!isNaN(governador_70[0])){
              $('div#gov_70').html(governador_70[0]);
            }
        }
        
          if(gov_13[1] !== undefined){
            //Pegando as Informações do Eleitores Brancos
            var bran = gov_13[1].split("BRAN:");
            var brancos = "";
            
             if(bran[1] !== undefined){
                
                brancos = bran[1].split(' '); 
                
                if(!isNaN(brancos[0])){
                  $('strong#brancos').html(brancos[0] < 10 ? '000'+brancos[0] : (brancos[0] < 100 ? '00'+brancos[0] : (brancos[0] < 1000 ? '0'+brancos[0] : brancos[0])));
                }
            }
            
            //Pegando as Informações do Eleitores Nulos
            var nul = gov_13[1].split("NULO:");
            var nulos = "";
            
             if(nul[1] !== undefined){
                
                nulos = nul[1].split(' '); 
                
                if(!isNaN(nulos[0])){
                  $('strong#nulos').html(nulos[0] < 10 ? '000'+nulos[0] : (nulos[0] < 100 ? '00'+nulos[0] : (nulos[0] < 1000 ? '0'+nulos[0] : nulos[0])));
                }
            }
          }
      
    swal({
            title: "Leitura do QR CODE "+retorno[0],
            text: 'QR Code lido com sucesso!',
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#8CD4F5",
            confirmButtonText: "OK",
            closeOnConfirm: false
        });
        
        //Verificando a % da barra
        var qtd_barra = 0;
        if($('strong#zona').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#secao').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#local').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#aptos').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#comparecimentos').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#faltosos').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#brancos').html() != '0000'){
            qtd_barra+=1;
        }
         if($('strong#nulos').html() != '0000'){
            qtd_barra+=1;
        }
        
         if($('div#gov_13').html() != '0'){
            qtd_barra+=1;
        }
         if($('div#gov_17').html() != '0'){
            qtd_barra+=1;
        }
         if($('div#gov_18').html() != '0'){
            qtd_barra+=1;
        }
         if($('div#gov_11').html() != '0'){
            qtd_barra+=1;
        }
         if($('div#gov_70').html() != '0'){
            qtd_barra+=1;
        }
        
        //Preenchendo a barra de progesso
        $('div#barra_progesso').attr('style','width: '+(qtd_barra * 7.69)+'%;');
        
        if(qtd_barra == 13){
            $('button#botao_confirmar').removeClass('btn-default');
            $('button#botao_confirmar').addClass('btn-success');
            $('button#botao_confirmar').removeAttr('disabled');
             var topPosition = $('button#botao_confirmar').offset().top - 135;
                $('html, body').animate({
                  scrollTop: topPosition
                }, 800);
        }
        
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      self.cameras = cameras;
      if (cameras.length == 1) {
        self.activeCameraId = cameras[0].id;
        self.scanner.start(cameras[0]);
      }else if (cameras.length > 1) {
        self.activeCameraId = cameras[1].id;
        self.scanner.start(cameras[1]);
      } else {
        console.error('Nenhuma Câmera Encontrada.');
      }
    }).catch(function (e) {
      console.error(e);
    });
  },
  methods: {
    formatName: function (name) {
      return name || '(Sem Nome)';
    },
    selectCamera: function (camera) {
      this.activeCameraId = camera.id;
      this.scanner.start(camera);
    }
  }
});
//----------------------------------------------------------------------------------------
//Enviando as Informações Coletadas ao Banco de Dados
$('button#botao_confirmar').click(function(){
    
    var zona = $('strong#zona').html();
    var secao = $('strong#secao').html();
    var local = $('strong#local').html();
    var aptos = $('strong#aptos').html();
    var comparecimentos = $('strong#comparecimentos').html();
    var faltosos = $('strong#faltosos').html();
    var brancos = $('strong#brancos').html();
    var nulos = $('strong#nulos').html();
    
    var gov_13 = $('div#gov_13').html();
    var gov_17 = $('div#gov_17').html();
    var gov_18 = $('div#gov_18').html();
    var gov_11 = $('div#gov_11').html();
    var gov_70 = $('div#gov_70').html();
    
    var hash = $('input#hash').val();
    
    $.ajax({
        type: "POST",
        url: 'https://acreideias.com.br/eleicoes/teste3/inserir.php',
        data: {brancos: brancos, nulos: nulos, comparecimentos: comparecimentos, faltosos: faltosos, zona: zona, secao: secao, local: local, aptos: aptos, hash: hash, gov_13: gov_13, gov_17: gov_17, gov_18: gov_18, gov_11: gov_11, gov_70: gov_70},
        cache: false,
        success: function (obj) {

          obj = JSON.parse(obj);
          
          if (obj.msg == 'success') {
            swal({
              title: "Salvando as Informações",
              text: obj.retorno,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#8CD4F5",
              confirmButtonText: "OK",
              closeOnConfirm: false
            }, function () {
                  setTimeout("location.href='https://acreideias.com.br/eleicoes/teste3/'", 1);
            });
            return false;
          } else if (obj.msg == 'error') {
            swal({
              title: "Salvando as Informações",
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
            title: "Salvando as Informações",
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
//----------------------------------------------------------------------------------------