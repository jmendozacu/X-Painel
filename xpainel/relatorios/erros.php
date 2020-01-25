<? require_once($_SERVER['DOCUMENT_ROOT']."/xpainel/load.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relatório de Erros - Grupo THX</title>

<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
<meta name = "viewport" content = "width=device-width, user-scalable = no, initial-scale=1">
<link rel="shortcut icon" href="img/favicon.ico" />
<link rel="shortcut icon" href="img/favicon.png" />
<script src="js/jquery.min.js"></script>
</head>
<body>
 <header>
     <img src="<?=X::getParametro('logomarca')?>" />
    </header>

    <section class="main_container">

         <h2>Relatório de Erros</h2>

            <article class="container_tabela">
             <table>

                 <tr>
                     <th style="width:100px;">Data</th>
                     <th>Cód</th>
                     <th>Erro</th>
                     <th>Arquivo</th>
                     <th>Linha</th>
                     <th>Erros</th>
                    </tr>
                   <?=X::getRelatorioErros()?>
             </table>

        </article><!-- content -->
    </section><!-- main_container -->

    <footer>
     <article class="footer_inner">
            <article class="texto">
            <a href="http://grupothx.com.br/" target="_blank">
           		<img class="logoTHX" src="http://www.grupothx.com.br/img/logo.png" />

             <h3><strong class="sucesso">Nosso sucesso depende diretamente do seu.</strong></h3>
			 </a>
            </article>
        </article>
    </footer>
	<?=file_get_contents('http://grupothx.com.br/scriptAtendimentoOnlineTodosOsSites.php')?>
</body>
</html>