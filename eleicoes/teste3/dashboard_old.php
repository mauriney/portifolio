<?php include 'template/top1.php' ?>
<?php include 'template/menu_topo1.php' ?>

<link rel="stylesheet" href="docs/style.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>

<!-- Início do Corpo do Site -->
<div class="content">
    <h1 class="county">Rio Branco</h1>
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<h1 class="title"><img src="assets/img/acre.svg" width="30px" alt="">CANDIDATOS AO GOVERNO</h1>
				<div class="card no-margin">
					<div class="candidates">
						<div class="image"><img src="assets/img/marcus.jpg" alt=""></div>
						<div class="name">
							<span>Marcus Alexandre</span> <strong class="treze">13</strong>
							<div class="grafic">
								<b class="">230.000 votos</b>
								<div class="bar bar-thirteen" style="width: 62%"></div>
							</div>
						</div>
						<div class="wishes">62%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/ullysses.jpg" alt=""></div>
						<div class="name">
							<span>Coronel Ullysses</span> <strong>17</strong>
							<div class="grafic">
								<b class="">25.000 votos</b>
								<div class="bar bar-others" style="width: 6%"></div>
							</div>
						</div>
						<div class="wishes">6%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/janaina.jpg" alt=""></div>
						<div class="name">
							<span>Janaína Furtado</span> <strong>18</strong>
							<div class="grafic">
								<b class="">15.000 votos</b>
								<div class="bar bar-others" style="width: 2%"></div>
							</div>
						</div>
						<div class="wishes">2%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/gladson.jpg" alt=""></div>
						<div class="name">
							<span>Gladson Cameli</span> <strong>11</strong>
							<div class="grafic">
								<b class="">80.531 votos</b>
								<div class="bar bar-others" style="width: 28%"></div>
							</div>
						</div>
						<div class="wishes">28%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/david.jpg" alt=""></div>
						<div class="name">
							<span>David Hall</span> <strong>70</strong>
							<div class="grafic">
								<b class="">5.000 votos</b>
								<div class="bar bar-others" style="width: 2%"></div>
							</div>
						</div>
						<div class="wishes">2%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/brancos.jpg" alt=""></div>
						<div class="name">
							<span>Brancos</span>
							<div class="grafic">
								<b class="">1.000 votos</b>
								<div class="bar bar-others" style="width: 1%"></div>
							</div>
						</div>
						<div class="wishes">1%</div>
					</div>
					<div class="candidates">
						<div class="image"><img src="assets/img/nulos.jpg" alt=""></div>
						<div class="name">
							<span>Nulos</span>
							<div class="grafic">
								<b class="">1.500 votos</b>
								<div class="bar bar-others" style="width: 1%"></div>
							</div>
						</div>
						<div class="wishes">1%</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-check title">
					<label class="form-check-label">
						<input class="form-check-input" type="checkbox" value="">
						REGIONAIS
						<span class="form-check-sign">
							<span class="check"></span>
						</span>
					</label>
				</div>
				
				<div class="card no-margin">
					<ul class="urn">
						<?php
$result = $db->prepare("SELECT s.id, s.regional   
                        FROM secao s
                        GROUP BY s.regional
                        ORDER BY s.regional ASC");
                        $result->execute();
                        while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
						?>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name"><?= $rs['regional']; ?></span>
								<div class="grafic">
									<strong>30%</strong>
									<div class="bar" style="width: 30%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
							<?php
						}
						?>
						<!--<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 02</span>
								<div class="grafic">
									<strong>40%</strong>
									<div class="bar bar-thirteen" style="width: 40%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 03</span>
								<div class="grafic">
									<strong>60%</strong>
									<div class="bar bar-thirteen" style="width: 60%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 04</span>
								<div class="grafic">
									<strong>15%</strong>
									<div class="bar bar-thirteen" style="width: 15%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 05</span>
								<div class="grafic">
									<strong>5%</strong>
									<div class="bar bar-thirteen" style="width: 5%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 06</span>
								<div class="grafic">
									<strong>80%</strong>
									<div class="bar" style="width: 80%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 07</span>
								<div class="grafic">
									<strong>100%</strong>
									<div class="bar bar-thirteen" style="width: 100%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 08</span>
								<div class="grafic">
									<strong>23%</strong>
									<div class="bar" style="width: 23%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 09</span>
								<div class="grafic">
									<strong>45%</strong>
									<div class="bar bar-thirteen" style="width: 45%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>
						<li>
							<span class="check">
								<div class="form-check">
									<label class="form-check-label">
										<input class="form-check-input" type="checkbox" value="">
										<span class="form-check-sign">
											<span class="check"></span>
										</span>
									</label>
								</div>
							</span>
							<span class="regional">
								<span class="name">REGIONAL 10</span>
								<div class="grafic">
									<strong>70%</strong>
									<div class="bar bar-equal" style="width: 70%;"></div>
								</div>
							</span>
							<span class="cleared">
								<h4>Apuradas</h4>
								<strong>230</strong>
							</span>
							<span class="ballots">
								<h4>Urnas</h4>
								<strong>500</strong>
							</span>
						</li>-->
					</ul>
				</div>
			</div>
		</div>
    </div>
</div>
<!-- Fim do Corpo do Site -->

<?php include 'template/footer1.php' ?>

<script type="text/javascript" src="docs/app.js"></script>
