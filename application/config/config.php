<?php

define('_DIR_', '/var/www/html/campeonato-brasileiro/');
define('_APPLICATION_', _DIR_ . 'application/');
define('_CONTROLLERS_', _APPLICATION_ . 'controllers/');
define('_VIEWS_', _APPLICATION_ . 'views/');
define('_MODELS_', _APPLICATION_ . 'models/');
define('_MODEL_', _MODELS_ . 'model/');

define('CONN_TYPE', 'mysql');

define('DB_HOST', 'localhost');
define('DB_NAME', 'campeonato');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

require_once(_CONTROLLERS_ . 'auth.controller.php');

?>