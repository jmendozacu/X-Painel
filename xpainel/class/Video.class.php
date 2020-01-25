<?
class Video
{
	static function getVideotecas()
	{
		try
		{
			$ret = '';

			$result = Sql::_fetchall('SELECT * FROM videoteca WHERE videoteca_deletada=0 AND videoteca_ativa=1 ORDER BY videoteca_ordem');
			foreach ($result as $res)
			{
				$ret.= '<li data-filter=".videoteca'.$res['videoteca_id'].'"><span>'.$res['videoteca_nome'].'</span></li>';
			}


			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}

	static function getVideos()
	{
		try
		{
			$ret = '';

			$result = Sql::_fetchall('SELECT * FROM video WHERE video_deletado=0 AND video_ativo=1');
			foreach ($result as $res)
			{
				$img = 'imagens/videos/'.$res['video_id'].'_1_1.'.$res['video_capa_extensao1'];
				$ret.= '
				<div class="col-md-4 col-sm-12 col-xs-12 itemOrganizado videoteca'.$res['videoteca_id'].' filter-item">
                    <div class="video-holder">
                        <img src="'.$img.'" alt="'.$res['video_nome'].'" title="'.$res['video_nome'].'">
                        <div class=" iconePlay">
                            <a class="html5lightbox" title="'.$res['video_nome'].'" href="'.$res['video_url'].'">
                                <img src="images/icon/play-btn.png" alt="Play Button">
                            </a>
                        </div>
                    </div>
                </div>';
			}


			return $ret;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}