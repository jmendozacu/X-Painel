<? require_once($_SERVER['DOCUMENT_ROOT']."/xpainel/load.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Página não encontrada</title>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

<meta name="googlebot" content="noindex">
<meta name="robots" content="noindex">
<meta name = "viewport" content = "width=device-width, user-scalable = no, initial-scale=1">
<link rel="shortcut icon" href="img/favicon.ico" />
<link rel="shortcut icon" href="img/favicon.png" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<style>
*{text-align:left; font-family: 'Roboto', sans-serif;}
body{
    padding-bottom: 70px;
}
.relLinhas{
    padding: 20px 0;
    margin: 20px 0;
    border: 1px solid #eee;
    border-left-width: 5px;
    border-radius: 3px;
    border-left-color: #1b809e;

}
.relLinhas:hover{
    background-color: rgba(238, 238, 238, 0.2);
    border-left-color: #FF0000;
}
strong{
    color:#F00;
}
.linkver{
    margin-top: 15px;
    width:100%;width: 100%;padding: 10px 0;font-size: 18px;
}
.logoTHX{
    position: fixed;
    bottom: 0;
    left: 0;
    width:100%;
    height: 70px;

    text-align: center;
    background-color: #FFF;
    border-top: solid #CCC 1px;
    padding: 10px 0;
}
.logoTHX img{
    max-width: 60px;
}
.logoTHX *, .logoCLIENTE *{
    text-align: center;
}
h1, span{
    color: #666;
}
</style>
<meta http-equiv="refresh" content="5;url= <?=HTTP?>">
</head>
<body>
    <div class="container-fluid">
        <div class="row logoCLIENTE" style="text-align: center; margin: 10px 0;">
            <div class="md-12">
                <img src="<?=X::getParametro('logomarca')?>" />
                <p>Página não encontrada.</p>
                <p><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Redirecionando...</span></p>
                <p>Redirecionando para <?=HTTP?></p>
            </div>
        </div>    
    </div>
</body>
</html>