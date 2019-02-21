<html>
    <head>
        <title>Instascan</title>
    </head>
    <body>
        <?php
        if (isset($_POST['yourInputFieldId'])) {
          echo str_replace(" ", "<br/>",$_POST['yourInputFieldId']);
        } else {
          echo "Nenhum resultado encontraro...";
        }
        ?>
    </body>
</html>
