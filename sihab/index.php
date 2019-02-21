<?php

ob_start();
session_start();

//ADICIONAR A CONEXAO E URL AMIGAVEL
include_once "conf/Url.php";
include_once "conf/config.php";

//FUNCOES
include_once "conf/session.php";
include_once "utils/funcoes.php";
include_once('utils/permissoes.php');

//INSTANCIA A CONEXAO
$oConexao = Conexao::getInstance();

$urlModulo = '';
$urlMvc = '';
$urlPasta = '';
$urlArquivo = '';
$urlParametro = '';
$urlAdress = Url::getURLFull();

$variavel = array('dao', 'view');
$variavel2 = array('', 'login', 'logout', 'dashboard', 'recuperar_senha', 'servidor', 'cidadao');
$variavel3 = array('login', 'login.php');
$variavel4 = array('index', 'index.php', 'home', 'home.php', 'dashboard', 'dashboard.php');
$variavel5 = array('logout', 'logout.php');

if (in_array(Url::getURL(1), $variavel) || in_array(Url::getURL(0), $variavel2)) {
    $urlModulo = Url::getURL(0);
    $urlMvc = Url::getURL(1);
    $urlPasta = Url::getURL(2);
    $urlArquivo = Url::getURL(3);
    $urlParametro = Url::getURL(4);
} else {
    $urlModulo = Url::getURL(0);
    $urlMvc = 'view';
    $urlPasta = Url::getURL(1);
    $urlArquivo = Url::getURL(2);
    $urlParametro = Url::getURL(3);
}

if ($urlModulo == 'sistema') {
    $urlModulo = 'hab';
    if (!$urlMvc) {
        $urlModulo = 'dashboard';
    }
} else if ($urlModulo == 'servidor') {
    if (!$urlMvc) {
        include_once ('hab/view/servidor/index.php');
        sessionOn();
        exit();
    }
} else if ($urlModulo == 'cidadao') {
    if (!$urlMvc) {
        include_once ('hab/view/site/home.php');
        sessionOn();
        exit();
    }
} else if ($urlModulo == 'index.php' || $urlModulo == 'index' || $urlModulo == '' || $urlModulo == null || $urlModulo == '10.46.1.104') {

    $urlModulo = "login";
    include $urlModulo . ".php";
    sessionOn();
    exit();
} else if ($urlModulo == 'login.php' || $urlModulo == 'login') {

    $urlModulo = "login";
    include $urlModulo . ".php";
    sessionOn();
    exit();
}


// VERIFICAÇÃO DE MODULOS
include_once "utils/permissoes.php";
// VERIFICA SE O USUÁRIO ESTÁ LOGADO PELA SESSION

if (Url::getURL(1) != 'site' && Url::getURL(2) != 'site' && Url::getURL(3) != 'home' && Url::getURL(1) != 'servidor' && Url::getURL(1) != 'dao') {
    vf_usuario_login();
} else if (Url::getURL(1) == 'site' && Url::getURL(3) != 'home' || Url::getURL(2) == 'site' && Url::getURL(3) != 'home') {
    vf_usuario_login_site();
}
// VERIFICA AS PERMISSÕES DO USUÁRIO DE ACESSO AO MÓDULO
vf_modulo();
if ($urlModulo == '') {
    include_once "dashboard.php";
    sessionOn();
    exit();
} else if ($urlModulo == 'hab') {
    if (!isset($_SESSION['id']) && Url::getURL(1) != 'site' && Url::getURL(2) != 'site' && Url::getURL(3) != 'home' && Url::getURL(1) != 'servidor' && Url::getURL(1) != 'dao') {
        include_once ('login.php');
        sessionOn();
        exit();
    }
}
if (file_exists($urlAdress . '.php')) {
    include_once ($urlAdress . '.php');
    sessionOn();
    exit();
} else if ($urlArquivo && $urlArquivo != '') {
    if (file_exists($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '/' . $urlArquivo . '.php')) {
        include_once ($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '/' . $urlArquivo . '.php');
        sessionOn();
        exit();
    } else if (file_exists($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '.php')) {
        include_once ($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '.php');
        sessionOn();
        exit();
    } else {
        include_once ('404.php');
        sessionOn();
        exit();
    }
} else if ($urlPasta && $urlPasta != '') {
    if (file_exists($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '.php')) {
        include_once ($urlModulo . '/' . $urlMvc . '/' . $urlPasta . '.php');
        sessionOn();
        exit();
    } else if (file_exists($urlModulo . '/' . $urlMvc . '.php')) {
        include_once ($urlModulo . '/' . $urlMvc . '.php');
        sessionOn();
        exit();
    } else {
        include_once ('404.php');
        sessionOn();
        exit();
    }
} else if ($urlMvc && $urlMvc != '') {
    if (file_exists($urlModulo . '/' . $urlMvc . '.php')) {
        include_once ($urlModulo . '/' . $urlMvc . '.php');
        sessionOn();
        exit();
    } else if (file_exists($urlModulo . '.php')) {
        include_once ($urlModulo . '.php');
        sessionOn();
        exit();
    } else if (in_array($urlMvc, $variavel3)) {
        include_once ('login.php');
        sessionOn();
        exit();
    } else if (in_array($urlMvc, $variavel4)) {
        include_once ($urlModulo . '/' . 'dashboard.php');
        sessionOn();
        exit();
    } else {
        include_once ('404.php');
        sessionOn();
        exit();
    }
} else if ($urlModulo && $urlModulo != '') {
    if (file_exists($urlModulo . '.php')) {
        include_once ('dashboard.php');
        sessionOn();
        exit();
    } else if (in_array($urlModulo, $variavel3)) {
        include_once ('login.php');
        sessionOn();
        exit();
    } else if (in_array($urlModulo, $variavel5)) {
        include_once ('logout.php');
        sessionOn();
        exit();
    } else if (in_array($urlModulo, $variavel4)) {
        include_once ('dashboard.php');
        sessionOn();
        exit();
    }
} else {
    include_once ('404.php');
    sessionOn();
    exit();
}
?>  