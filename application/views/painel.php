<input type="hidden" class="empresa" value="<?php echo (isset($empresa_id) ? $empresa_id : 0); ?>">
<?php 
if ( isset($inacessivel) ) :
    ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-danger">
            <h3>Inacessivel para: <?php echo $inacessivel;?>, contate o RH.</h3>
        </div>
    </div>
</div>
    <?php
endif;
?> 
<div class="row">
    <?php if(isset($vencimento) && !empty($vencimento) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $vencimento; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if(isset($vencendo) && !empty($vencendo) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $vencendo; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php   if(isset($ocorrencias) && !empty($ocorrencias) ): ?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?php  echo $ocorrencias; ?>
            <div class="row">
                <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;  ?>
    
    
    <?php if(isset($tarefas) && !empty($tarefas) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $tarefas; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php   if(isset($campanhas) && !empty($campanhas) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $campanhas; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        
        <?php 
        $array_cores = array('red','blue','green','purple');
        if(isset($setores['estatisticas']) && count($setores['estatisticas']) > 0 ):
            $i = 0;
            $depois = '';
            foreach ($setores['estatisticas'] as $chave => $estatistica):
            ?>
        <div class="row grupos grupo-<?php echo $estatistica['setor'];?>" data-tipo="views" data-setor="<?php echo $estatistica['setor'];?>">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[$i];;?> clicks" href="#">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="qtde_views_<?php echo $estatistica['setor'];?>"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[$i];$i++;?>.gif"></span>
                        </div>
                        <div class="desc">
                            Visualizações <?php echo str_replace('_', ' ', $chave);?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[$i];?> views" href="#">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="qtde_clicks_<?php echo $estatistica['setor'];?>"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[$i];$i++;?>.gif"></span>
                        </div>
                        <div class="desc">
                            Clicks <?php echo str_replace('_', ' ', $chave);?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="portlet light bordered grafico_<?php echo $estatistica['setor'];?>">
                    <div class="portlet-title">
                        Estatística <?php echo str_replace('_', ' ', $chave) ?>
                    </div>
                    <div class="portlet-body">
                        <div id="chart_<?php echo $estatistica['setor'];?>"></div>
                    </div>
                </div>
            </div>
        </div>
            <?php
            endforeach;
            ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <small>Estatísticas baseadas nos ultimos 30 dias.</small>
                </div>
            <?php
            echo $depois;
            echo    '</div>';
        endif;
        if(isset($setores['estatisticas_portal'])):
        ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 grafico_portal <?php echo $array_cores[0];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="email_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[0];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Emails
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[1];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-list"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="lista_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[1];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Lista
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[2];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-desktop"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="ficha_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[2];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Ficha
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[3];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="favorito_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[3];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Favorito
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[0];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="ligacao_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[0];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Ligação
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 <?php echo $array_cores[1];?>" href="#">
                    <div class="visual">
                        <i class="fa fa-phone-square"></i>
                    </div>
                    <div class="details">
                        <div class="number text-right">
                            <span data-counter="counterup" class="ligacao_celular_portal"><img class="img-responsive" style="width:35px;height:35px;display:inherit" src="<?php echo base_url();?>images/carregando_<?php echo $array_cores[1];?>.gif"></span>
                        </div>
                        <div class="desc">
                            Ligação celular
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        Gráfico
                    </div>
                    <div class="portlet-body">
                        <div id="chart_portal" style="height:500px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <?php
        endif;
        ?>
    </div>
</div>
    <?php 
    if(isset($setores['contatos']) && $setores['contatos']['qtde'] > 0):
    ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    Ultimos contatos
                    <span class="caption-helper">
                        Total de contatos - <?php echo $setores['contatos']['qtde'];?>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="mt-comments">
                <?php 
                foreach ($setores['contatos']['itens'] as $chave => $contato):
                ?>
                    <div class="mt-comment contato_site pointer" data-item="<?php echo $contato->id;?>">
                        <div class="mt-comment-img">
                            <img class="img-circle" style="width:45px;height:45px" src="<?php echo base_url();?>metronic/apps/img/inbox-avatar.jpg"/>
                        </div>
                        <div class="mt-comment-body">
                            <div class="mt-comment-info">
                                <span class="mt-comment-author uppercase">
                                    <?php echo $contato->nome;?>
                                </span>
                                <span class="mt-comment-date">
                                    <?php echo $contato->data;?>
                                </span>
                            </div>
                            <div class="mt-comment-text">
                                <?php echo $contato->assunto;?>
                            </div>
                        </div>
                    </div>
                <?php
                if($chave >= 5)
                {
                    break;
                }
                endforeach;
                ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    endif;
    ?>
</div>