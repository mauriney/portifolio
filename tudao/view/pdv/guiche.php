<?php
@session_start();
include($_SESSION['NOME_SISTEMA'] . 'template/topo.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PDV :: <?= TITULOSISTEMA ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Sweetalert --> 
        <link href="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.css" rel="stylesheet">  
        <!-- Bootstrap 3.3.4 -->
        <link href="<?= ASSETS_FOLDER; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
        <!-- FontAwesome 4.3.0 -->
        <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
        <!-- Ionicons 2.0.0 -->
        <!-- <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" /> -->    
        <!-- Theme style -->
        <link href="<?= CSS_FOLDER; ?>AdminLTE.min.css" rel="stylesheet" type="text/css" />
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

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="skin-yellow sidebar-mini">
        <?php
        $pedido_principal = 0;
        $sql = $db->prepare("SELECT p.id, p.mesa, pe.nome AS cliente
                             FROM pedidos AS p
                             LEFT JOIN clientes AS c ON c.id = p.cliente_id
                             LEFT JOIN pessoas AS pe ON pe.id = c.pessoa_id
                             WHERE p.guinche = 2 AND p.mesa IS NOT NULL AND p.mesa <> 0
                             ORDER BY p.atualizacao DESC
                             LIMIT 0,1");
        $sql->execute();
        while ($pedido = $sql->fetch(PDO::FETCH_ASSOC)) {
            $pedido_principal = $pedido['id'];
            ?>
            <div class="row">
                <div class="col-md-3">
                    Cliente: <?php echo $pedido['cliente']; ?>
                </div>
                <div class="col-md-3">
                    Pedido: 
                </div>
                <div class="col-md-3">
                    Mesa: <?php echo $pedido['mesa']; ?>
                </div>
            </div>
            <?php
        }
        ?>

        <h3>---- OUTROS PEDIDOS CHAMADOS NO GUICHÊ ----</h3>
        <?php
        $sql2 = $db->prepare("SELECT p.mesa, pe.nome AS cliente
                             FROM pedidos AS p
                             LEFT JOIN clientes AS c ON c.id = p.cliente_id
                             LEFT JOIN pessoas AS pe ON pe.id = c.pessoa_id
                             WHERE p.guinche = 1 AND p.id <> ?
                             GROUP BY p.id
                             ORDER BY p.atualizacao DESC");
        $sql2->bindValue(1, $pedido_principal);
        $sql2->execute();
        while ($pedido2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="row">
                <div class="col-md-3">
                    Cliente: <?php echo $pedido2['cliente']; ?>
                </div>
                <div class="col-md-3">
                    Pedido: 
                </div>
                <div class="col-md-3">
                    Mesa: <?php echo $pedido2['mesa']; ?>
                </div>
            </div>
            <?php
        }
        ?>

    </body>
    <!-- jQuery 2.1.4 -->
    <script src="<?= PLUGINS_FOLDER; ?>jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.2 -->
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?= ASSETS_FOLDER; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
    <!-- Morris.js charts -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?= PLUGINS_FOLDER; ?>morris/morris.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="<?= PLUGINS_FOLDER; ?>sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="<?= PLUGINS_FOLDER; ?>jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="<?= PLUGINS_FOLDER; ?>jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?= PLUGINS_FOLDER; ?>knob/jquery.knob.js" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
    <script src="<?= PLUGINS_FOLDER; ?>daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="<?= PLUGINS_FOLDER; ?>datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?= PLUGINS_FOLDER; ?>bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="<?= PLUGINS_FOLDER; ?>slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?= PLUGINS_FOLDER; ?>fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="<?= JS_FOLDER; ?>app.min.js" type="text/javascript"></script>    
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="<?= JS_FOLDER; ?>pages/dashboard.js" type="text/javascript"></script>-->   
    <!-- AdminLTE for demo purposes -->
    <script src="<?= JS_FOLDER; ?>demo.js" type="text/javascript"></script>
    <!-- JAVASCRIPT DO SELECT2 DO LOCAWEB -->
    <script src="<?= PLUGINS_FOLDER ?>bootstrap-select2/select2.js"></script>
    <!-- InputMask -->
    <script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="<?= PLUGINS_FOLDER; ?>input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <!-- Sweetalert -->
    <script src="<?= PLUGINS_FOLDER; ?>sweetalert/dist/sweetalert.min.js"></script>
    <!-- Livequery -->
    <script src="<?= JS_FOLDER ?>livequery.js" type="text/javascript"></script>
    <!-- JS UTIL -->
    <script src="<?= JS_FOLDER ?>utils.js" type="text/javascript"></script>
    <!-- Projeto.Utils -->
    <script src="<?= JS_FOLDER ?>projeto.utils.js" type="text/javascript"></script>

    <!-- MÁSCARA PARA DINHEIRO -->
    <script type="text/javascript" src="<?= ASSETS_FOLDER; ?>js/jquery.price_format.1.3.js"></script>

    <script>

        $('select').each(function() {
            $(this).select2();
        });

        $('.datepicker').each(function() {
            $(this).datepicker({
                format: 'mm/dd/yyyy',
                startDate: '-3d'
            });
        });

        window.onload = setInterval("showPedidos()", 30000);

        function showPedidos() {
            setTimeout("location.href='" + PORTAL_URL + "view/pdv/guiche.php'", 1);
        }

    </script>

</html>