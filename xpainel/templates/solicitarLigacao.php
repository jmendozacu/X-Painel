<div class="modal-header">
  <button type="button"  title="Fechar" class="close" data-dismiss="modal">&times;</button>
  <strong class="modal-title">Nós ligamos pra você :)</strong>
</div>
<div class="modal-body" id="modalX">
  <form <?=Form::setAction()?>class="form-horizontal" role="form">
    <input type="hidden" name="assunto" value="Solicitação de Ligação" />
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="nome>">Seu Nome</label>
          <input type="text" class="form-control" name="nome" id="nome" placeholder="Seu Nome" required />
        </div>
      </div>
      <div class="col-md-5 pull-right">
        <div class="form-group">
          <label for="data">Melhor Dia</label>
          <input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="data" id="data" placeholder="Melhor Data">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="telefone">Seu Telefone</label>
          <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Seu Telefone">
        </div>
      </div>
      <div class="col-md-5 pull-right">
        <div class="form-group">
          <label for="horario">Melhor Horário</label>
          <input type="time" class="form-control" name="horario" value="<?=date('G:i')?>" id="horario" placeholder="Melhor Data">
        </div>
      </div>
    </div>
    <div class="row">
      <button type="submit" class="btn btn-primary btn-block">Solicitar Ligação</button>
    </div>
    <?=Form::setCaptcha()?>
  </form>
</div>