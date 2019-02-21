<?php
header ("Cache-Control: max-age=300, must-revalidate" ); // CORRIGINDO O HISTORY.BACK
                                                       // DEFINIR TIMEZONE PADRÃO
date_default_timezone_set ( "Brazil/Acre" );

// CONFIGURACOES PADRAO DO SISTEMA
define ( "PORTAL_URL", 'http://acreideias.com.br/cinpro/' );
define ( "TITULOSISTEMA", 'CINPRO' );
define ( "FAVICONSISTEMA", 'http://acreideias.com.br/cinpro/upload/favicon.png' );
define ( "LOGO_DASHBOARD", 'http://acreideias.com.br/cinpro/tema/img/logo-gestor-de-cargos.svg' );
define ( "CSS_FOLDER", 'http://acreideias.com.br/cinpro/assets/css/' );
define ( "IMG_FOLDER", 'http://acreideias.com.br/cinpro/assets/img/' );
define ( "JS_FOLDER", 'http://acreideias.com.br/cinpro/assets/js/' );
define ( "FONTS_FOLDER", 'http://acreideias.com.br/cinpro/assets/fontes/' );
define ( "PLUGINS_FOLDER", 'http://acreideias.com.br/cinpro/assets/plugins/' );
define ( "UTILS_FOLDER", 'http://acreideias.com.br/cinpro/utils/' );
define ( "ASSETS_FOLDER", 'http://acreideias.com.br/cinpro/assets/' );
define ( "PORTAL_URL_SERVIDOR", 'C:/xampp/htdocs/cinpro/' );
?>