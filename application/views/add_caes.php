<style>
    .form-horizontal .form-group {
    margin-left: -15px;
    margin-right: 0;
}
.input-group-btn:last-child > .btn, .input-group-btn:last-child > .btn-group {
    z-index: 2;
    margin-left: 0px;
    padding: 10px 10px;
    border: 1px solid #c2cad8;
}

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light portlet-fit bordered">
            <div class="alert">
            <?php if ( isset($erro) && $erro ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>	
                <?php echo validation_errors(); ?>
            </div>
            <div class="portlet-title">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <h3 class=""> <i class="fa fa-cube"></i> Cão</h3>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                
                <form class="form-horizontal" action="<?php echo $action;?>" method="post" >
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center status-arquivo">
                        <input type="hidden" class="id" value="<?php echo $item->id;?>">
                        <input type="hidden" name="image" class="image" value="<?php echo set_value('image', isset($item->image) ? $item->image : '');?>">
                        <div class="row">
                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <div style="margin-top: 20px;" class="upload"></div>
                               <div class="upload_status"></div>
                             </div>
                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco_preview">
                                 <?php if (isset($item->image) && !empty($item->image)):
                                   ?>
                                 <img src="<?php echo base_url().'arquivos/caes/'.$item->id.'/'.$item->image;?>" class="img-responsive">
                                    <?php  
                                 endif;
                                 ?>
                                <div class="status"></div>
                            </div>
                         </div>
                    </div>
                    <div class="form-group obrigatorio">
                        <label for="nome" class="col-lg-2 col-xs-12 control-label ">
                            Nome
                        </label>
                        <div class=" col-lg-8">
                            <input type="text" placeholder="Nome" class="form-control" id="nome" value="<?php echo set_value('nome', isset($item->nome ) ? $item->nome : '');?>" name="nome">
                            <div class="nome_message"></div>
                        </div>
                    </div>
                    <div class="form-group obrigatorio">
                        <label for="data_nascimento" class=" control-label">
                            Data Nascimento
                        </label>
                        <div class="input-group date date-time-picker" id="data_nascimento">
                            <input name="data_nascimento" type="text" data-date-format="DD/MM/YYYY HH:mm" class="form-control" id="data_nascimento" placeholder="Data Nascimento"  value="<?php echo set_value('data_nascimento', isset($item->data_nascimento ) ? $item->data_nascimento : '');?>">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="sexo">Sexo</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $sexo; 
                            $config['nome'] = 'sexo'; 
                            $config['extra'] = 'id="sexo" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('sexo', (isset($item->sexo) ? $item->sexo : '') ) ); 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="id_raca">Raça</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $raca; 
                            $config['nome'] = 'id_raca'; 
                            $config['extra'] = 'id="id_raca" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_raca', (isset($item->id_raca) ? $item->id_raca : '') ) ); 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="cor">Cor</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $cores; 
                            $config['nome'] = 'id_cor'; 
                            $config['extra'] = 'id="id_cor" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_cor', (isset($item->id_cor) ? $item->id_cor : '') ) ); 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="id_pai">Pai</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $pai; 
                            $config['nome'] = 'id_pai'; 
                            $config['extra'] = 'id="id_pai" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_pai', (isset($item->id_pai) ? $item->id_pai : '') ) ); 
                            ?>
                            <a href="<?php echo base_url();?>caes/adicionar" target="_blank">Adicionar o pai</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="mae">Mãe</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $mae; 
                            $config['nome'] = 'id_mae'; 
                            $config['extra'] = 'id="id_mae" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_mae', (isset($item->id_mae) ? $item->id_mae : '') ) ); 
                            ?>
                            <a href="<?php echo base_url();?>caes/adicionar" target="_blank">Adicionar a mãe</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="id_canil_origem">Canil Origem</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $canis; 
                            $config['nome'] = 'id_canil_origem'; 
                            $config['extra'] = 'id="id_canil_origem" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_canil_origem', (isset($item->id_canil_origem) ? $item->id_canil_origem : '') ) ); 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-xs-12 control-label" for="id_canil_atual">Canil Atual</label>
                        <div class=" col-lg-8">
                            <?php
                            $config['valor'] = $canis; 
                            $config['nome'] = 'id_canil_atual'; 
                            $config['extra'] = 'id="id_canil_atual" '; 
                            $config['class'] = ''; 
                            echo form_select($config, set_value('id_canil_atual', (isset($item->id_canil_atual) ? $item->id_canil_atual : '') ) ); 
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group obrigatorio">
                                <label for="carga" class="col-lg-2 col-xs-12 control-label ">
                                    Local Atual
                                </label>
                                <div class=" col-lg-8">
                                    <input type="text" placeholder="Local Atual" class="form-control" id="Local_atual" value="<?php echo set_value('local_atual', isset($item->local_atual) && ! empty($item->local_atual) ? $item->local_atual : '');?>" name="local_atual">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group obrigatorio">
                                <label for="carga" class="col-lg-2 col-xs-12 control-label ">
                                    Local Nascimento
                                </label>
                                <div class=" col-lg-8">
                                    <input type="text" placeholder="Local Origem" class="form-control" id="local_nascimento" value="<?php echo set_value('local_nascimento', isset($item->local_nascimento) && ! empty($item->local_nascimento) ? $item->local_nascimento : '');?>" name="local_nascimento">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group obrigatorio">
                                <div class=" col-lg-8">
                                    <button type="submit" class="btn btn-success" >Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="portlet light portlet-fit bordered monta-arvore">
            <div class="portlet-title">
                <div class="caption">
                    <h3 class=""> <i class="fa fa-hourglass-start"></i> Arvore</h3>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body arvore">
            </div>
        </div>
        
    </div>
</div>
