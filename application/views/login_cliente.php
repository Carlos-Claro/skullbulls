
<div class="content">
<?php 
    if(isset($novo_acesso) && $novo_acesso) :
?>
    <h3>Olá <?php echo $dados->nome;?></h3>
    <p class="alert alert-success bg-default">Verificamos que este é o seu primeiro acesso, por favor, confirme os dados abaixo.</p>
    <p class="erro"></p>
    <form class="login-form" role="form" method="post" action="<?php echo isset($action) ? $action : '#' ?>">
        <div class="form-group">
            <input type="hidden" name="id_empresa" value="<?php echo $dados->id_empresa;?>">
            <label for="nome" class="control-label">Nome</label>
            <!--<input type="email" class="form-control" id="email" placeholder="Email" required name="email">-->
            <input type="text" class="form-control form-control-solid placeholder-no-fix" value="<?php echo (isset($dados->nome) ? $dados->nome: '');?>" id="nome" placeholder="Nome" name="nome">
        </div> 
        <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="text" class="form-control form-control-solid placeholder-no-fix" value="<?php echo (isset($dados->email) ? $dados->email : '');?>" id="email" placeholder="E-mail" name="email">
        </div>
        <div class="form-group">
            <label for="cargo" class="control-label">Profissão</label>
            <input type="text" class="form-control form-control-solid placeholder-no-fix" value="<?php echo (isset($dados->observacao) ? $dados->observacao : '');?>" id="cargo" placeholder="Cargo" name="observacao">
        </div>
        <div class="form-group">
            <label for="telefone" class="control-label">Telefone</label>
            <input type="text" class="form-control form-control-solid placeholder-no-fix" value="<?php echo (isset($dados->telefone) ? $dados->telefone : '');?>" id="telefone" placeholder="telefone" name="telefone">
        </div>
        <div class="form-group">
            <label for="senha" class="control-label">Senha</label>
            <input type="password" class="form-control form-control-solid placeholder-no-fix" id="senha" placeholder="Senha" name="senha">
        </div>
        <div class="form-group">
            <label for="c_senha" class="control-label">Confirmar senha</label>
            <input type="password" class="form-control form-control-solid placeholder-no-fix" id="c_senha" placeholder="Confirme sua senha" name="c_senha">
        </div>
        <!--<div class="g-recaptcha" data-theme="dark" data-sitekey="6LeqeBgUAAAAALhFQxQX7NhoPCxVwdTXTvTzD7ja"></div>-->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar dados</button>
        </div>
    </form>
<?php
    else:
?>
        <form class="login-form" role="form" method="post" action="<?php echo base_url().(isset($acao) ? $acao : 'site_sistemas/login?debug=1'); ?>">
            <div class="erro"><?php  echo validation_errors(); if ( isset($erro) ) : echo '<h3 class="form-title '.$erro['class'].'"><small>'.$erro['texto'].'</small></h3>'; endif; ?></div>
            <input type="hidden" value="<?php echo $empresa->id;?>" name="id_empresa">
            <div class="form-group">
                <label for="email" class="control-label visible-ie8 visible-ie9">Email</label>
                <!--<input type="email" class="form-control" id="email" placeholder="Email" required name="email">-->
                <input type="text" class="form-control form-control-solid placeholder-no-fix" id="email" placeholder="E-mail" name="email">
            </div> 
            <div class="form-group">
                <label for="senha" class="control-label visible-ie8 visible-ie9">Senha</label>
                <input type="password" class="form-control form-control-solid placeholder-no-fix" id="password" placeholder="Senha" name="senha">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary login-cliente">Login</button>
                <?php if ( ! isset($nao_esquece_senha)):
                    ?>
                    <a data-toggle="modal" href="#esqueciModal" class="btn btn-default">Esqueceu sua Senha?</a>
                    <?php
                endif;
                ?>
            </div>
        </form>
<?php endif;?>
</div>
<div class="modal fade" id="esqueciModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Recuperar Senha!</h4>
            </div>
            <div class="modal-body">
                <label class="control-label" for="mail">Digite seu E-mail</label>
                <div class="input-group">
                    <input type="email" id="mail" class="form-control">
                    <span class="input-group-addon btn btn-warning verificar">Verificar</span>
                </div>
                <div class="verificado"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->