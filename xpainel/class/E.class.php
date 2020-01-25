<?
class E
{
	static function email($destino,$nome,$titulo, $mensagem, $anexo=false)
	{
		try
		{
			$dados=X::getParametros('email');
			$corpo=str_replace(
								array('{titulo}','{mensagem}','{logomarca}', '{HTTP}'),
								array($titulo,$mensagem,$dados['logomarca'], HTTP),
								$dados['htmlEmail']
							   );

			if(defined('XSETOR'))
			{
				$dados = self::setDepartamento($dados);
			}

			require_once(ROOT."/xpainel/vendor/PHPMailer/class.phpmailer.php");
			$mail = new PHPMailer();
			if(MODE_DEVELOPER)
			{
				$mail->SMTPDebug  = 2;
			}
			$mail->IsSMTP();
			$mail->CharSet = CHARSET;
			$mail->Port = $dados['SmtpPorta'];
			$mail->Host = $dados['ServerSmtp'];
			$mail->SMTPAuth = true;
			$mail->Username = $dados['UserName'];
			$mail->Password = $dados['ServerEmailSenha']; // senha
			$mail->From = $dados['EmailFrom'];
			$mail->FromName = $dados['FromName'];
			$mail->AddAddress($destino,$nome);
			$mail->AddBcc($dados['EmailFrom'],$nome);


			if(isset($dados['email_bcc']))
			{
				$copias=$dados['email_bcc'];
				if ($copias && $copias != '')
				{
					 $copias= explode(';',$copias);
					 if (is_array($copias))
					 {
					 	foreach ($copias as $copia)
					 	{
							$mail->AddBcc($copia,$nome);
					 	}
					 }
					 else
					 {
					 	$mail->AddBcc($copias,$nome);
					 }
				}
			}
			$mail->WordWrap = 50;

			if($anexo)
			{
				if (is_array($anexo))
				{
					foreach($anexo as $file)
					{
						$mail->AddAttachment($file);
					}
				}
				else
				{
					$mail->AddAttachment($anexo);
				}
			}
			$mail->IsHTML(true);
			$mail->AddReplyTo($destino,$nome);
			$mail->Subject = $titulo;
			$mail->Body = $corpo;

			if(! $mail->Send())
			{
				if(DEBUG)
				{
					echo $mail->ErrorInfo;
				}
				return false;
			}

			return $corpo;

		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function setDepartamento($dados)
	{
		try
		{
			$sql = "SELECT * FROM  dept_email WHERE dept_email_deletado=0 AND dept_email_ativo=1 AND dept_email_id=".XSETOR;
			$result = Sql::_fetch($sql);

			if($result)
			{
				$dados['EmailFrom'] = $result['dept_email_email'];
				$dados['FromName'].= ' '.$result['dept_email_nome'];

			}
			return U::clearStr($dados);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function setSetor($id)
	{
		try
		{
			return '<input type="'.TYPE.'" name="xsetor" value="'.$id.'" >';
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getDado($id, $coluna = false)
	{
		try
		{
			$dados = Sql::_fetch("SELECT * FROM dept_email WHERE dept_email_id =".$id);
			if(! $dados)
			{
				return false;
			}
			return ($coluna && isset($dados[$coluna])) ? $dados[$coluna] : $dados;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getDepartamento()
	{
		try
		{
			if(isset($_POST['xsetor']))
			{
				if($_POST['xsetor'] > 0)
				{
					if(Sql::checaTabelaExists('dept_email'))
					{
						define('XSETOR', (int)$_POST['xsetor']);
					}
				}

				unset($_POST['xsetor']);
			}
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function departamento($departamento = false)
	{
		try
		{
			if($departamento)
			{
				return '<input type="'.TYPE.'" name="xsetor" value="'.$departamento.'">';
			}

			return;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
	static function getDepartamentos($departamento)
	{
		try
		{

			$ret = '<option value="">Selecione o departamento</option>';
			echo $sql = "SELECT * FROM  dept_email WHERE dept_email_deletado=0 AND dept_email_ativo=1  AND checkbox{$departamento} = 1 ORDER BY dept_email_ordem";
			$result = Sql::_fetchAll($sql);
			if(! $result)
			{
				$dados=X::getParametros('email');
				$ret.='<option value="0">Geral</option>';
			}

			foreach($result as $res)
			{
				$ret.='<option value="'.$res['dept_email_id'].'">'.$res['dept_email_setor'].'</option>';
			}

			return U::clearStr($ret);
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}