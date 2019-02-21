<?php
header ("Cache-Control: max-age=300, must-revalidate" ); // CORRIGINDO O HISTORY.BACK
                                                       // DEFINIR TIMEZONE PADRÃO
date_default_timezone_set ( "Brazil/Acre" );

define("DB", 'mysql');
define("DB_HOST", "mysql762.umbler.com");
define("DB_NAME", "acreideias");
define("DB_USER", "acreideias");
define("DB_PASS", "123acreideias");

// CONFIGURACOES PADRAO DO SISTEMA
define ( "PORTAL_URL", 'https://acreideias.com.br/eleicoes/teste3/' );
define ( "TITULOSISTEMA", 'QRCODE' );
define ( "CSS_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/css/' );
define ( "IMG_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/img/' );
define ( "JS_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/js/' );
define ( "FONTS_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/fontes/' );
define ( "PLUGINS_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/plugins/' );
define ( "ASSETS_FOLDER", 'https://acreideias.com.br/eleicoes/teste3/assets/' );
define ( "PORTAL_URL_SERVIDOR", 'C:/xampp/htdocs/eleicoes/teste3/' );

// ADICIONAR CLASSE DE CONEÇÃO
include_once("Conexao.class.php");
?>