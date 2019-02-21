/*---------------------------------------------------------------------------------------------------------
 DATA: 26/07/2016 ÀS 09:42
 NOME: JS DA CLASSE DE LOGIN
 ---------------------------------------------------------------------------------------------------------*/

$(document).ready(function() {
		// ---------------------------------------------------------------------------------------------------------
		// LOGIN NO SISTEMA
		$('form#home_login').submit(function() {
				if (login_validator()) {
						$.ajax({
						  type : "POST",
						  url : PORTAL_URL + 'hab/dao/site/autenticar.php',
						  data : $('form#home_login').serialize(),
						  cache : false,
						  success : function(obj) {
								  obj = JSON.parse(obj);
								  if (obj.msg == 'success') {
										  setTimeout('location.href="result"', 1);
								  } else if (obj.msg == 'error') {
										  swal({
										    title : "Autenticação",
										    text : obj.retorno,
										    type : "error",
										    showCancelButton : false,
										    confirmButtonColor : "#8CD4F5",
										    confirmButtonText : "OK",
										    closeOnConfirm : false
										  });
										  return false;
								  }
						  },
						  error : function(obj) {
								  swal({
								    title : "Autenticação",
								    text : obj.retorno,
								    type : "error",
								    showCancelButton : false,
								    confirmButtonColor : "#8CD4F5",
								    confirmButtonText : "OK",
								    closeOnConfirm : false
								  });
								  return false;
						  }
						});
						return false;
				} else {
						return false;
				}
		});
});
// ---------------------------------------------------------------------------------------------------------
// VALIDAÇÃO DO LOGIN
function login_validator() {
		var valido = true;
		var login = $("#cpf").val();
		var senha = $("#senha").val();

		// LIMPA MENSAGENS DE ERRO
		$('label.error').each(function() {
				$(this).remove();
		});

		// VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
		if (login == "") {
				$('div#div_login').after('<label id="erro_login" class="error">O campo CPF é obrigatório.</label>');
				valido = false;
		}
		if (senha == "") {
				$('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
				valido = false;
		}
		return valido;
}
// ---------------------------------------------------------------------------------------------------------
