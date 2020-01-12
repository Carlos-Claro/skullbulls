<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h2>
            Lista relatorios
        </h2>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?php 
        if(count($relatorios) > 0 ):
            
            foreach ($relatorios as $relatorio):
                
                echo '<a href="'.base_url().'relatorios/'.$relatorio['nome'].'" download="'.$relatorio['nome'].'">'.$relatorio['nome'].' - '.$relatorio['tamanho'].' </a><br>';
            
            endforeach;
                
        endif;
        ?>
    </div>
</div>