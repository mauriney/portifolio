<?php include_once 'conf/config.php' ?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>CINPRO :: Clínica Integrada de Prevenção e Reabilitação Oral</title>
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

        <!-- Bootstrap core CSS -->
        <link href="<?= PORTAL_URL; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?= PORTAL_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?= PORTAL_URL; ?>assets/fonts/fonts.css" rel="stylesheet">

    </head>

    <body class="purple">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-3">
                        <a class="toggle menu-btn">
                            <i class="ci-menu"></i>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-4 col-9">
                        <a class="navbar-brand logo-cinpro" href="#"></a>
                    </div>
                    <div class="col-md-4 col-sm-4 desktop">
                        <div class="contact">
                            <h5>Agende um Horário:</h5>
                            <a href="tel:0689999-0000"> <i class="ci-phone"></i> (68) 3224-6991</a>
                            <a href="tel:+556899999-0000"> <i class="ci-whatsapp"></i> (68) 99999-0000</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Pushy Menu -->
        <nav class="pushy pushy-left" data-focus="#first-link">
            <div class="pushy-content">
                <img src="<?= IMG_FOLDER; ?>logo.svg" alt="" class="logo" /><br />
                <ul>
                    <li class="pushy-link"><a href="<?= PORTAL_URL; ?>index.php">Home</a></li>
                    <li class="pushy-link">
                        <a class="pushy-link" data-toggle="collapse" href="#cinpro" role="button" aria-expanded="false">
                            A Cinpro
                        </a>
                        <div class="collapse" id="cinpro">
                            <ul>
                                <li><a href="<?= PORTAL_URL; ?>cinpro/referencia.php">Porque somos referência</a></li>
                                <li><a href="<?= PORTAL_URL; ?>cinpro/espaco.php">Nosso Espaço</a></li>
                                <li><a href="<?= PORTAL_URL; ?>cinpro/odontologia.php">Odontologia Integrada</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="pushy-link"><a href="<?= PORTAL_URL; ?>kids/kids.php">Cinpro Kids</a></li>
                    <li class="pushy-link">
                        <a class="pushy-link" data-toggle="collapse" href="#tratamento" role="button" aria-expanded="false">
                            Tratamento
                        </a>
                        <div class="collapse" id="tratamento">
                            <ul>
                                <li><a href="<?= PORTAL_URL; ?>tratamento/primeira-consulta.php">Primeira Consulta</a></li>
                                <li><a href="<?= PORTAL_URL; ?>tratamento/odontologia-estetica.php">Odontologia Estética</a></li>
                                <li><a href="<?= PORTAL_URL; ?>tratamento/prevencao.php">Prevenção e Profilaxia</a></li>
                                <li><a href="<?= PORTAL_URL; ?>tratamento/experianecia-paciente.php">Experiência do Paciente</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="pushy-link">
                        <a class="pushy-link" data-toggle="collapse" href="#especialidades" role="button" aria-expanded="false">
                            Especialidades
                        </a>
                        <div class="collapse" id="especialidades">
                            <ul>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/ortognatica.php">Cirurgia ortognática</a></li>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/endodontia.php">Endodontia</a></li>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/implantodontia.php">Implantodontia</a></li>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/ortodontia.php">Ortodontia</a></li>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/ortopedia-funcional.php">Ortopedia funcional dos maxilares</a></li>
                                <li><a href="<?= PORTAL_URL; ?>especialidades/periodontia.php">Periodontia</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="pushy-link"><a href="<?= PORTAL_URL; ?>clientes.php">Clientes</a></li>
                    <li class="pushy-link">
                        <a class="pushy-link" data-toggle="collapse" href="#mais" role="button" aria-expanded="false">
                            Mais Sobre Nós
                        </a>
                        <div class="collapse" id="mais">
                            <ul>
                                <li><a href="<?= PORTAL_URL; ?>sobre/imprensa.php">Imprensa</a></li>
                                <li><a href="<?= PORTAL_URL; ?>sobre/videos.php">Vídeos</a></li>
                                <li><a href="<?= PORTAL_URL; ?>sobre/fotos.php">Fotos</a></li>
                                <li><a href="<?= PORTAL_URL; ?>sobre/livros.php">Livros</a></li>
                                <li><a href="<?= PORTAL_URL; ?>sobre/parceiros.php">Parceiros</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="pushy-link"><a href="<?= PORTAL_URL; ?>contato.php">Contato</a></li>
                </ul>
            </div>
            <div class="menu-foot">
                <div class="row">
                    <div class="col-md-12"> <span>Clínica Integrada de <br>Prevenção e Reabilitação Oral</span></div>
                </div>
                <div class="row">
                    <div class="col-md-12"> <span> <i class="ci-phone"></i> (68) 3224-6991</span></div>
                </div>
            </div>
        </nav>
        <!-- Site Overlay -->
        <div class="site-overlay"></div>