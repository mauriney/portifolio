<?php
//DEFINIR TIMEZONE PADRÃO
date_default_timezone_set("Brazil/East");

// DEFININDO OS DADOS DE ACESSO AO BANCO DE DADOS
define("DB", 'mysql');
define("DB_HOST", "localhost");
define("DB_NAME", "agenda");
define("DB_USER", "root");
define("DB_PASS", "");


// CONFIGURACOES PADRAO DO SISTEMA
define("PORTAL_URL", 'http://localhost/agenda/');
define("TITULOSISTEMA", 'AGENDA');

// ADICIONAR CLASSE DE CONEÇÃO
include_once("Conexao.class.php");
?>
