<?php include 'template/top1.php' ?>
<?php include 'template/menu_topo1.php' ?>

<link rel="stylesheet" href="docs/style.css">

<!-- Início do Corpo do Site -->
<div class="content">
    <h1 class="county">Rio Branco</h1>
    <div class="container-fluid">
    	<div class="row">
    		<div class="col-md-6">
    			<h1 class="title"><i class="fas fa-chart-line"></i> COMPARAÇÃO</h1>
	    		<div class="card no-margin">
					<div class="compare">
						<div class="marcus">
							<img src="assets/img/marcus-800.jpg" alt="">
							<div class="name">Marcus Alexandre <strong>13</strong></div>
						</div>
						<div class="versus">
							<i class="fas fa-times"></i>
						</div>
						<div class="others">
							<div class="form-check title">
								<label class="form-check-label">
									Coronel Ulysses <strong>17</strong>
									<input checked="checked" id="check_17" name="check_17" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									Janaína Furtado <strong>18</strong>
									<input checked="checked" id="check_18" name="check_18" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									Gladson Cameli <strong>11</strong>
									<input checked="checked" id="check_11" name="check_11" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									David Hall <strong>70</strong>
									<input checked="checked" id="check_70" name="check_70" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									Brancos <strong>5</strong>
									<input checked="checked" id="check_brancos" name="check_brancos" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									Nulos <strong>8</strong>
									<input checked="checked" id="check_nulos" name="check_nulos" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
							<div class="form-check title">
								<label class="form-check-label">
									Agrupar 
									<input id="check_agrupar" name="check_agrupar" class="form-check-input" type="checkbox" value="">
									<span class="form-check-sign">
										<span class="check"></span>
									</span>
								</label>
							</div>
						</div>
					</div>
				</div>
	    	</div>
	    	<div class="col-md-6">
				<h1 class="title"><img src="assets/img/acre.svg" width="30px" alt="">CANDIDATOS AO GOVERNO</h1>
				<div class="card no-margin">
				    
				    <?php
				    $votos_13= buscar_votos_candidato(13);
				    $votos_17 = buscar_votos_candidato(17);
				    $votos_18 = buscar_votos_candidato(18);
				    $votos_11 = buscar_votos_candidato(11);
				    $votos_70 = buscar_votos_candidato(70);
				    $nulos = pesquisar_nulos();
				    $brancos = pesquisar_brancos();
				    $soma_votos = $votos_13 + $votos_17 + $votos_18 + $votos_11 + $votos_70 + $nulos + $brancos;
				    ?>
				    
				    <input type="hidden" id="total" name="total" value="<?= $soma_votos; ?>" />
				    
					<div id="gov_13" class="candidates">
						<div class="image"><img src="assets/img/marcus.jpg" alt=""></div>
						<div class="name">
							<span>Marcus Alexandre</span> <strong class="treze">13</strong>
							<div class="grafic">
								<b id="votos_13" class=""><?= $votos_13; ?> votos</b>
								<div class="bar bar-thirteen" style="width: <?= round(((100 / $soma_votos) * $votos_13)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $votos_13)); ?>%</div>
					</div>
					<div  id="div_candidatos">
					<div id="gov_17" class="candidates">
						<div class="image"><img src="assets/img/ullysses.jpg" alt=""></div>
						<div class="name">
							<span>Coronel Ullysses</span> <strong>17</strong>
							<div class="grafic">
								<b class=""><?= $votos_17; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $votos_17)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $votos_17)); ?>%</div>
					</div>
					<div id="gov_18" class="candidates">
						<div class="image"><img src="assets/img/janaina.jpg" alt=""></div>
						<div class="name">
							<span>Janaína Furtado</span> <strong>18</strong>
							<div class="grafic">
								<b class=""><?= $votos_18; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $votos_18)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $votos_18)); ?>%</div>
					</div>
					<div id="gov_11" class="candidates">
						<div class="image"><img src="assets/img/gladson.jpg" alt=""></div>
						<div class="name">
							<span>Gladson Cameli</span> <strong>11</strong>
							<div class="grafic">
								<b class=""><?= $votos_11; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $votos_11)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $votos_11)); ?>%</div>
					</div>
					<div id="gov_70" class="candidates">
						<div class="image"><img src="assets/img/david.jpg" alt=""></div>
						<div class="name">
							<span>David Hall</span> <strong>70</strong>
							<div class="grafic">
								<b class=""><?= $votos_70; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $votos_70)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $votos_70)); ?>%</div>
					</div>
					<div id="gov_brancos" class="candidates">
						<div class="image"><img src="assets/img/user.jpg" alt=""></div>
						<div class="name">
							<span>Brancos</span>
							<div class="grafic">
								<b class=""><?= $brancos; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $brancos)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $brancos)); ?>%</div>
					</div>
					<div id="gov_nulos" class="candidates">
						<div class="image"><img src="assets/img/user.jpg" alt=""></div>
						<div class="name">
							<span>Nulos</span>
							<div class="grafic">
								<b class=""><?= $nulos; ?> votos</b>
								<div class="bar bar-others" style="width: <?= round(((100 / $soma_votos) * $nulos)); ?>%"></div>
							</div>
						</div>
						<div class="wishes"><?= round(((100 / $soma_votos) * $nulos)); ?>%</div>
					</div>
					</div>
					<div id="gov_agrupados" style="display: none" class="candidates">
						<div class="image"><img src="assets/img/user.jpg" alt=""></div>
						<div class="name">
							<span id="agrupado_numeros">17, 70, 11, 18, brancos, nulos</span>
							<div class="grafic">
								<b class="">1.500 votos</b>
								<div class="bar bar-others" style="width: 40%"></div>
							</div>
						</div>
						<div class="wishes">40%</div>
					</div>
				</div>
			</div>
    	</div>
    </div>
</div>
<!-- Fim do Corpo do Site -->

<?php include 'template/footer1.php' ?>

<script type="text/javascript" src="docs/comparacao.js"></script>
