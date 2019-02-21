<?php
include_once ($_SERVER["DOCUMENT_ROOT"].'/eleicoes/teste3/conf/config.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'/eleicoes/teste3/conf/funcoes.php');

$db = Conexao::getInstance();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Apuração - QR Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="theme-color" content="#FFFFFF">
    <meta name="msapplication-navbutton-color" content="#FFFFFF">
    <meta name="apple-mobile-web-app-status-bar-style" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?= IMG_FOLDER; ?>favicons/mstile-144x144.png">

    <link rel="apple-touch-icon" sizes="57x57" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= IMG_FOLDER; ?>favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="<?= IMG_FOLDER; ?>favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?= IMG_FOLDER; ?>favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="<?= IMG_FOLDER; ?>favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?= IMG_FOLDER; ?>favicons/favicon-16x16.png" sizes="16x16">
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material table -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css"> -->
    <!-- CSS Files -->
    <link href="<?= PORTAL_URL; ?>assets/css/material-kit.css?v=2.0.4" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="<?= PORTAL_URL; ?>assets/demo/demo.css" rel="stylesheet" />
    
    <link href="<?= PORTAL_URL; ?>assets/plugins/datatable/css/bootstrap.css" rel="stylesheet" />
    <link href="<?= PORTAL_URL; ?>assets/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="<?= PORTAL_URL; ?>assets/plugins/datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" />

    
</head>
<body class="index-page sidebar-collapse">



