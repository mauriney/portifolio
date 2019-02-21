<?php
header ("Cache-Control: max-age=300, must-revalidate" ); // CORRIGINDO O HISTORY.BACK
                                                       // DEFINIR TIMEZONE PADRÃO
date_default_timezone_set ( "Brazil/Acre" );

// CONFIGURACOES PADRAO DO SISTEMA
define ( "PORTAL_URL", 'https://acreideias.com.br/eleicoes/teste2/' );
define ( "TITULOSISTEMA", 'QRCODE' );
define ( "CSS_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/css/' );
define ( "IMG_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/img/' );
define ( "JS_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/js/' );
define ( "FONTS_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/fontes/' );
define ( "PLUGINS_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/plugins/' );
define ( "ASSETS_FOLDER", 'https://acreideias.com.br/eleicoes/teste2/assets/' );
define ( "PORTAL_URL_SERVIDOR", 'C:/xampp/htdocs/eleicoes/teste2/' );
?>