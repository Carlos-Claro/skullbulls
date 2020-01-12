<div class="container-fluid">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <img src="
                    <?php
                    if ( isset($item->image) && ! empty($item->image) ):
                        echo base_url().'arquivos/caes/'.$item->id.'/'.$item->image;
                    else:
                        echo base_url().'arquivos/caes/sem_foto.png';
                    endif;
                    ?>
                    " class="img-responsive">
        </div>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <ul class="list-unstyled">
                <li>Nome: <?php echo $item->nome;?></li>
                <li>Nascimento: <?php echo $item->data_nascimento;?></li>
                <li>Canil: <?php echo $item->canil_atual;?></li>
                <li>Cor: <?php echo $item->cor;?></li>
                <li>Raça: <?php echo $item->raca;?></li>
                <li>Sexo: <?php echo $item->sexo_;?></li>
            </ul>
        </div>
    </div>
</div>