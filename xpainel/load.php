<?
require_once('class/config.php');
X::checkManutencao();
$cart = X_ECOMMERCE ? Cart::getCarrinho() : false;