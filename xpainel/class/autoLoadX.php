<?
function __autoload($classe)
{
  try
  {
    if(! file_exists(ROOT."/xpainel/class/{$classe}.class.php"))
    {
      if(stristr($classe, 'pagseguro'))
      {
        require_once(ROOT.'/xpainel/gateway/pagseguro/lib/loader/PagSeguroAutoLoader.class.php');
        return;
      }

      throw new Exception("Arquivo para a classe <strong>$classe</strong> não existe.");
    }
    include_once ROOT."/xpainel/class/{$classe}.class.php";

    if(!class_exists($classe,false)){
            throw new Exception("Classe <strong>$classe</strong> não localizada em <strong>{$classe}.class.php</strong>");
        }

  }
  catch (Exception $e)
  {
            die('<div style="    left: 50%;
          margin: -200px 0 0 -250px;
          position: absolute;
          top: 50%;
          width: 500px;
          height: 400px;
          border: solid #1D2127 1px;
          background-color: #FFF;
          border-radius: 5px;
          padding: 10px;
          font-size: 20px;">
            <h1>Fatal Error :(</h1><hr />'.$e->getMessage().'
          </div>');


  }

}