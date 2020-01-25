<?
class Paginacao
{
    static function layout($indice, $chave = false, $valor = false)
    {
        try
        {
            $layout=array();
            $layout['open'] = '
            <div class="text-center mgt-30">
  						<span class="current-page">{pag}</span>
  						<ul class="page-pagination style-1">';
            $layout['setaE'] = '<li><a href="{url}"><i class="icon icon-arrow-left"></i></a></li>';
            $layout['setaD'] = '<li><a href="{url}"><i class="icon icon-arrow-right"></i></a></li>';
            $layout['pagina'] = '<li><a class="page-numbers" href="{url}"></a></li>';
            $layout['paginaAtual'] = '<li><span class="page-numbers current"></span></li>';
            $layout['close'] = '
            </ul>
          </div>';
            if($chave &&  $valor)
            {
               return str_replace($chave, $valor, $layout[$indice]);
            }

            return $layout[$indice];

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPaginacao($query,$final=9, $setas = true, $limitePaginas = 13)
    {
        try
        {
            $_GET['pag'] = isset($_GET['pag']) && is_numeric($_GET['pag']) ? $_GET['pag'] : 1;

            $setSql = explode("FROM", $query);
            $setSql = $setSql[0];
            $setSql = str_replace($setSql,'',$query);
            $setSql = "SELECT count(*) as paginacaototal FROM (SELECT 1 ".$setSql.") as paginacaototal";
            $result = Sql::_fetch($setSql);
            $total_de_linhas = $result['paginacaototal'];
            $total_de_paginas = ceil ($total_de_linhas/$final);
            $inicio = ($_GET['pag']-1) * $final;

            $inicioing_no = $inicio + 1;

            if ($total_de_linhas - $inicio < $final)
            {
                $ultima_pagina = $total_de_linhas;
            }
            else if($total_de_linhas - $inicio >= $final)
            {
                $ultima_pagina = $inicio + $final;
            }

            if($total_de_linhas > $final)
            {
                if($total_de_linhas>0)
                {
                    if ($total_de_linhas - $ultima_pagina > $final)
                    {
                        $var2 = $final;
                    }
                    else if ($total_de_linhas - $ultima_pagina <= $final)
                    {
                        $var2 = $total_de_linhas - $ultima_pagina;
                    }


                    $setaesq = $setadir = '';
                    if($setas)
                    {
                        $bkpPage=$_GET['pag'];
                        if($_GET['pag'] != 1 && $total_de_paginas > 1)
                        {
                            $_GET['pag']--;
                            $parametros = strtok($_SERVER['REQUEST_URI'],'?').'?'.http_build_query($_GET);
                            $setaesq = self::layout('setaE', '{url}', $parametros);
                        }

                        $_GET['pag'] = $bkpPage;
                        $setadir = '';
                        if($_GET['pag'] != $total_de_paginas)
                        {
                            $_GET['pag']++;
                            $parametros = strtok($_SERVER['REQUEST_URI'],'?').'?'.http_build_query($_GET);

                            $setadir = self::layout('setaD', '{url}', $parametros);
                        }
                        $_GET['pag'] = $bkpPage;
                    }
                    $variavel = str_replace('{pag}', $_GET['pag'], self::layout('open'));
                    $paginacao.=$variavel.$setaesq;


                    $paginaAtual=$_GET['pag'];
                    for ($i=1; $i<=$total_de_paginas; $i++)
                    {
                        if($i==$paginaAtual)
                        {
                            $paginacao.=self::layout('paginaAtual', '{pag}', $i);
                        }
                        else
                        {
                             $_GET['pag']=$i;
                             $parametros = strtok($_SERVER['REQUEST_URI'],'?').'?'.http_build_query($_GET);

                            if($limitePaginas > 0)
                            {
                                if($i+$limitePaginas > $paginaAtual && $i-$limitePaginas < $paginaAtual)
                                {
                                    $paginacao.=self::layout('pagina', array('{url}', '{pag}'), array($parametros, $i));
                                }
                            }
                            else
                            {
                                $paginacao.=self::layout('pagina', array('{url}', '{pag}'), array($parametros, $i));
                            }
                        }

                    }

                    $paginacao.=$setadir.self::layout('close');
                }

            }
            $queryLimit=$query." LIMIT {$inicio},{$final}";
            return array('paginacao' => $paginacao, 'query' => $queryLimit);

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPaginacaoAjax($query, $limitePaginas = 8)
    {
        try
        {
            $_GET['pag'] = isset($_GET['pag']) && is_numeric($_GET['pag']) ? $_GET['pag']+$limitePaginas : 0;

            $bt='
            <div class="col-md-12 align-center btpaginacaoAjax">
                <a href="javascript:paginacaoAjaxFotos('.$_GET['pag'].')" class="ff_button">Carregar mais</a>
            </div>
            <script>
            recarregaLightBox();
            </script>';


            $query.=" LIMIT {$_GET['pag']},{$limitePaginas}";

            return array('query' => $query, 'paginacao' => $bt);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
}
