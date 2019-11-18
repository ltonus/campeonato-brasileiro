<?php

require_once('../application/config/config.php');

Auth::redirectLogedIn();

$title = 'Home - Sign in';

require_once(_VIEWS_ . 'header.view.php');
require_once(_VIEWS_ . 'login.view.php');
require_once(_VIEWS_ . 'footer.view.php');

?>