<div class="menu-toggler sidebar-toggler">
</div>
<div class="logo">
    <!--<img src="<?php echo base_url();?>images/logo_pow.png">-->
</div>
<div class="content">

        <h3 class="form-title"> Acessar o painel </h3>
        <?php if ( isset($erro) ) : echo '<p class="'.$erro['class'].'">'.$erro['texto'].'</p>'; endif; ?>
        <form class="login-form" role="form" method="post" action="<?php echo isset($action) ? $action : '#' ?>">
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
                <button type="submit" class="btn btn-primary">Login</button>
                <a data-toggle="modal" href="#esqueciModal" class="btn btn-default">Esqueceu sua Senha?</a>
            </div>
        </form>
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
