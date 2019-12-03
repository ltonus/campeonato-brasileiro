<?php

require_once('../config/config.php');
require_once(_MODELS_ . 'usuario.class.php');
require_once(_MODELS_ . 'campeonato.usuario.class.php');
require_once(_MODELS_ . 'rodada.class.php');
require_once(_MODELS_ . 'jogo.class.php');
require_once(_MODELS_ . 'campeonato.class.php');
require_once(_MODELS_ . 'campeonato.time.class.php');
require_once(_MODELS_ . 'classificacao.class.php');

new CampeonatoBrasileiro();

class CampeonatoBrasileiro {

	public function __construct() {
		$this->verificarLogin();
	}

	private function verificarLogin() {
		if (!Auth::authenticate()) {
			$handle = fopen('php://stdin', 'r');

			echo "Você não está logado ainda, entre com suas credenciais.\n";
			echo "Login: ";
			$login = fgets($handle);
			echo "Senha: ";
			$senha = fgets($handle);

			$usuarioClass = new Usuario();
			$resLogin = $usuarioClass->signIn([
				'login' => trim($login),
				'senha' => md5(trim($senha))
			]);

			if (!empty($resLogin)) {
				foreach ($resLogin as $msg) {
					echo $msg . "\n";
				}
			} else {
				Auth::setIdCampeonato();
				$this->showMenu();
			}
		} else {
			$this->showMenu();
		}
	}

	private function showMenu() {
		$this->verificarRodadasGeradas();

		echo "-------------- MENU ------------\n";
		echo "1 - Exibir classificação\n";
		echo "2 - Exibir rodadas\n";
		echo "3 - Obter rodada\n";
		echo "4 - Atualizar placar\n";
		echo "5 - Fechar rodada atual\n";
		echo "6 - Logout\n";
		echo "Escolha uma opção do menu: ";

		$this->selecionarMenu();
	}

	private function selecionarMenu() {
		$handle = fopen('php://stdin', 'r');
		$opt = fgets($handle);

		switch ((int) $opt) {
			case 1:
				$this->exibirClassificacao();
				break;
			case 2:
				$this->exibirRodadas();
				break;
			case 3:
				$this->obterRodada();
				break;
			case 4:
				$this->atualizarPlacar();
				break;
			case 5:
				$this->fecharRodadaAtual();
				break;
			case 6:
				$this->signOut();
				break;
			default:
				echo "Opção inválida, tente novamente.\n";
				break;
		}

		$this->showMenu();
	}

	private function verificarRodadasGeradas() {
		$rodadaClass = new Rodada();
		$rodadasGeradas = $rodadaClass->rodadasGeradas();

		if (!$rodadasGeradas) {
			echo "As rodadas ainda não foram geradas!\n";
			echo "Gerando rodadas, aguarde...\n";
			$rodadaClass->gerarRodadas();
			echo "Rodadas geradas com sucesso!\n\n";
		}
	}

	private function exibirClassificacao() {
		$classificacaoClass = new Classificacao();
		$dadosClassificacao = $classificacaoClass->obter();

		printf("\n┌────────────────────┬─────┬────┬────┬────┬─────┬─────┬─────┬────────┐\n");
		printf("│ %-21s│ %-4s│ %-3s│ %-3s│ %-3s│ %-4s│ %-4s│ %-4s│ %-7s│\n", 'Classificação', 'P', 'V', 'E', 'D', 'GP', 'GC', 'SG', '%');
		printf("├────┬───────────────┼─────┼────┼────┼────┼─────┼─────┼─────┼────────┤\n");

		foreach ($dadosClassificacao['classificacoes'] as $i => $classificacao) {
			$porcentagem = $classificacao['pontuacao'] / (3 * $dadosClassificacao['numeroRodadaAtual']) * 100;
			printf("│ %-3s│ %-14s│ %-4s│ %-3s│ %-3s│ %-3s│ %-4s│ %-4s│ %-4s│ %-7.2f│\n", $i + 1, $this->removerCaracter($classificacao['time']), $classificacao['pontuacao'], $classificacao['numeroVitoria'], $classificacao['numeroEmpate'], $classificacao['numeroDerrota'], $classificacao['saldoGolPro'], $classificacao['saldoGolContra'], $classificacao['saldoGolPro'] - $classificacao['saldoGolContra'], $porcentagem);
		}

		printf("└────────────────────┴─────┴────┴────┴────┴─────┴─────┴─────┴────────┘\n\n");
	}

