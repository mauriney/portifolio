<?php
include("topo.php");
include("menu-lateral.php");
?>

<div class="conteudo container">
	<div class="view relatorio">
		<h1>RELATÓRIOS</h1>
		<ul class="menu-relatorios">
			<li class="col-md-3"><a href="relatorio-quantidade-agendas.php" title="Quantidade de Agendas" class="config qtd-agendas">Quantidade de Agendas</a></li>
			<!--<li class="col-md-3"><a href="relatorio-quem-foi.php" title="Quem Foi?" class="config quem-foi">Quem Foi?</a></li>
			<li class="col-md-3"><a href="relatorio-agenda-segmentos.php" title="Agendas por Segmentos" class="config agenda-segmento">Agendas por Segmentos</a></li>-->
			<li class="col-md-3"><a href="relatorio-agenda-bairro.php" title="Agenda por Bairro" class="config agenda-bairro">Agenda por Bairro</a></li>
			<li class="col-md-3"><a href="relatorio-desempenho-equipe.php" title="Desempenho por Equipe" class="config desempenho-equipe">Desempenho por Equipe</a></li>
			<li class="col-md-3"><a href="usuarios-demanda.php" title="Desempenho por Agentes" class="config desempenho-agente">Desempenho por Agentes</a></li>
		</ul>
	</div>
</div>

<?php include("rodape.php") ?>

<script type="text/javascript" src="js/relatorio/relatorio-painel.js"></script>