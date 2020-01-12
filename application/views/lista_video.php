<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="mt-card-item">
        <div class="mt-card-avatar mt-overlay-1">
            <?php echo $aula->incorporado;?>
            
        </div>
        <div class="mt-card-content">
            <h3 class="mt-card-name"><?php echo $aula->titulo;?></h3>
<!--            <p class="mt-card-desc font-grey-mint"><?php // echo $aluno->email;?></p>
            <p class="mt-card-desc font-grey-mint"><?php ?></p>-->
            <div class="mt-card-social">
                <ul>
                    <?php 
//                    if (in_array($aluno->id, $ids) ):
                        ?>
                    <li>
                        <a href="javascript:;" class="deletar-aula" data-id="<?php echo $aula->id;?>" data-titulo="<?php echo $aula->titulo;?>" title="deletar aula">
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                        <?php
//                    else:
                        ?>
                    
                        <?php
//                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>