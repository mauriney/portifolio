<?php

@session_start();
include_once ('conf/sistema.php');
echo "<script 'text/javascript'>window.location = '" . PORTAL_URL . "view/cardapios/index.php';</script>";
?>