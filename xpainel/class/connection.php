<?
   	define('DB_HOST','localhost');
	define('DB_NAME',''.($_SESSION[X]['idioma'] == '' ? $_SESSION[X]['idioma'] : '_'.$_SESSION[X]['idioma']));
	define('DB_USER','');
	define('DB_PASS','');
	define('DB_TYPE','mysql');
	define('DB_PORT',3306);
	define('PASTA', $_SESSION[X]['idioma']);
	// Versão 1.2 X Painel Rental Code