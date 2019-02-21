<html>
<head>
    <meta charset="utf-8" lang="pt-br">
</head>
<body style="background-color: #fcfcfc;">
    <div style="width: 600px; background-color: white; margin: 15px auto;-webkit-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); -moz-box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); box-shadow: 0px 3px 19px -2px rgba(0,0,0,0.53); padding-bottom: 6px;">

        <div style="height: 120px;">
            <img src="img/logo-siba.svg" style="margin: 20px 71px;">
        </div>
        <div style="width: 100%; height: 92px; background-color: red; display: block;">
            <img style="margin: 8px 176px 8px;" src="img/email-agenda.svg">
        </div>

<?php
// NIRO AQUI VC COLOCA A CONDIÇÃO PARA ENVIO COM O STATUS ENTRE CONFIRMADA E CANCELADA
$a = 2;

if ($a > 1) {
    echo " <div style='background-color: #1dc116; color: white; font-family: Helvetica, Arial, sans-serif; font-size: 25px; text-align: center; padding: 8px 0 4px 0;'>CONFIRMADA</div> ";
} else {
    echo " <div style='background-color: #CC0000; color: white; font-family: Helvetica, Arial, sans-serif; font-size: 25px; text-align: center; padding: 8px 0 4px 0;'>CANCELADA</div> ";
}

?>
        <div style="font-family: Helvetica, Arial, sans-serif; font-size: 31px; text-align: center; margin: 25px 0 35px 0;">Qua 23 Set 2015 - 15:00</div>

        <div style="width: 93%; margin: auto;">
            <div style="margin: 35px 0 4px 0; font-family: Helvetica, Arial, sans-serif;color:red;">PAUTA</div>

            <hr size="2" width="100%" align="center" noshade color="red">

            <div style="font-family: Helvetica, Arial, sans-serif; font-size: 24px;">
                <b>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mattis cursus lacus. Nunc pharetra metus ac consequat laoreet. Aenean at nisi sit amet felis malesuada rutrum vitae non sem. Etiam faucibus metus enim, eget venenatis dolor mattis et.
                </b>
            </div>

            <div style="margin: 20px 0 4px 0; font-family: Helvetica, Arial, sans-serif; color:red;">LOCAL</div>

            <hr size="2" width="100%" align="center" noshade color="red">

            <div style="font-family: Helvetica, Arial, sans-serif; font-size: 18px;">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mattis cursus lacus. Nunc pharetra metus ac consequat laoreet. Aenean at nisi sit amet felis malesuada rutrum vitae non sem. Etiam faucibus metus enim, eget venenatis dolor mattis et.
            </div>

            <div style="font-family: Helvetica, Arial, sans-serif; font-size: 18px; margin-top: 6px;">
                Bairro <!-- AQUI VC PÕE O NOME DO BAIRRO --><b>Nome do Bairro</b> - <b>Rio Branco</b> - <b>AC</b>
            </div>
        </div>
        <a href="http://sibamachado13.com" target=”_blank” style="margin: 30px auto 20px auto; display: block; width: 148px; "><img src="img/email-mais-detalhes.svg"></a>
    </div>
</body>
</html>
