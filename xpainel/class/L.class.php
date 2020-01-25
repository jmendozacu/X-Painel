<?
class L
{

    static function setLinkPagamento($pedido_id, $url)
    {
        try
        {
            $result = Sql::_query("UPDATE pedidos SET pedidos_link_pagamento = '{$url}' WHERE pedidos_id = '{$pedido_id}' ");
            return $url;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getTransportadoras()
    {
      try
      {
        $GLOBALS['Xjs'][] = 'getTransportadoras();';
        return '<div id="fretes">Aguardando Cep</div>';
      }
      catch( Exception $e)
      {
        X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
      }
    }
    static function setRevisarPedido()
    {
        try
        {
            $obg = array(
              'clientes_endereco' => 'Informe o endereço para entrega.',
              'clientes_numero' => 'Informe o número para a entrega',
              'clientes_bairro' => 'Informe o bairro para entrega',
              'clientes_estado' => 'Informe o estado para entrega',
              'clientes_cidade' => 'Informe a cidade para entrega',
              'clientes_pagamento' => 'Escolha uma forma de pagamento',
              'frete_escolhido' => 'Escolha uma forma de entrega');

            foreach($obg as $name => $erro)
            {
              if(!isset($_POST[$name][1]))
              {
                echo "<script>parent.document.getElementsByName('{$name}')[0].focus();</script>";
                return X::alert($erro.$_POST[$name], false, true);
              }
            }

            $_SESSION[X]['sessao_cliente'] = $_POST;

            Cart::setDado('valor_frete', Frete::getFrete('valor'));
            Cart::setDado('total', Frete::getFrete('valor')+Cart::getDado('subtotal'));


            return "<script>top.location='".HTTP."/revisar-pedido.php';</script>";

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getGateways()
    {
      try
      {
        $GLOBALS['Xjs'][] = "getGateways();";
        return '<div id="gateways"><img class="loadingImgX" style="max-width: 32px !important;max-height: 32px !important;" src="'.HTTP.'/xpainel/imagens/loading.gif" /> Carregando Formas de pagamento</div>';
      }
      catch( Exception $e)
      {
        X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
      }
    }
    static function sendStatusOrder($token)
    {
        try
        {
            $produtos='';
            $result = Sql::_fetch("SELECT p.*, c.* FROM pedidos p INNER JOIN clientes c ON c.clientes_id=p.clientes_id WHERE pedidos_token='{$token}'");

            if(! $result)
            {
                return false;
            }

            if($result['pedidos_status'] == 'Cancelado')
            {

                L::getRetornaProdutoEstoque($result['pedidos_id']);
            }

            $mensagem = 'Olá <strong>'.$result['clientes_nome'].'</strong> ,

            seu pedido número <strong>'.$result['pedidos_id'].'</strong> teve o status alterado.<br /> <hr />

            <strong style="color:#F00">Status de seu pedido : </strong><strong>'.$result['pedidos_status'].'</strong>

            <br />

            <hr />';



            $forma_de_pagamento = L::getGateway($result['pedidos_gateway']);



            if($result['pedidos_status'] == 'Enviado' && $result['pedidos_rastreamento'] != '')

            $mensagem.= '<strong style="color:#F00">Código de Rastreamento: </strong> <strong>'.$result['pedidos_rastreamento'].'</strong><br />

            <a href="http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI='.$result['pedidos_rastreamento'].'">Clique aqui para rastrear seu pedido junto aos correios.</a><br /> <hr />';

            $mensagem.='<strong style="color:#F00">Forma de Pagamento:</strong> <strong>'.$forma_de_pagamento['gateway_nome'].'</strong><br /><br /><hr />';

            $link = $result['pedidos_link_pagamento'];

            if($result['pedidos_status'] == 'Aguardando Pagamento' || $result['pedidos_status'] == 'Pedido Realizado')
            {
                $mensagem.=
                '<p align="center"><a href="'.$link.'"><img src="'.HTTP.'/xpainel/gateway/'.$result['pedidos_gateway'].'/'.$result['pedidos_gateway'].'.png" style="margion: 0 auto;" title="Clique aqui para pagar" alt="Clique aqui para pagar Agora" /></a></p><hr />';
            }

            $mensagem.= '<strong style="color:#F00">Endereço Para Entrega: </strong><br />

                    <strong>CEP: '.$result['pedidos_entrega_cep'].'<br />

                    '.$result['pedidos_entrega_endereco'].' - Numero: '.$result['pedidos_entrega_numero'].'<br />

                    Complemento: '.$result['pedidos_entrega_complemento'].'<br />

                    Bairro: '.$result['pedidos_entrega_bairro'].'<br />

                    Cidade: '.$result['pedidos_entrega_cidade'].' / '.$result['pedidos_entrega_estado'].'<br />

                    Destinatário: '.$result['pedidos_entrega_destinatario'].' </strong><br />
                    <hr />';





            $mensagem.='<strong style="color:#F00">Forma de Envio: </strong> <strong>'.$result['pedidos_forma_entrega'].'</strong><br /><hr />';

            $mensagem.=L::getPedidoEmail($result['pedidos_id'],$mensagem,$result['clientes_id']);

            return E::email($result['clientes_email'],$result['clientes_nome'],'Atualização do Pedido #ID - '.$result['pedidos_id'],$mensagem);

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function msgCompra($gateway, $pedido_id, $link_pagamento)
    {
        try
        {
            $link_pagamento = '
            <a title="Clique para pagar Agora" target="_blank"  href="'.$link_pagamento.'">
                <img src="'.HTTP.'/xpainel/gateway/'.$gateway.'/'.$gateway.'.png" title="Clique aqui para pagar" alt="Clique aqui para pagar Agora" />
            </a>';

            echo '
                <div class="agradecimento">
                    <h1>Parabens Pela Compra! =) <br />Seu pedido será enviado após confirmação do pagamento!</h1>
                    <p>Número do pedido: <strong>'.$pedido_id.'</strong></p>
                    <p>'.$link_pagamento.'</p>
                </div>';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPedidoEmail($pedido,$msg,$cliente)
    {
        try
        {
            $result = Sql::_fetchall("SELECT ped.*,pedi.*,prod.*,

            date_format(pedidos_criacao, '%d/%m/%Y %T') AS pedidos_criacao

            FROM pedidos ped

            INNER JOIN pedido_itens pedi ON ped.pedidos_id=pedi.pedidos_id

            INNER JOIN produto prod ON pedi.produto_id=prod.produto_id

            AND ped.pedidos_id={$pedido} AND ped.clientes_id={$cliente}");



            if(! $result)
            {
                return 'Erro ao capturar produtos do pedido';
            }

            $pedido ='

                    <strong style="color:#F00">Itens do Pedido:</strong>

                    <table  style="float:left; border-collapse:collapse; border:solid #CCC 1px; width:100%;" cellpadding="5" cellspacing="5" class="table">';


            foreach ($result as $item)
            {

                $pedido.='

                <tr>

                <th style="border:solid #EEE 1px; width: 90px;">
                <img style="max-height:75px; max-width: 60px;" src="'.HTTP.'/imagens/produtos/'.$item['produto_id'].'_1_1.'.$item['produto_extensao1'].'" />

                </th>

                <th style="border:solid #EEE 1px;">'.$item['produto_nome'].'<br />'.$item['pedido_itens_parametro1'].':'.$item['pedido_itens_parametro2'].'</th>

                <th style="border:solid #EEE 1px; width: 90px;">R$ '.$item['pedido_itens_valor'].'</th>

                </tr>

                ';

            }

                    $pedido.='

                    <tr>
                        <th style="border:solid #EEE 1px;"></th>

                        <th style="border:solid #EEE 1px; text-align:right">Frete: </th>

                        <th colspan="2" style="border:solid #EEE 1px;">R$ '.$result[0]['pedidos_frete'].'</th>

                    </tr>

                    <tr>
                        <th style="border:solid #EEE 1px;"></th>

                        <th style="border:solid #EEE 1px; text-align:right">Total: </th>

                        <th style="border:solid #EEE 1px;">R$ '.$result[0]['pedidos_valor'].'</th>

                    </tr>';

                $pedido.='</table>';

            return $pedido;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getCaracteristicas($lista)
    {
        try
        {
            $caracteirsticas = array('pedido_itens_parametro1', 'estoque_grade_linha_primaria', 'estoque_grade_linha_secundaria', );
    
            return U::clearStr($ret);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPedidos()
    {
        try
        {
           Cliente::checkLogin();

            $listaPedidos = '';

            $result = Sql::_fetchAll("SELECT * FROM pedidos WHERE clientes_id=? ORDER BY pedidos_id DESC", array(Cliente::getDado('clientes_id')));



            if(! $result)
            {
                return U::ocultaX('<h4>Nenhum Pedido localizado!</h4>');
            }

            foreach($result as $pedido)
            {

                $link_pagamento = $pedido['pedidos_link_pagamento'];
                if($pedido['pedidos_gateway'] == 'pagseguro')
                {
                  $link_pagamento = L::setLightBoxPagSeguro($link_pagamento);
                }

                if($pedido['pedidos_status'] == 'Aguardando Pagamento' || $pedido['pedidos_status'] == 'Pedido Realizado')
                {
                    $pagar = '
                    <a href="'.$link_pagamento.'" class="b-btn f-btn b-btn-sm f-btn-sm b-btn-default f-primary-b">
                     <img class="img-responsive" src="'.HTTP.'/xpainel/gateway/'.$pedido['pedidos_gateway'].'/'.$pedido['pedidos_gateway'].'.png" />
                   </a>';
                }

                $listaPedidos.='
                <tr class="first odd">
                  <td><h2 class="product-name"> <a href="#" class="negrito_thx">'.$pedido['pedidos_id'].'</a> </h2></td>
                  <td class="a-right"><span class="cart-price"> <span class="negrito_thx">'.U::formataData($pedido['pedidos_criacao'], '%d/%m/%Y às %H:%M').'</span> </span></td>
                  <td class="a-right movewishlist"><span class="cart-price"> <span class="negrito_thx">R$ '.$pedido['pedidos_valor'].'</span> </span></td>
                  <td class="a-right"><span class="cart-price"> <span class="negrito_thx">'.$pedido['pedidos_status'].'</td>
                  <td class="a-center last"><a class="visualizar_botao_thx" title="Detalhes" href="meu-pedido.php?id='.$pedido['pedidos_id'].'"></a></td>
                  <td class="a-center last">'.$pagar.'</td>
                </tr>';
            }

            return $listaPedidos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPedidosQtd()
    {
        try
        {
           L::checkLogin();

           $ct = Sql::_fetch("SELECT count(*) as pedidos FROM pedidos WHERE clientes_id=?", array(Cliente::getDado('clientes_id')));
           return $ct['pedidos'];
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getPedido()
    {
        try
        {
            Cliente::checkLogin();

            if(!isset($_GET['id']))
            {
                goHome('meus-pedidos.php');
            }


            $result = Sql::_fetchAll("SELECT ped.*,pedi.*,prod.*,

                                        date_format(pedidos_criacao, '%d/%m/%Y %T') AS pedidos_criacao

                                            FROM pedidos ped

                                                INNER JOIN pedido_itens pedi ON ped.pedidos_id=pedi.pedidos_id

                                                    INNER JOIN produto prod ON pedi.produto_id=prod.produto_id

                                                        AND ped.pedidos_id=? AND ped.clientes_id=?", array($_GET['id'], Cliente::getDado('clientes_id')));
            X::printArray($result);
            if(! $result)
            {
                return U::ocultaX('<h2>Nenhum pedido localizado!</h2>');
            }

             $pedido = '
             <caption>
                Pedido '.$result[0]['pedidos_id'].' - Criado em  '.$result[0]['pedidos_criacao'].'
             </caption>';

            foreach ($result as $item)
            {
                echo '<pre>';print_r($item);
              $pedido.='
              <tr class="first last">
                  <td rowspan="1"><img class="img-responsive" src="'.U::getImg('imagens/produtos/'.$item['produto_id'].'_1_1.'.$item['produto_extensao1']).'" /></td>
                  <td rowspan="1"><span class="nobr">'.$item['produto_nome'].'</span></td>
                  <td colspan="1" class="a-center"><span class="nobr">R$ '.U::moeda($item['pedido_itens_valor']).'</span></td>
                  <td class="a-center" rowspan="1">'.$item['pedido_itens_quantidade'].'</td>
                  <td colspan="1" class="a-center">R$ '.U::moeda($item['pedido_itens_valor']*$item['pedido_itens_quantidade']).'</td>
                  <td class="a-center" rowspan="1">&nbsp;</td>
                </tr>';

          }

          $pagar = '';

          $link_pagamento = $item['pedidos_link_pagamento'];
          if($item['pedidos_gateway'] == 'pagseguro')
          {
            $link_pagamento = L::setLightBoxPagSeguro($link_pagamento);
          }

          if($item['pedidos_status'] == 'Aguardando Pagamento' || $item['pedidos_status'] == 'Pedido Realizado')
          {
              $pagar = ' - <a href="'.$link_pagamento.'" style="color: #f00;"><img title="Clique aqui para pagar" alt="Clique aqui para pagar" src="'.HTTP.'/xpainel/gateway/'.$item['pedidos_gateway'].'/'.$item['pedidos_gateway'].'.png" /></a>';
          }

         $pedido.='
            <tr class="first last">
              <td colspan="3">
                <strong>Endereço de Entrega:</strong><br />
                    '.$item['pedidos_entrega_endereco'].' - '.$item['pedidos_entrega_cidade'].' - '.$item['pedidos_entrega_estado'].' - Cep '.$item['pedidos_entrega_cep'].'<br />
                   <strong>Forma de Pagamento:</strong> '.L::getGateway($item['pedidos_gateway'],'gateway_nome').'<br />
                    <strong>Status: </strong> '.$item['pedidos_status'].'<br />
                    <strong>Data:</strong>'.$item['pedidos_criacao'].'<br />
              </td>
              <td colspan="3">
                <strong>Frete ('.$item['pedidos_forma_entrega'].')</strong> <br /> R$ '.U::moeda($item['pedidos_frete']).'<br /><br />
                    <strong>Total</strong> <br /> R$ '.U::moeda($item['pedidos_valor']).'
              </td>
            </tr>
            <tr>
                <td colspan="6"><center>'.$pagar.'</center></td>
            </tr>';

            return $pedido;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }



    static function getGateway($gateway,$campo = '*')
    {
        try
        {
            $result = Sql::_fetch('SELECT '.$campo.' FROM gateway WHERE gateway_parametro =  ?', array($gateway));
            if($campo != '*')
            {
                    return $result[$campo];
            }
            return $result;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function geVideos()
    {
        try
        {
           $retorno = '';
           $result = Sql::_fetchAll('SELECT * FROM video WHERE video_deletado = 0 AND video_ativo = 1');
           foreach ($result as $res)
           {
               $retorno .= '<div class="grid-item illustrations">
                        <a class="content fancybox-media" href="'.$res['video_url'].'">

                            <img src="'.HTTP.'/imagens/videos/'.$res['video_id'].'_1_1.'.$res['video_capa_extensao1'].'" data-full="'.HTTP.'/imagens/videos/'.$res['video_id'].'_1_1.'.$res['video_capa_extensao1'].'" alt="img"/>
                            <div class="info">
                                <div class="head-text">'.$res['video_nome'].'</div>
                            </div>
                        </a>
                        <div class="social">
                            '.Social::getRedesSociais().'
                        </div>
                    </div>';
           }
            return $retorno;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function checkPagamento($url = 'entrega-e-pagamento.php')
    {
        try
        {
            if(! isset($_SESSION[X]['sessao_cliente']['clientes_pagamento'][1]))
            {
                U::goHome($url);
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }






    static function getProduto()
    {
        try
        {
            if(!isset($_GET['produto']))
            {
                U::goHome();
            }
            $result = Sql::_fetch('SELECT p.*, s.subcategoria_nome, c.categoria_nome
                                        FROM produto p
                                            INNER JOIN subcategoria s ON s.subcategoria_id=p.subcategoria_id
                                                INNER JOIN categoria c ON s.categoria_id=c.categoria_id
                                                    WHERE p.produto_deletado=0 AND p.produto_id = ?', array($_GET['produto']));
            if(! $result)
            {
                U::goHome();
            }

            $i = 1;
            $result['imagensZoom'] = $result['imagensIcos'] = '';

            for($i=1; $i<=3; $i++)
            {
                if(file_exists('imagens/produtos/'.$result['produto_id'].'_'.$i.'_2.'.$result['produto_extensao'.$i]))
                {
                    $result['imagensZoom'].='
                    <a href="#" class="item">
                        <img src="'.HTTP.'/imagens/produtos/'.$result['produto_id'].'_'.$i.'_2.'.$result['produto_extensao'.$i].'" alt="img" data-fancybox="'.HTTP.'/imagens/produtos/'.$result['produto_id'].'_'.$i.'_3.'.$result['produto_extensao'.$i].'" data-zoom-image="'.HTTP.'/imagens/produtos/'.$result['produto_id'].'_'.$i.'_3.'.$result['produto_extensao'.$i].'"/>
                    </a>';

                    $result['imagensIcos'].='
                    <div class="item">
                        <img src="'.HTTP.'/imagens/produtos/'.$result['produto_id'].'_'.$i.'_1.'.$result['produto_extensao'.$i].'" alt="img"/>
                    </div>';
                }


            }




            $result['imagensIcos'].='
            <a class="item fancybox-media" href="https://www.youtube.com/watch?v=YL5KfJGRCVE">
                <img src="images/video.jpg" alt="video">
            </a>';



            $result['imagem'] = U::getImg('imagens/produtos/'.$result['produto_id'].'_1_2.'.$result['produto_extensao1']);
            $result['link'] =  '<a id="addto-cart" href="'.HTTP.'/carrinho.php?acao_carrinho=add&produto='.$result['produto_id'].'" class="le-button huge">Comprar</a>';

            if($result['produto_ativo'] != 1)
            {
                $result['link'] =  '<a id="addto-cart" href="'.HTTP.'contato.php?assunto=aviseme&produto='.$result['produto_id'].'" class="le-button huge">Produto Indisponível <br /><small style="color: #F00">Me avise quanto estiver disponível</small></a>';
            }

            return $result;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getProdutosRelacionados($p)
    {
        try
        {
            $retorno = '';
            $result = Sql::_fetchAll('SELECT * FROM produto WHERE produto_deletado=0 AND produto_ativo=1 AND subcategoria_id=? AND produto_id != ? ORDER BY RAND() LIMIT 12', array($p['subcategoria_id'], $p['produto_id']));

            foreach ($result as $res)
            {
                $link = HTTP.'/produto.php?produto='.$res['produto_id'];
                $retorno.='
                <div class=" no-margin carousel-item product-item-holder size-small hover" style="cursor:pointer" onClick="location=\''.$link.'\'">
                    <div class="product-item">
                        <div class="image">
                            <img style="max-height: 150px; min-height: 150px;" alt="" src="'.U::getImg('imagens/produtos/'.$res['produto_id'].'_1_1.'.$res['produto_extensao1']).'" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">'.$res['produto_nome'].'</a>
                            </div>
                            <div class="brand">'.$res['valor5'].'</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">R$ '.$res['valor2'].'</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="'.$link.'" class="le-button">Ver Produto</a>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            if($retorno != '')
            {
                $retorno = '
                <section id="recently-reviewd" class="wow fadeInUp">
                    <div class="container">
                        <div class="carousel-holder hover">
                            <div class="title-nav">
                                <h2 class="h1">Produtos Relacionados</h2>
                                <div class="nav-holder">
                                    <a href="#prev" data-target="#owl-recently-viewed" class="slider-prev btn-prev fa fa-angle-left"></a>
                                    <a href="#next" data-target="#owl-recently-viewed" class="slider-next btn-next fa fa-angle-right"></a>
                                </div>
                            </div><!-- /.title-nav -->

                            <div id="owl-recently-viewed" class="owl-carousel product-grid-holder">
                                '.$retorno.'
                            </div><!-- /#recently-carousel -->

                        </div><!-- /.carousel-holder -->
                    </div><!-- /.container -->
                </section><!-- /#recently-reviewd -->';
            }

            return $retorno;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function setPedido()
    {
        try
        {
            Cart::checkCarrinho();
            Cliente::checkLogin();
            Frete::checkEntrega();




            $produtos='';
            $token= U::getToken(30);
            $pedidos_valor = U::moeda(Cart::getDado('total'));
            $destinatarioOpt = $_SESSION[X]['sessao_cliente']['clientes_destinatario'] == '' ? $_SESSION[X.X]['clientes_nome'] : $_SESSION[X]['sessao_cliente']['clientes_destinatario'];

            $result = Sql::_query("INSERT INTO pedidos (
            pedidos_status,
            pedidos_criacao,
            clientes_id,
            pedidos_valor,
            pedidos_gateway,
            pedidos_html,
            pedidos_token,
            pedidos_frete,
            pedidos_entrega_cep,
            pedidos_entrega_numero,
            pedidos_entrega_complemento,
            pedidos_entrega_endereco,
            pedidos_entrega_cidade,
            pedidos_entrega_estado,
            pedidos_entrega_bairro,
            pedidos_forma_entrega,
            pedidos_entrega_destinatario
            )
            VALUES (
            'Pedido Realizado',
            NOW(),
            {$_SESSION[X.X]['clientes_id']},
            '{$pedidos_valor}',
            '".$_SESSION[X]['sessao_cliente']['clientes_pagamento']."',
            '',
            '{$token}',
            '".Frete::getFrete('valor')."',
            '{$_SESSION[X]['sessao_cliente']['clientes_cep']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_numero']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_complemento']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_endereco']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_cidade']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_estado']}',
            '{$_SESSION[X]['sessao_cliente']['clientes_bairro']}',
            '".Frete::getFrete('nome')."',
            '{$destinatarioOpt}'
            )");

            return self::setPedidoItens($result,$token);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function setPedidoItens($pedidoId,$token)
    {

        try
        {

            $produtos='';
            $array_produtos='';
            foreach($_SESSION[X]['carrinho']['produtos'] as $linha)
            {
                $subtotal = U::moeda($linha['produto_qtd']*$linha['produto_preco']);
                $produtos.="
                ({$pedidoId},
                '{$linha['produto_qtd']}',
                '{$linha['produto_preco']}',
                '{$subtotal}',
                '{$linha['produto_id']}',
                '{$linha['estoque_grade_linha_primaria']}',
                '{$linha['estoque_grade_linha_secundaria']}'),";
                $array_produtos.=$linha['produto_id'].', ';
            }

            $array_produtos = trim($array_produtos,',');
            $produtos=substr($produtos,0,-1);

            Sql::_query("INSERT INTO pedido_itens(pedidos_id,pedido_itens_quantidade, pedido_itens_valor, pedido_itens_total,produto_id,pedido_itens_parametro1,pedido_itens_parametro2) VALUES {$produtos}");



            //self::setEstoque();

            return self::enviaGateway($pedidoId,$token);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }

    }
    static function getDadosPedido()
    {
        try
        {
            if(! isset($_GET['token']))
            {
                U::goHome();
            }
            $pedido = '';
            $result = Sql::_fetch('SELECT * FROM pedidos WHERE pedidos_token = ?', array($_GET['token']));

            if(! $result)
            {
                U::goHome();
            }
            return $result;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getDescontoPedido()
    {
      try
      {
        return;
      }
      catch( Exception $e )
      {
        X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
      }
    }
    static function enviaGateway($pedido_id,$token)
    {
        $funcao = ucfirst($_SESSION[X]['sessao_cliente']['clientes_pagamento']);
        $script = ROOT.'/xpainel/gateway/'.$_SESSION[X]['sessao_cliente']['clientes_pagamento'].'/setPedido'.$funcao.'.php';

        if(file_exists($script))
        {
            //die($script);
            require_once($script);
            call_user_func('setOrder'.$funcao,$pedido_id,$token);
        }
        else
        {
            return '<br /><br /><br /><br /><br />

                    <div id="agradecimento">

                        <p style="text-align: center;font-size: 20px;  font-weight: bold;">

                            <span>

                                Ooops :(

                            </span>

                            <br />

                            Encontramos um erro no processamento do seu pedido.

                            <br/>

                            <br/>

                            <a title="Retornar para o site" href="'.HTTP.'">Retornar ao Site</a>

                        </p>

                    </div>';
        }

    }
    static function getProdutosHome()
    {
        try
        {
            $produtos='';
            $result = Sql::_fetchall('SELECT * FROM produto WHERE produto_deletado=0 AND produto_ativo=1 ');
            $i = 0;
            foreach ($result as $res)
            {
                $i++;
                $imagem=U::getImg('/imagens/produtos/'.$res['produto_id'].'_1_2.'.$res['produto_extensao1']);
                $link = 'produto.php?produto='.$res['produto_id'];

                $produtos.='
                <div class="col-md-3 col-sm-3 col-xs-12 tt-col" '.U::divLink($link).'>
                            <div class="product right-hover" data-product-id="9">
                                <div class="substrate"></div>
                                <div class="product-main-inside">
                                    <div class="product-image-block">
                                        <!--<div class="product-label" style="bottom:0; top: inherit; width: 100%">
                                            <span class="product-label-discount" style="width: 100%">FRETE GRÁTIS !!!</span>
                                        </div>-->
                                        <img src="'.$imagem.'" alt="product"/>
                                        <div class="button-open" '.U::divLink($link).'>
                                                <span class="icon-open icon"></span>
                                            </div>
                                    </div>
                                    <div class="product-info-block">
                                        <div class="row">
                                            <div class="col-left">
                                                <div class="product-description">
                                                    <a href="'.$link.'">'.$res['produto_nome'].'</a>
                                                </div>
                                                <p class="price">
                                                    <span class="single-price">R$ '.$res['valor1'].'</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-hidden-bl-wrapp-1">
                                    <div class="product-hidden-block-1">
                                        <div class="hidden-body">
                                            <div class="row" style="text-align:center">
                                               <a href="'.$link.'" class="tt-btn-type1" style="margin:0">COMPRAR AGORA</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        if($i == 4)
                        {
                            $produtos.='<octor class="col-md-12 col-sm-12 col-xs-12 tt-col" style="    margin: 30px 0;"><hr /></octor>';
                            $i = 0;
                        }
            }

            return $produtos;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function setLightBoxPagSeguro($url)
    {
        try
        {
            $code = explode('code=', $url);
            $code = end($code);
            return "javascript:setPagSeguro('{$code}')";

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function setEstoque()
    {

        try
        {
            foreach ($_SESSION[X]['carrinho']['produtos'] as $retirar)
            {
                Sql::_query("UPDATE produto SET produto_ativo = 0 WHERE produto_id = {$retirar['produto_id']}");
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function getProdutos()
    {
        try
        {
            if(! isset($_GET['subcategoria']) && !isset($_GET['busca']))
            {
                return array('','
                <section id="featureCategory" class="row contentRowPad">
                    <div class="row m0 sectionTitle">
                        <h3>Nossos Produtos</h3>
                        <h5>Escolha uma Categoria</h5>
                    </div>
                    <div class="container">
                        <div class="row produtos">
                            '.S::getSubcategorias().'
                        </div>
                    </div>
                </section>');
            }

            $condicao = '';

            if(isset($_GET['subcategoria']))
            {
                $condicao = " AND p.subcategoria_id=".(int)$_GET['subcategoria'];
            }


            $produtos = array('titulo' => '', 'produtos' => '');

            $query = "SELECT p.*, s.subcategoria_nome, c.categoria_nome
                            FROM produto p
                                INNER JOIN subcategoria s ON s.subcategoria_id=p.subcategoria_id
                                    INNER JOIN categoria c ON s.categoria_id=c.categoria_id
                                        WHERE p.produto_deletado=0 {$condicao} ORDER BY produto_nome";




            $result = Sql::_fetchall($query);

            //$produtos.='<h1 class="border h1">'.$result[0]['categoria_nome'].' - '.$result[0]['subcategoria_nome'].'</h1>';

            foreach ($result as $prod)
            {
                $img = U::getImg('/imagens/produtos/'.$prod['produto_id'].'_1_1.'.$prod['produto_extensao1']);
                $link = '';

                $produtos['produtos'].='
                <div class="col-sm-4 product2">
                    <div class="row m0 thumbnail">
                        <div class="row m0 imgHov">
                            <a href="produto.php"><img src="images/product/pro2p/1.png" alt=""></a>
                            <div class="hovArea row m0">
                                <div class="links row m0">
                                </div>
                            </div>
                        </div>
                        <div class="row m0 productIntro">
                            <h5 class="heading"><a href="produto.php">Carros Planos</a></h5>
                        </div>
                    </div>
                </div>';
            }
            return $produtos.$pag['paginacao'];
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function getProdutosBusca()
    {
        try
        {
            if(! isset($_GET['busca'][3]))
            {
                U::gohome();
            }
            $_GET['busca'] = addslashes(strip_tags($_GET['busca']));


            $produtos = $imagem = '';

            $queryT = "SELECT p.*, s.subcategoria_nome, c.categoria_nome
                                        FROM produto p
                                            INNER JOIN subcategoria s ON s.subcategoria_id=p.subcategoria_id
                                                INNER JOIN categoria c ON s.categoria_id=c.categoria_id
                                                    WHERE p.produto_deletado=0
                                                        AND (p.produto_nome LIKE '%{$_GET['busca']}%' OR produto_descricao LIKE '%{$_GET['busca']}%' OR valor5 LIKE '%{$_GET['busca']}%' OR valor3 LIKE '%{$_GET['busca']}%') ORDER BY produto_nome";


            $pag = Paginacao::getPaginacao($queryT, $final=25,$paginacao='');

            if(! $pag)
            {
                return  '<h1 class="border h1">Ops, Não encontramos nenhum produto para sua busca</h1>';
            }



            $result = Sql::_fetchall($pag['query']);

            $produtos.='<h1 class="border h1">Resulados para sua busca '.$_GET['busca'].'</h1>';

            foreach ($result as $prod)
            {
                $imagem = U::getImg('/imagens/produtos/'.$prod['produto_id'].'_1_1.'.$prod['produto_extensao1']);

                $produtos.='
                <div class="row border-hover" onClick="location=\'produto.php?produto='.$prod['produto_id'].'\'">
                    <div class="col-md-12">
                        <div class="col-md-1"><img  src="assets/images/blank.gif" data-echo="'.$imagem.'" alt=""></div>
                        <div class="col-md-9">
                            <h5 class="name"><a href="produto-detalhado.php?id='.$prod['produto_id'].'">'.$prod['produto_nome'].'</a></h5>
                            '.$prod['valor5'].'
                            <br />'.$prod['valor3'].'
                            <br />'.$prod['valor5'].'
                            <br />'.$prod['valor4'].'
                        </div>
                        <div class="col-md-2">
                            <strong>R$ '.U::moeda($prod['valor2']).'</strong><br /><br />
                            <a href="produto.php?produto='.$prod['produto_id'].'"><button class="btn btn-primary" type="button">Ver Produto</button></a>
                        </div>
                    </div>
                </div>';
            }
            return $produtos.$pag['paginacao'];
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function produtoIndisponivel()
    {
        try
        {
            return 'Produto Não Disponível em Estoque <br /> <a href="contato.php">Me avise quando chegar</a>';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }



    static function getCepAutoLoad()
    {
        $retorno = '
        <input type="'.TYPE.'" name="update_sessao_cliente" value="x">';
        if(isset($_SESSION[X]['sessao_cliente']['clientes_cep'][8]))
        {
            $retorno.='<script>auto_cep(\''.$_SESSION[X]['sessao_cliente']['clientes_cep'].'\')</script>';
        }
        return $retorno;
    }
}