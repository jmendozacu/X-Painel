<?
class Form
{
    static function required($min = 0)
    {
        try
        {
            $pattern = '';
            if($min > 0)
            {
                $pattern = ' pattern=".{0}|.{'.$min.',}" title="É necessário pelo menos '.$min.' caracteres." ';
            }
           return $pattern.' required ';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function autoCep()
    {
        try
        {
            if(isset($_SESSION[X]['sessao_cliente']['clientes_cep'][8]))
            {
                $GLOBALS['Xjs'][]="autoCep('{$_SESSION[X]['sessao_cliente']['clientes_cep']}');";
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function setAction($function = 'setContato', $method = 'post')
    {
        try
        {
            return ' method="'.$method.'" action="'.X::protocolo().'/xpainel/lib/ajax.php?function='.$function.'&nocache='.U::getToken(10).'" target="xgetDados" onsubmit="loadingX()" enctype="multipart/form-data"';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function setCaptcha($float = 'left', $cor = 'padrao', $size = 'padrao')
    {
        try
        {
            return Seguranca::getCaptcha($float, $cor, $size);
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function hr($titulo)
    {
        try
        {
            return '<input type="hidden" name="'.$titulo.':" value="SEPARADOR">';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function mascara($mascara)
    {
        try
        {
            return ' onkeydown="Mascara(this,'.$mascara.');" onkeypress="Mascara(this,'.$mascara.');" onkeyup="Mascara(this,'.$mascara.');" ';
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
	static function getInput($name, $parasAdicionais = '',  $required = true)
    {
        try
        {
            $value=' value="" ';
            if(isset($_SESSION[X]['sessao_cliente'][$name]))
            {
              $value = ' value="'.$_SESSION[X]['sessao_cliente'][$name].'" '; 
            }
            $required = $required ? ' required ' : '';
            switch($name)
            {
                case 'clientes_cep':
                    Form::autoCep();
                    return '  id="cep" '.self::mascara('CepEnd').'  name="clientes_cep" maxlength="9"  '.$value.$required.$parasAdicionais;
                break;

                case 'clientes_endereco':
                    return ' type="text" name="clientes_endereco" id="endereco" '.$value.$required.$parasAdicionais;
                break;
                case 'clientes_destinatario':
                    return ' type="text" name="clientes_destinatario" id="clientes_destinatario" '.$value.$required.$parasAdicionais;
                break;

                case 'clientes_numero':
                    return ' type="text" name="clientes_numero" id="numero" '.$value.$required.$parasAdicionais;
                break;

                case 'clientes_complemento':
                    return ' type="text" name="clientes_complemento" id="complemento"  '.$value.$required.$parasAdicionais;
                break;

                case 'clientes_bairro':
                    return ' type="text" name="clientes_bairro" id="bairro" '.$value.$required.$parasAdicionais;
                break;

                case 'clientes_estado':
                    return ' name="clientes_estado" id="clientes_estado"  '.$required.$parasAdicionais;
                break;

                case 'clientes_cidade':
                    return ' name="clientes_cidade" id="clientes_cidade" '.$required.$parasAdicionais;
                break;



                default:
                     return ' name="'.$name.'" id="'.$name.'" '.$value.$required.$parasAdicionais;
                //return '>'.x::alert($name.' não definido em '.__CLASS__.'>'.__FUNCTION__);
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
}