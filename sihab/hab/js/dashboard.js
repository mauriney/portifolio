$(window).load(function () {
  // Jogando a busca para a lista de ação
  $("form#form_busca").attr('action', PORTAL_URL + "sistema/candidato/lista");
  //MENSAGEM DE BEM VINDO AO SISTEMA
  function notify(message, type) {
    $.growl({
      message: message
    }, {
      type: type,
      allow_dismiss: false,
      label: 'Cancel',
      className: 'btn-xs btn-inverse',
      placement: {
        from: 'bottom',
        align: 'right'
      },
      delay: 2500,
      animate: {
        enter: 'animated fadeInRight',
        exit: 'animated fadeOutRight'
      },
      offset: {
        x: 30,
        y: 30
      }
    });
  }
  ;

  if ($("#nome_usuario").attr('rel') == 1) {
    notify('Bem vindo ' + $("#nome_usuario").val(), 'inverse');
  }
});


