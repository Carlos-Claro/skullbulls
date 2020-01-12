<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <h3> <i class="fa fa-user"></i> Cores</h3>
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
                                Titulo
                            </label>
                            <div class=" col-lg-8">
                                <input type="text" required="required" placeholder="Nome Cores" class="form-control" id="titulo" name="titulo" value="<?php echo set_value('titulo',(isset($item->titulo) ? $item->titulo : ''));?>">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success salvar_1" type="submit">Salvar</button>
                            <a href="<?php echo base_url();?>/cores/adicionar" class="btn btn-warning" >Adicionar novo</a>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
