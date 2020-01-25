<div class="modal-header">
  <button type="button"  title="Fechar" class="close" data-dismiss="modal">&times;</button>
  <strong class="modal-title">Orçamento</strong>
</div>
<div class="modal-body" id="modalX">
  <form <?=Form::setAction()?>class="form-horizontal" role="form">
    <input type="hidden" name="assunto" value="Pedido de Orçamento" />
    <div class="row">
      <div class="col-md-9">
        <div class="form-group">
          <label for="Comemoração">Comemoração</label>
          <select class="form-control" name="Comemoração" id="Comemoração" placeholder="O que deseja comemorar ?" required >
            <option value="">Escolha</option>
            <option value="Making OFF">Making OFF</option>
            <option value="Decorações">Decorações</option>
            <option value="Buffet">Buffet</option>
            <option value="Aniversários">Aniversários</option>
            <option value="Debutante">Debutante</option>
            <option value="Confraternização">Confraternização</option>
            <option value="Casamentos">Casamentos</option>
            <option value="Outros">Outros</option>
          </select>
        </div>
      </div>
      <div class="col-md-2 pull-right">
        <div class="form-group">
          <label for="Convidados">Convidados</label>
          <input type="number" min="10" class="form-control" name="Convidados" id="Convidados" placeholder="" required />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="nome>">Seu Nome</label>
          <input type="text" class="form-control" name="nome" id="nome" placeholder="Seu Nome" required />
        </div>
      </div>
      <div class="col-md-5 pull-right">
        <div class="form-group">
          <label for="data">Data Pretendida</label>
          <input type="date" class="form-control"  name="data" id="data" placeholder="Melhor Data" required />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="telefone">Seu Telefone</label>
          <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Seu Telefone" required />
        </div>
      </div>
      <div class="col-md-5 pull-right">
        <div class="form-group">
          <label for="email">Seu E-mail</label>
          <input type="email" class="form-control" name="email"  id="email" placeholder="Seu Email" rewuired />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="Observações">Observações</label>
          <input type="text" class="form-control" name="Observações" id="Observações" placeholder="Observações" />
        </div>
      </div>
    </div>
    <div class="row">
      <button type="submit" class="btn btn-primary btn-block">Solicitar Orçamento</button>
    </div>
    <?=Form::setCaptcha()?>
  </form>
</div>