<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <h3> <i class="fa fa-user"></i> Raça</h3>
                </div>
            </div>
            <div class="portlet-body">
                <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <div class="alert">
                    <?php echo validation_errors(); ?>
                </div>
                <form class="form-horizontal" action="<?php echo $action;?>" method="post">
                    <div style="margin-top: 50px;" >
                        <div class="form-group obrigatorio">
                            <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                                Raça
                            </label>
                            <div class=" col-lg-8">
                                <input type="text" required="required" placeholder="Raça" class="form-control" id="raca" name="titulo" value="<?php echo set_value('titulo',(isset($item->titulo) ? $item->titulo : ''));?>">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success salvar_1" type="submit">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
