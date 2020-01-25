<?
class Seguranca
{
    static function getCaptchaV3($float = 'left', $cor = 'padrao', $size = 'padrao')
    {
        try
        {
            $cor = $cor == 'padrao' ? 'light' : 'dark';
            $size = $size == 'padrao' ? 'normal' : 'compact';

            $local = '<input type="hidden" name="xlocal" value="'.$_SERVER['PHP_SELF'].'">';

            return'
                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                <script src="https://www.google.com/recaptcha/api.js?render='.CAPTCHA_SITE_KEY.'"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute(\''.CAPTCHA_SITE_KEY.'\', {action: \'login\'}).then(function(token) {
                           document.getElementById(\'g-recaptcha-response\').value=token;
                        });
                    });
                </script>'.$local;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
    static function getCaptcha($float = 'left', $cor = 'padrao', $size = 'padrao')
    {
        try
        {
            $cor = $cor == 'padrao' ? 'light' : 'dark';
            $size = $size == 'padrao' ? 'normal' : 'compact';

            $local = '<input type="hidden" name="xlocal" value="'.$_SERVER['PHP_SELF'].'">';

            return'
            <div id="recaptchaX">
                <script src="//www.google.com/recaptcha/api.js" async defer></script>
                <div class="g-recaptcha" data-theme="'.$cor.'" data-size="'.$size.'" data-sitekey="'.CAPTCHA_SITE_KEY.'" style="float: right;"></div>
            </div>'.$local;
        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }

    static function checkCaptcha($retornoBoleano = false)
    {
        try
        {
            if(DEBUG)
            {
                return true;
            }
            $retorno = false;

            if(isset($_POST['g-recaptcha-response']))
            {

                $recaptcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".CAPTCHA_SITE_SECRET_KEY."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']));

                $retorno = $recaptcha->success;

                unset($_POST['g-recaptcha-response']);
            }

            if($retornoBoleano)
            {
                return $retorno;
            }

            if(! $retorno)
            {
                die(X::alert('Robôs são bloqueados. \n Prove que você não é um robô.'));
            }

        }
        catch( Exception $e )
        {
            X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
        }
    }
}