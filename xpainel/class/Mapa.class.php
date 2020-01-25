<?
class Mapa
{

	static function getMapa($id = 1, $classId='mapa',$script=true, $balao = true)
	{
		try
		{
			
			$mapa = '';
			$result = Sql::_fetchAll('SELECT * FROM mapas WHERE mapa_id = '.$id);
			$locations = '';
			foreach($result as $res)
			{
				$titulo = $res['mapa_titulo'] != '' ? ",'<h4>{$res['mapa_titulo']}</h4><span>{$res['mapa_subtitulo']}</span>'" : ',false';
				$icone = 'false';
				if($res['mapa_extensao1'] != '' && file_exists(ROOT."/imagens/mapas/{$res['mapa_id']}_1_1.{$res['mapa_extensao1']}"))
				{
					$icone = "'".HTTP."/imagens/mapas/{$res['mapa_id']}_1_1.{$res['mapa_extensao1']}'";
				}
				$animacao = $res['mapa_animacao'] == 1 ? 'true' : 'false';

				$descricao = $balao
				? U::clearStr('<div class="mapxdescription"><h4>'.$res['mapa_titulo'].'</h4><strong>'.$res['mapa_subtitulo'].'</strong><span>'.$res['mapa_descricao'].'</span></div>')
				: '';

				$locations.="['{$descricao}', {$res['txtlatitude']}, {$res['txtlongitude']}, {$icone}, {$animacao}, '{$res['mapa_zoom']}'],";

				//$GLOBALS['Xjs'][]='initializeMap(\''.$classId.'\','.$res['txtlatitude'].','.$res['txtlongitude'].$titulo.$icone.$animacao.','.$res['mapa_zoom'].');';
			}

			$locations = "
			[
			 {$locations}
			]";

			$mapa.= '
				<div class="'.$classId.'" id="'.$classId.'">'.U::setTable('<img src="'.X::protocolo().'/xpainel/imagens/loading.gif" />').'</div>';
				$GLOBALS['Xjs'][]='initializeMap(\''.$classId.'\','.$locations.');';

			if($script)
			{
				$mapa='
				<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCFoT4YF8Vs0_UyVhE64Ll7r9Ke6cmCkgU"></script>
				<script type="text/javascript" src="'.X::protocolo().'/xpainel/js/vendor/googlemapv3/mapa.js"></script>
				'
				.$mapa;
			}
			return $mapa;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getMapas($classId='mapa',$script=true, $balao = true)
	{
		try
		{
			$mapa = '';
			$result = Sql::_fetchAll('SELECT * FROM mapas');
			$locations = '';
			foreach($result as $res)
			{
				$titulo = $res['mapa_titulo'] != '' ? ",'<h4>{$res['mapa_titulo']}</h4><span>{$res['mapa_subtitulo']}</span>'" : ',false';
				$icone = 'false';
				if($res['mapa_extensao1'] != '' && file_exists(ROOT."/imagens/mapas/{$res['mapa_id']}_1_1.{$res['mapa_extensao1']}"))
				{
					$icone = "'".HTTP."/imagens/mapas/{$res['mapa_id']}_1_1.{$res['mapa_extensao1']}'";
				}
				$animacao = $res['mapa_animacao'] == 1 ? 'true' : 'false';

				$descricao = $balao
				? U::clearStr('<div class="mapxdescription"><h4>'.$res['mapa_titulo'].'</h4><strong>'.$res['mapa_subtitulo'].'</strong><span>'.$res['mapa_descricao'].'</span></div>')
				: '';

				$locations.="['{$descricao}', {$res['txtlatitude']}, {$res['txtlongitude']}, {$icone}, {$animacao}, '{$res['mapa_zoom']}'],";

				//$GLOBALS['Xjs'][]='initializeMap(\''.$classId.'\','.$res['txtlatitude'].','.$res['txtlongitude'].$titulo.$icone.$animacao.','.$res['mapa_zoom'].');';
			}

			$locations = "
			[
			 {$locations}
			]";

			$mapa.= '
				<div class="'.$classId.'" id="'.$classId.'">'.U::setTable('<img src="'.X::protocolo().'/xpainel/imagens/loading.gif" />').'</div>';
				$GLOBALS['Xjs'][]='initializeMap(\''.$classId.'\','.$locations.');';

			if($script)
			{
				$mapa='
				<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCFoT4YF8Vs0_UyVhE64Ll7r9Ke6cmCkgU"></script>
				<script type="text/javascript" src="'.X::protocolo().'/xpainel/js/vendor/googlemapv3/mapa.js"></script>
				'
				.$mapa;
			}
			return $mapa;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getDado($coluna = false, $id=1)
	{
		try
		{
			$result = Sql::_fetch('SELECT * FROM mapas WHERE mapa_id='.$id);

			$result['icone'] = U::getImg("imagens/mapas/{$id}_1_1.{$result['mapa_extensao1']}");

			$result['mapa_animacao'] = $result['mapa_animacao'] == 1 ? 'true' : 'false';

			if($coluna)
			{
				return $result[$coluna];
			}
			return $result;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

}