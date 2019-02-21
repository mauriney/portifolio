<?php
include_once('conf/config.php');
include_once('utils/funcoes.php');

$db = Conexao::getInstance();

@session_start();

vf_usuario_login();
?>

<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <title>:: Agenda ::</title>
        <meta content="Sistema para gestores públicos" name="description">
        <meta name="author" content="Ubirajara de Almeida Jucá">
        <meta content="noindex, follow" name="robots">
        <meta content="width=device-width, minimum-scale=1, maximum-scale=1" name="viewport">
        <!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" media="all" href="css/bootstrap.min.css">
        <!-- CSS Geral -->
        <link type="text/css" rel="stylesheet" media="all" href="css/style.min.css">
        <!-- Fontes -->
        <link type="text/css" rel="stylesheet" media="all" href="fontes/fontes.css">
        <!-- Plugins -->
        <link type="text/css" rel="stylesheet" media="all" href="plugins/perfect-scrollbar/css/perfect-scrollbar.min.css">
        <link type="text/css" rel="stylesheet" media="all" href="plugins/datatables-responsive/css/datatables.responsive.min.css">

        <!-- Select2 -->
        <link type="text/css" rel="stylesheet" media="all" href="plugins/bootstrap-select2/select2.css">
        <link type="text/css" rel="stylesheet" media="all" href="css/estilo.min.css">

        <!-- CSS DO DATAPICKER -->
        <link rel="stylesheet" href="plugins/datepicker-personalizado/css/kendo.common-material.min.css" />
        <link rel="stylesheet" href="plugins/datepicker-personalizado/css/kendo.material.min.css" />

        <!-- CSS DA NOTIFICAÇÃO EM IMPROMPT -->
        <link rel="stylesheet" media="all" type="text/css" href="plugins/jQuery-Impromptu-master/dist/jquery-impromptu.css" />

        <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.css" />

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
    </head>
    <body>
        <!-- topo -->
        <header>
            <!-- acesso -->
            <div class="acesso">
                <div> <span><?php echo $_SESSION['nome']; ?></span> </div>
                <div> <a href="logout.php" class="sair">Sair</a> </div>
            </div>
            <!-- identificação -->
            <div class="identificacao-pagina">
                <div> <a id="voltar_pagina" href="#" title="voltar" onclick="window.history.go(-1);" class="bt-voltar">Voltar</a></div>
                <div> <span id="titulo1"></span> </div>
            </div>	
        </header>