	private function exibirRodadas() {
		$rodadaClass = new Rodada();
		$dadosRodadas = $rodadaClass->load(['idCampeonato' => Auth::$idCampeonato]);

		echo "\nRodadas do campeonato brasileiro\n";

		printf("┌─────┬────────┬────────────┬──────────┐\n");
		printf("│ %-4s│ %-8s│ %-11s│ %-9s│\n", 'Id', 'Número', 'Data', 'Fechada');
		printf("├─────┼────────┼────────────┼──────────┤\n");

		foreach ($dadosRodadas as $rodada) {
			printf("│ %-4s│ %-7s│ %-11s│ %-9s│\n", $rodada['id'], $rodada['numero'], DateTime::createFromFormat('Y-m-d', $rodada['data'])->format('d/m/Y'), ($rodada['fechada'] ? 'S' : 'N'));
		}

		printf("└─────┴────────┴────────────┴──────────┘\n\n");
	}

	private function obterRodada() {
		$handle = fopen('php://stdin', 'r');

		echo "Digite o id da rodada:\n";
		$opt = fgets($handle);

		$rodadaClass = new Rodada();
		$dadosRodada = $rodadaClass->obter((int) $opt);

		if ($dadosRodada) {
			echo "\nRodada número " . $dadosRodada['rodada']['numero'] . ' - ' . $dadosRodada['rodada']['data'] . "\n";

			printf("┌───────────────────────────────────┬────────┬──────────┬───────────┐\n");
			printf("│ %-35s│ %-7s│ %-9s│ %-10s│\n", 'Estádio', 'idJogo', 'Mandante', 'Visitante');
			printf("├───────────────────────────────────┼────────┼─────┬────┼─────┬─────┤\n");

			foreach ($dadosRodada['jogos'] as $jogo) {
				printf("│ %-34s│ %-7s│ %-4s│ %-3s│ %-4s│ %-4s│\n", $this->removerCaracter($jogo['estadio']), $jogo['idJogo'], $jogo['siglaTimeMandante'], $jogo['golTimeMandante'], $jogo['siglaTimeVisitante'], $jogo['golTimeVisitante']);
			}

			printf("└───────────────────────────────────┴────────┴─────┴────┴─────┴─────┘\n\n");
		} else {
			echo "\nRodada inválida.\n\n";
		}
	}

	private function atualizarPlacar() {
		$handle = fopen('php://stdin', 'r');

		echo "Digite o id do jogo:\n";
		$idJogo = (int) fgets($handle);
		$jogoClass = new Jogo();
		$dadosJogo = $jogoClass->obterJogoRodadaAtual($idJogo);

		if ($dadosJogo) {
			echo 'Digite a quantidade de gols do time ' . $dadosJogo['nomeTimeMandante'] . ' (' . $dadosJogo['siglaTimeMandante'] . "): \n";
			$golTimeMandante = (int) fgets($handle);
			echo 'Digite a quantidade de gols do time ' . $dadosJogo['nomeTimeVisitante'] . ' (' . $dadosJogo['siglaTimeVisitante'] . "): \n";
			$golTimeVisitante = (int) fgets($handle);

			$jogoClass->save([
				'id' => $idJogo,
				'golTimeMandante' => $golTimeMandante,
				'golTimeVisitante' => $golTimeVisitante
			]);

			echo "\nPlacar atualizado com sucesso!\n\n";
		} else {
			echo "\nJogo inválido. Certifique-se que ele pertence a rodada atual.\n\n";
		}
	}

	private function fecharRodadaAtual() {
		$handle = fopen('php://stdin', 'r');

		echo "Deseja realmente fechar a rodada atual? (S/n)\n";
		$opt = fgets($handle);

		if (mb_strtolower(trim($opt)) == 's') {
			$rodadaClass = new Rodada();
			$dadosRodada = $rodadaClass->obterRodadaAtual();
			$rodadaClass->fecharRodada($dadosRodada['id']);
			echo "\nRodada número " . $dadosRodada['numero'] . " fechada com sucesso!\n\n";
		} else {
			echo "\nOperação cancelada.\n\n";
		}
	}

	private function signOut() {
		$usuarioClass = new Usuario();
		$usuarioClass->signOut();
		echo "\nAté logo!\n\n";

		exit(0);
	}

	private function removerCaracter($string) {
		$string = str_replace('í', 'i', $string);
		$string = str_replace('á', 'a', $string);
		$string = str_replace('ê', 'e', $string);
		$string = str_replace('é', 'e', $string);
		$string = str_replace('ã', 'a', $string);

		return $string;
	}

}


?>
