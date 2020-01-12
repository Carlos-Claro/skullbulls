<style>[class*=" fa-"]:not(.fa-stack), [class*=" glyphicon-"], [class*=" icon-"], [class^=fa-]:not(.fa-stack), [class^=glyphicon-], [class^=icon-] {
    display: inline-block;
    line-height: 14px;
    color: #2f2f2f;
    -webkit-font-smoothing: antialiased;
}
.btn.btn-outline.default {
    border-color: #efefef;
    color: #e1e5ec;
    background: 0 0;
}
</style>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="mt-card-item">
        <!--<div class="mt-card-avatar mt-overlay-1">-->
            <!--<img src="<?php echo isset($aluno->foto_) && ! empty($aluno->foto_) ? $aluno->foto : base_url().'images/usuarios/sem_foto.jpeg';?>" />-->
            <!--<div class="mt-overlay">-->
                <!--<ul class="mt-info">-->
                    <!--<li>-->
                    <!--</li>-->
                    <!--<li>-->
                        
                    <!--</li>-->
                <!--</ul>-->
            <!--</div>-->
        <!--</div>-->
        <div class="mt-card-content">
            <h3 class="mt-card-name"><?php echo $aluno->nome;?></h3>
            <p class="mt-card-desc font-grey-mint"><?php echo $aluno->email;?></p>
            <a class="btn default btn-outline log-aluno-curso" href="javascript:;" data-id="<?php echo $aluno->id;?>">
                <i class="fa fa-bar-chart"></i>
            </a>
            <a class="btn default btn-outline" href="<?php echo base_url().'usuario/editar/'.$aluno->id;?>" target="_blank">
               <i class="icon-link"></i>
            </a>
            <p class="mt-card-desc font-grey-mint"><?php ?></p>
            <div class="mt-card-social">
                <ul>
                    <?php 
                    if (in_array($aluno->id, $ids) ):
                        ?>
                    <li>
                        <a href="javascript:;" class="remover-curso" data-id="<?php echo $aluno->id;?>" data-nome="<?php echo $aluno->nome;?>" title="remover aluno do curso">
                            <i class="fa fa-check-square-o"></i>
                        </a>
                    </li>
                        <?php
                    else:
                        ?>
                    <li>
                        <a href="javascript:;" class="inscrever-curso" data-id="<?php echo $aluno->id;?>" data-nome="<?php echo $aluno->nome;?>"  title="Adicionar aluno ao curso">
                            <i class="fa fa-square-o"></i>
                        </a>
                    </li>
                        <?php
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>