<?php
@session_start();
include_once ($_SESSION['NOME_SISTEMA'] . 'conf/sistema.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'assets/plugins/phpmailer/class.smtp.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'assets/plugins/phpmailer/class.phpmailer.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'functions/geral.php');
include_once ($_SESSION['NOME_SISTEMA'] . 'functions/Url.php');

$db = Conexao::getInstance();


if (ver_nivel(1, 1) == false && ver_nivel(1, 2) == false && ver_nivel(1, 3) == false && ver_nivel(1, 4) == false) {
    echo "<script language='javascript'>window.alert('Você não tem permissão de acesso ao sistema'); window.location.href='" . PORTAL_URL . "logout.php'</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= TITULOSISTEMA ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Sweetalert --> 
        <link href="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.css" rel="stylesheet">  
        <!-- Bootstrap 3.3.4 -->
        <link href="<?= ASSETS_FOLDER; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
        <!-- FontAwesome 4.3.0 -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons 2.0.0 -->
        <!-- <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" /> -->    
        <!-- Theme style -->
        <link href="<?= CSS_FOLDER; ?>AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= CSS_FOLDER; ?>style.css" rel="stylesheet" type="text/css" />
        <link href="<?= CSS_FOLDER; ?>pdv.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?= CSS_FOLDER; ?>skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!-- iCheck -->
        <link href="<?= PLUGINS_FOLDER; ?>iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="<?= PLUGINS_FOLDER; ?>morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="<?= PLUGINS_FOLDER; ?>jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- Date Picker -->
        <link href="<?= PLUGINS_FOLDER; ?>datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="<?= PLUGINS_FOLDER; ?>daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?= PLUGINS_FOLDER; ?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Bootstrap Select 2 -->
        <link media="screen" type="text/css" rel="stylesheet" href="<?= PLUGINS_FOLDER ?>bootstrap-select2/select2.css" />
        <!-- Fonte -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet">
        <!-- DATA TABLES -->
        <link href="<?= PLUGINS_FOLDER; ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

        <link href="<?= CSS_FOLDER; ?>mTab-style.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="skin-yellow sidebar-mini">