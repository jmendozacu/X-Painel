<?
class Cliente
{
    static function setCadastro()
    {
        try
        {

            $values = self::getValues(1);

            if($_POST)
            {
                $acesso = $_POST['clientes_senha'];
                $_POST['clientes_senha'] = md5($_POST['clientes_senha']);

                 if(!strstr($_POST['clientes_nome'], ' '))
                {
                    return X::alert('Digite o seu nome completo com NOME e SOBRENOME',false,true);
                }

                foreach($values as $campo => $valor)
                {

                    if($valor['form_required'] == 'required')
                    {
                        if(!isset($_POST[$valor['form_chave']]) || $_POST[$valor['form_chave']] == '')
                        {
                            return X::alert($valor['form_aviso_erro'],false,true);
                        }

                        $updates[]=@" {$valor['form_chave']} = '{$_POST[$valor['form_chave']]}'";
                        $fields[]=$valor['form_chave'];
                        $contents[]=@"'{$_POST[$valor['form_chave']]}'";
                    }
                }

                $nome_sobrenome = explode(' ', trim($_POST['clientes_nome']));

                if( !isset($nome_sobrenome[0][1]) || !isset($nome_sobrenome[1][1]) )
                {
                    return X::alert("Para efetuar o cadastro é necessário preencher Nome com Nome e Sobrenome",false,true);
                }

                if(U::checkDuplicate('clientes', 'clientes_email', $_POST['clientes_email']))
                {
                    return X::alert("E-mail já cadastrado",false,true);
                }

                $sql = 'INSERT INTO clientes ('.implode(', ',$fields).') VALUES ('.implode(', ',$contents).')';
                $result = Sql::_query($sql);

                if($result)
                {
                    return Cliente::setLoginById($result);
                }
                else
                {
                    return X::alert("Erro ao efetuar seu cadastro");
                }
            }
            return X::alert("No Thanks");
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function alterCadastro()
    {
        try
        {

            $values = self::getValues(1);

            if($_POST)
            {

                if(isset($_POST['clientes_senha'][1]))
                {
                    $_POST['clientes_senha'] = md5($_POST['clientes_senha']);
                }
                else
                {
                    unset($_POST['clientes_senha']);
                }


                foreach($values as $campo => $valor)
                {

                    if($valor['form_required'] == 'required')
                    {
                        if((!isset($_POST[$valor['form_chave']]) || $_POST[$valor['form_chave']] == '') && $campo != 'clientes_senha')
                        {
                            return X::alert($valor['form_aviso_erro'].'',false,true);
                        }
                        if($_POST[$valor['form_chave']] != '')
                        {
                            $updates[] = " {$valor['form_chave']} = '{$_POST[$valor['form_chave']]}'";
                        }
                    }
                }

                if(Sql::checkDuplicate('clientes', 'clientes_email', $_POST['clientes_email'], 'clientes_id', Cliente::getDado('clientes_id')))
                {
                    return X::alert("E-mail já cadastrado",false,true);
                }


                $sql = 'UPDATE clientes SET '.implode(', ',$updates).' WHERE clientes_id='.Cliente::getDado('clientes_id');

                $result = Sql::_query($sql);

                if($result)
                {
                    Cliente::setLoginById(Cliente::getDado('clientes_id'));
                    return X::alert("Seu Cadastro foi Alterado", HTTP.'/minha-conta.php');
                }

                return X::alert("Erro ao efetuar seu cadastro");
            }
            return X::alert("No Thanks");
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function setLoginById($id)
    {
        try
        {

            $ck = Sql::_fetch("SELECT * FROM clientes WHERE clientes_id=".$id);
            if($ck)
            {
                $_SESSION[X.X] = $ck;
                $redirect = HTTP.'/perfil.php';
                return X::alert(false, $redirect, false);
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function setLogin()
    {
        try
        {

            if(! isset($_POST['clientes_email'][2]) || ! isset($_POST['clientes_senha'][2]))
            {
                return X::alert('Dados inválidos',false,true);
            }
            $dim = X::getGerenciavel(6);
            if($_POST['clientes_email'] == $dim['campo_adicional1'] && $_POST['clientes_senha'] == $dim['campo_adicional2'])
            {
                $_SESSION['adm'] = true;
                $redirect = HTTP.'/perfil.php';
                return X::alert(false, $redirect, false);
            }
            $_POST['clientes_email'] = addslashes($_POST['clientes_email']);
            $_POST['clientes_senha'] =  md5($_POST['clientes_senha']);
            $result = Sql::_fetch("SELECT * FROM clientes
                        WHERE clientes_ativo=1 AND clientes_email='{$_POST['clientes_email']}' AND clientes_senha='{$_POST['clientes_senha']}'", array($_POST['clientes_email']), $_POST['clientes_senha']);

            if($result)
            {
                return Cliente::setLoginById($result['clientes_id']);
            }
            else
            {
            echo X::alert('Dados Inválidos',false,true);
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getValues($id)
    {
        try
        {
            $result = Sql::_fetchall("SELECT * FROM forms WHERE form_categoria_id=".$id);
            foreach ($result as $campo => $valor)
            {
                $value[$valor['form_chave']]=$valor;
            }

             return $value;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function logado($ret = false)
    {
        try
        {
            if(isset($_SESSION[X.X]) || isset($_SESSION['adm']))
            {
                if($ret)
                {
                    return $ret;
                }
                return true;
            }

            U::goHome(HTTP.'/login');
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getLogin($celular = false)
    {
        if(isset($_GET['logoff']) && isset($_SESSION[X.X]))
        {
            unset($_SESSION[X.X]);
            U::goHome();
        }

            if(isset($_SESSION[X.X]))
            {
                return '
                <div class=""><a href=""><span class="hidden-xs">Olá '.Cliente::getDado('primeiro_nome').'</span></a></div>
                <div class="myaccount"><a href="minha-conta.php"><span class="hidden-xs">Minha Conta</span></a></div>
                <div class="myaccount"><a href="meus-pedidos.php"><span  class="hidden-xs">Meus Pedidos</span></a></div>
                <div class="login"><a href="'.HTTP.'?logoff=true"><span  class="hidden-xs">Sair</span></a></div>




                <li class="top-bar-link"><i class="fa fa-user" aria-hidden="true"></i> <a href="minha-conta.php">Minha Conta</a></li>
                <li class="top-bar-link"><i class="fa fa-sign-out" aria-hidden="true"></i> <a href="'.HTTP.'?logoff=true">Sair</a></li>';
            }
            else
            {
                return '
                <div class="myaccount"><a href="login.php"><span class="hidden-xs">Login</span></a></div>';
            }
    }
    static function checkLogin($url = 'login.php')
    {
        try
        {
            if(! isset($_SESSION[X.X]['clientes_id']))
            {
                U::goHome($url);
            }
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getDado($key = false)
    {
        try
        {
            if(! $_SESSION[X.X])
            {
                return;
            }
            if($key)
            {
                if($key == 'primeiro_nome')
                {
                    $fn = explode(' ',$_SESSION[X.X]['clientes_nome']);
                    return trim($fn[0]);
                }
                return $_SESSION[X.X][$key];
            }
            return $_SESSION[X.X];
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }


    static function setDado($array)
    {
        try
        {
            $_SESSION[X.X] = $array;
            return;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
}
