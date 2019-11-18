<?php

require_once('../application/config/config.php');

$title = 'Rodadas';

Auth::authenticate();

require_once(_VIEWS_ . 'header.view.php');
require_once(_VIEWS_ . 'menu.view.php');
require_once(_VIEWS_ . 'rodadas.view.php');
require_once(_VIEWS_ . 'footer.view.php');

?>