<!-- Modal -->
<?php
$grupos = "";
$cont = 1;
$sql = $db->prepare("SELECT * FROM tb_bsc_contato ORDER BY nome ASC");
$sql->execute();
while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="modal fade" id="modal-contato-<?php echo $linha['idcontato']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog md-grupo" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="fechar"></span></button>
                    <span>Segmentos do contato</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="grupos">
                                <ul>
                                    <?php
                                    $grupos = "";

                                    $sql_grupo = $db->prepare("SELECT se.Descricao FROM tb_bsc_segmento_grupo sg 
        LEFT JOIN tb_bsc_segmento se ON se.IdSegmento = sg.idsegmento
        WHERE idcontato = ?");

                                    $sql_grupo->bindValue(1, $linha['idcontato']);
                                    $sql_grupo->execute();

                                    while ($f_grupo = $sql_grupo->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <li class="col-md-4"> <label> <?php echo utf8_encode($f_grupo['Descricao']); ?> </label></li>
                                            <?php
                                        }
                                        ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
