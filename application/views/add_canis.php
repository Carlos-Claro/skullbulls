<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <h3> <i class="fa fa-user"></i> Canil</h3>
                </div>
            </div>
            <div class="portlet-body">
                <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <div class="alert">
                    <?php echo validation_errors(); ?>
                </div>
                <form class="form-horizontal" action="<?php echo $action;?>" method="post">
                    <div style="" >
                        <div class="form-group obrigatorio">
                            <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                                Nome canil
                            </label>
                            <div class=" col-lg-8">
                                <input type="text" required="required" placeholder="Nome Canil" class="form-control" id="nome" name="nome" value="<?php echo set_value('nome',(isset($item->nome) ? $item->nome : ''));?>">
                            </div>
                        </div>
                        <div class="form-group obrigatorio">
                            <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                                Nome Proprietario
                            </label>
                            <div class=" col-lg-8">
                                <input type="text" required="required" placeholder="Nome Proprietario" class="form-control" id="proprietario" name="proprietario" value="<?php echo set_value('proprietario',(isset($item->proprietario) ? $item->proprietario : ''));?>">
                            </div>
                        </div>
                        <div class="form-group obrigatorio">
                            <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                                Telefone
                            </label>
                            <div class=" col-lg-8">
                                <input type="text"  placeholder="Telefone" class="form-control telefone" id="telefone" name="telefone" value="<?php echo set_value('telefone',(isset($item->telefone) ? $item->telefone : ''));?>">
                            </div>
                        </div>
                        <div class="form-group obrigatorio">
                            <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                                Email
                            </label>
                            <div class=" col-lg-8">
                                <input type="text" placeholder="Email" class="form-control" id="email" name="email" value="<?php echo set_value('email',(isset($item->email) ? $item->email : ''));?>">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success salvar_1" type="submit">Salvar</button>
                            <a href="<?php echo base_url();?>/canis/adicionar" class="btn btn-warning" >Adicionar novo</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
