<?php
include_once('conf/config.php');
include_once('utils/funcoes.php');

@session_start();

// VERIFICAÇÕES DE SESSÕES
if (isset($_SESSION['id']) && is_numeric(pesquisar("idinfo", "info_login", "idusuario", "=", @$_SESSION['id'], ""))) {
    echo "<script>window.location = '" . PORTAL_URL . "agenda-dia.php';</script>";
    exit();
}
// RANDONIZADOR DE IMAGENS DO BACKGROUND
$dir_name = "img/fotos/";
$handle = opendir($dir_name);
$i = 0;
while ($file = readdir($handle)){
    if ($file != "." && $file != ".." && $file != ".DS_Store"){
        $photos[$i] = "$dir_name/$file";
        $i++;
    }
}
closedir($handle);
$img = $photos[array_rand($photos)];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>:: Agenda ::</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" sizes="57x57" href="img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">


        <!-- BEGIN CSS TEMPLATE -->
        <link href="css/login.min.css" rel="stylesheet" type="text/css"/>
        <!-- END CSS TEMPLATE -->

        <!-- FONTES -->
        <link href="fontes/fontes.css" rel="stylesheet" />

        <!-- CSS DA NOTIFICAÇÃO EM IMPROMPT -->
        <link rel="stylesheet" media="all" type="text/css" href="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.css" />

        <!-- JAVASCRIPT DA NOTIFICAÇÃO EM IMPROMPT --> 
        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.js"></script>

        <script type="text/javascript" src="js/jquery.validate.min.js"></script>

        <script type="text/javascript" src="js/livequery.js"></script>

        <script type="text/javascript" src="utils/utils.js"></script>

        <script type="text/javascript">

            setInterval("buscarTempo()", 1000);

            function buscarTempo()
            {
                var data = new Date();

                var h = data.getHours();
                var m = data.getMinutes();

                //COLOCANDO UM 0 NA FRENTE, CASO A HORA SEJA MENOR QUE 10
                if (h < 10) {
                    h = "0" + data.getHours();
                }

                if (m < 10) {
                    m = "0" + data.getMinutes();
                }

                var semana = "";
                var dia_mes = "";
                var hora = h + ":" + m;

                var dia = data.getDate();           // 1-31
                var dia_sem = data.getDay();            // 0-6 (zero=domingo)
                var mes = data.getMonth();          // 0-11 (zero=janeiro)

                if (mes == 0) {
                    dia_mes = dia + " de Janeiro";
                } else if (mes == 1) {
                    dia_mes = dia + " de Fevereiro";
                } else if (mes == 2) {
                    dia_mes = dia + " de Março";
                } else if (mes == 3) {
                    dia_mes = dia + " de Abril";
                } else if (mes == 4) {
                    dia_mes = dia + " de Maio";
                } else if (mes == 5) {
                    dia_mes = dia + " de Junho";
                } else if (mes == 6) {
                    dia_mes = dia + " de Julho";
                } else if (mes == 7) {
                    dia_mes = dia + " de Agosto";
                } else if (mes == 8) {
                    dia_mes = dia + " de Setembro";
                } else if (mes == 9) {
                    dia_mes = dia + " de Outubro";
                } else if (mes == 10) {
                    dia_mes = dia + " de Novembro";
                } else if (mes == 11) {
                    dia_mes = dia + " de Dezembro";
                }


                if (dia_sem == 0) {
                    semana = "Domingo";
                } else if (dia_sem == 1) {
                    semana = "Segunda-feira";
                } else if (dia_sem == 2) {
                    semana = "Terça-feira";
                } else if (dia_sem == 3) {
                    semana = "Quarta-feira";
                } else if (dia_sem == 4) {
                    semana = "Quinta-feira";
                } else if (dia_sem == 5) {
                    semana = "Sexta-feira";
                } else if (dia_sem == 6) {
                    semana = "Sábado";
                }

                $("#hora").html(hora);
                $("#dia_semana").html(semana);
                $("#dia_mes").html(dia_mes);
            }

            $(document).ready(function() {

                $('#form_login').submit(function() {

                    var login_valido = login_validator();

                    if (login_valido) {

                        $.ajax({
                            type: "POST",
                            url: 'autenticar.php',
                            data: $('#form_login').serialize(),
                            cache: false,
                            success: function(obj) {
                                obj = JSON.parse(obj);
                                if (obj.msg == 'success') {
                                    setTimeout("location.href='agenda-dia.php'", 1);
                                } else if (obj.msg == 'error') {
                                    $('div#div_senha').after('<label id="erro_senha" class="error">' + obj.retorno + '</label>');
                                }
                            },
                            error: function(obj) {
                                $.prompt(obj.retorno);
                            }
                        });
                        return false;
                    } else {
                        return false;
                    }
                });
            });

            //VALIDATOR DO LOGIN
            function login_validator() {
                var valido = true;
                var login = $("#login").val();
                var senha = $("#senha").val();

                //LIMPA MENSAGENS DE ERRO
                $('label#erro_login').remove();
                $('label#erro_senha').remove();

                //VERIFICANDO SE OS CAMPOS LOGIN E SENHA FORAM INFORMADOS
                if (login == "") {
                    $('div#div_login').after('<label id="erro_login" class="error">O campo usuário é obrigatório.</label>');
                    valido = false;
                }
                if (senha == "") {
                    $('div#div_senha').after('<label id="erro_senha" class="error">O campo senha é obrigatório.</label>');
                    valido = false;
                }
                return valido;
            }
        </script>

    </head>
    <body>
        <?php
        $hora = date('H:i');
        $dia = dataExtensoSemAno(date('d/m/Y'));
        $dia_semana = getSemana(date('N'), 1);
        ?>
        <div class="corpo" style="background: url(<?php echo $img ?>) center center no-repeat;background-size:cover;">

            <!-- GRADIENTE -->
            <div class="gradiente"></div>
            <!-- FIM GRADIENTE -->

            <!-- LOGO DO SISTEMA -->
            <!-- <div class="logo-siplage"> Logo do Sistema </div> -->
            <!-- FIM LOGO DO SISTEMA -->

            <div class="previsao">
                <ul class="ic_tempo">
                        <!-- <li class="tempo"><img src="img/ic_termometro.svg" height="42" /></li>
                        <li class="graus"></li> -->
                    <li id="hora" class="hora"><?php echo $hora ?></li>
                    <li id="dia_semana" class="dia"><?php echo $dia_semana ?></li>
                    <li id="dia_mes" class="dia_mes"><?php echo $dia ?></li>
                </ul>
            </div>

            <!-- LOGIN -->
            <div class="login">

                <div class="row">

                    <div class="col-md-12">

                        <form id="form_login" name="form_login" method="post" action="#">

                            <div class="campos">

                                <div id="div_login" class="input-group usuario">
                                    <span class="input-group-addon primary">				  
                                        <img src="img/usuario-login.svg" border="0" />
                                    </span>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="Usuário" value="" />
                                </div>

                                <div id="div_senha" class="input-group senha">
                                    <span class="input-group-addon primary">				  
                                        <img src="img/senha-login.svg" border="0" />
                                    </span>
                                    <input type="password"  class="form-control" id="senha" name="senha" placeholder="Senha" value="" />
                                </div>

                            </div>

                            <div class="botao">

                                <div class="input-group">
                                    <button id="confirmar" class="btn bt-login" type="submit"></button>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
            <!-- FIM LOGIN -->

            <!-- ACESSO -->
            <div class="acesso"> 
                <a href="esqueceu-senha.php"> Não consegue acessar?</a>
            </div>
            <!-- FIM LOGIN -->

            <!-- RODAPÉ -->
            <footer class="rodape-login">
                <div>
                    <div class="nome-estado">Agenda <br /> Desenvolvido por Acre Idéias</div>
                    <div class="logo-estado"><span></span></div>
                    <div class="nome-sistema">2018 Todos os direitos reservados</div>
                </div>
            </footer>
            <!-- FIM RODAPÉ -->

        </div>
        <!-- FIM CORPO -->
    </body>
</html>