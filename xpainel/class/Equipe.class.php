<?
class Equipe
{
	static function getEquipe()
	{
		try
		{
			$equipe = '';
			$result = Sql::_fetchAll('SELECT * FROM equipe WHERE equipe_deletado=0 AND equipe_ativo=1');
			$class = 'active';
			foreach($result as $res)
			{
				$img = U::getImg('imagens/equipe/'.$res['equipe_id'].'_1_1.'.$res['equipe_extensao1']);
				$equipe.=				'
				<div class=" col-xs-12 col-md-3 carousel-item '.$class.'">
					<div class="ff_team_box">
						<div class="ff_team_img">
							<img src="'.$img.'" alt="team" title="team" class="img-fluid">
						</div>
						<div class="ff_team_text">
							<h3>'.$re´['equipe_nome'].'</h3>
							<p>'.$res['equipe_campo_adicional1'].'</p>
							<ul>
								'.Layout::display('<li><a href="{XlayoutX}"><i class="fa fa-facebook"></i></a></li>',$res['equipe_campo_adicional2']).'
								'.Layout::display('<li><a href="{XlayoutX}"><i class="fa fa-instagram"></i></a></li>',$res['equipe_campo_adicional3']).'
							</ul>
						</div>
					</div>
				</div>';
	            $class = '';
			}
			return $equipe;
		}
		catch( Exception $e )
		{
			X::sendErrors($e->getMessage(), __CLASS__.'>'.__FUNCTION__.'>'.__LINE__);
		}
	}
}