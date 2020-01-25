<? require_once('../load.php');
die(L::sendStatusOrder($_POST['token']) ? 'ok' : 'no thanks');
?>