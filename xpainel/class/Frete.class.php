<?php
class Frete
{
  static function getTransportadoras()
  {
    try
    {
        unset($_SESSION[X]['fretes']);
        return Sql::_fetchAll("SELECT * FROM transportadora WHERE transportadora_ativa = 1");
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function getFretes($cep)
  {
    try
    {
        $transportadoras = Frete::getTransportadoras();

        $lista ='';
        foreach($transportadoras as $t)
        {

            $funcao = $t['transportadora_parametro'];
           //if(function_exists('Frete::'.$funcao))
            {
              $lista.=call_user_func('Frete::'.$funcao, $cep, $t);
            }
        }

        return self::tabela_frete();
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function correios($cep, $t)
  {

     $cep_destino = eregi_replace("([^0-9])",'',$cep_destino);
     $rotulo = array();
     $servicos = explode(',',$t['transportadora_servicos']);
     foreach($servicos as $servico)
     {
        $s = explode('#', $servico);
        $rotulo[$s[0]] = $s[1];
     }

     $webservice = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';
     $codigo_correios = implode(',',array_keys($rotulo));
     $pesominimo = 0.300;
     $parms = new stdClass;
     $parms->nCdServico = $codigo_correios;//41106,40010,40290,40215 ->  PAC, SEDEX E ESEDEX (TODOS COM CONTRATO) - se vc precisar de mais tipos adicione aqui
     $parms->nCdEmpresa = $t['transportadora_usuario'];// <- LOGIN DO CADASTRO NO CORREIOS (OPCIONAL)
     $parms->sDsSenha = $t['transportadora_pwd'];// <- SENHA DO CADASTRO NO CORREIOS (OPCIONAL)
     $parms->StrRetorno = 'xml';
     // DADOS DINAMICOS
     $parms->sCepDestino = $cep;// CEP CLIENTE
     $parms->sCepOrigem = $t['transportadora_cep_origem'];// CEP DA LOJA (BD)
     $parms->nVlPeso = $_SESSION[X]['carrinho']['peso_total'] >= $pesominimo ? $_SESSION[X]['carrinho']['peso_total'] : $pesominimo;
     // VALORES MINIMOS DO PAC (SE VC PRECISAR ESPECIFICAR OUTROS FAÇA ISSO AQUI)
     $parms->nVlComprimento = '16';
     $parms->nVlDiametro = 0;
     $parms->nVlAltura = 2;
     $parms->nVlLargura = 11;
     // OUTROS OBRIGATORIOS (MESMO VAZIO)
     $parms->nCdFormato = 1;
     $parms->sCdMaoPropria = 'N';
     $parms->nVlValorDeclarado = 0;
     $parms->sCdAvisoRecebimento = 'N';
     // Inicializa o cliente SOAP
     $soap = @new SoapClient($webservice, array(
             'trace' => true,
             'exceptions' => true,
             'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
             'connection_timeout' => 1000
     ));
     // Resgata o valor calculado
     $resposta = $soap->CalcPrecoPrazo($parms);
     $objeto = $resposta->CalcPrecoPrazoResult->Servicos->cServico;
     $array = array();
     $tabela = '';
     $valor = 0;

     foreach($objeto as $obj)
     {

      $tipo = isset($rotulo[$obj->Codigo]) ? strtolower($rotulo[$obj->Codigo]) : '';
      if($tipo != '')
      {

        $retorno[$tipo] = array('tipo'=>$tipo,'valor'=>str_replace(',','.',$obj->Valor),'prazo'=>$obj->PrazoEntrega,'erro'=>$obj->Erro,'msg'=>$obj->MsgErro);

        if($retorno[$tipo]['erro'] == 0)
        {
          self::addFrete($retorno[$tipo]['tipo'], $retorno[$tipo]['valor'], $rotulo[$obj->Codigo], ($retorno[$tipo]['prazo']+$t['transportadora_adicional1']).' dias para entrega.');
        }
      }
    }
      //self::addFrete('retira', 0, 'Retirar no Local', 'Disponível até 30 dias para retirada');
      //self::impressoModico($peso);
  }
  static function checkEntrega($url = 'entrega.php')
  {
      try
      {
          if(! isset($_SESSION[X]['carrinho']['valor_frete']))
          {
              U::goHome($url);
          }
      }
      catch( Exception $e )
      {
          X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
      }
  }
  static function retira($cep, $t)
  {
    try
    {
       return  self::addFrete('retira', U::moeda($t['transportadora_adicional2']), $t['transportadora_adicional1'], $t['transportadora_adicional3']);
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function personalizada1($cep, $t)
  {
    try
    {
       return  self::addFrete(__FUNCTION__, U::moeda($t['transportadora_adicional2']), $t['transportadora_adicional1'], $t['transportadora_adicional3']);
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function personalizada2($cep, $t)
  {
    try
    {
       return  self::addFrete(__FUNCTION__, U::moeda($t['transportadora_adicional2']), $t['transportadora_adicional1'], $t['transportadora_adicional3']);
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function tabela_frete()
  {
    try
    {
        $tabela ='';
        foreach($_SESSION[X]['fretes'] as $tipo => $valor)
        {
          $ck = ($_SESSION[X]['sessao_cliente']['frete_escolhido'] == $tipo || count($_SESSION[X]['fretes']) == 1)? ' checked ' : '';
          $preco = $valor['valor'] > 0 ? 'R$ '.$valor['valor'] : 'GRÁTIS';
          $tabela.='
          <label class="xlabel">
             <p class="b-remaining">
                <input class="b-form-radio b-form-radio--big-indent" type="radio" name="frete_escolhido" value="'.$tipo.'" '.$ck.' required />
                '.$preco.' - '.$valor['nome'].' ('.$valor['prazo'].')
             </p>
          </label>';
        }




        return $tabela;
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function addFrete($tipo, $valor, $nome, $prazo)
  {
    try
    {
       $_SESSION[X]['fretes'][$tipo] = array('valor' => $valor, 'nome' => $nome, 'prazo' => $prazo, 'tipo' => $tipo);
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
  static function getFrete($valor = false)
  {
    try
    {
      if($valor)
      {
        return  $_SESSION[X]['fretes'][$_SESSION[X]['sessao_cliente']['frete_escolhido']][$valor];
      }
        return  $_SESSION[X]['fretes'][$_SESSION[X]['sessao_cliente']['frete_escolhido']];
    }
    catch( Exception $e )
    {
      X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
    }
  }
}
