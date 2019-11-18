<?php

require_once('../application/config/config.php');

$title = 'Classificação';

Auth::authenticate();

require_once(_VIEWS_ . 'header.view.php');
require_once(_VIEWS_ . 'menu.view.php');
require_once(_VIEWS_ . 'classificacao.view.php');
require_once(_VIEWS_ . 'footer.view.php');

?>