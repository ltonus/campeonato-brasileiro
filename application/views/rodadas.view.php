<script type="text/javascript" src="/campeonato-brasileiro/public/js/rodadas.js"></script>

<div id="container">
	<h3 id="titulo_rodada" data-numero="0">Rodada</h3>

	<div id="div_gerar_rodadas" style="display: none;">
		<p>Ainda não foraram geradas as rodadas para esse campeonato, clique no botão abaixo para gerar.</p>
		<button id="gerar_rodadas" class="btn-form">Gerar rodadas</button>
	</div>

	<div id="rodadas" style="display: none;">
		<div class="row">
			<div class="col-1 seta-rodadas seta-esquerda">
				<i class="fas fa-chevron-circle-left"></i>
			</div>

			<div class="col-10">
				<div id="n_rodadas"></div>
			</div>

			<div class="col-1 seta-rodadas seta-direita">
				<i class="fas fa-chevron-circle-right"></i>
			</div>
		</div>

		<div class="row">
			<div id="jogos_rodada" class="col"></div>
		</div>

		<div class="row">
			<div class="col">
				<button id="salvar_rodada" class="btn-form">Salvar</button>
				<button id="fechar_rodada" class="btn-form">Fechar Rodada</button>
			</div>
		</div>
	</div>
</div>