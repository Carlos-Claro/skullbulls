<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-comments"></i>Upload de arquivos.</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
        </div>
    </div>
    <div class="portlet-body">
        
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="help-block">Arquivos suportados: <br>- Imagem: txt,RET  </p>
                <form class="dropzone" id="dropzone_teste">
                    <div class="fallback" >
                        <input type="file" name="upload" multiple="multiple"/>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-comments"></i>Lista de arquivos <?php echo isset($titulo) ? $titulo : '';?> </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-scrollable">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <?php 
                        foreach( $header as $c ) :
                            echo '<th>'.$c['title'].'</th>';
                        endforeach;
                        if ( isset($buttons) ):
                            echo '<th>Ações</th>';
                        endif;
                        ?>
<!--                        <th> # </th>
                        <th> First Name </th>
                        <th> Last Name </th>
                        <th> Username </th>
                        <th> Status </th>-->
                    </tr>
                </thead>
                <tbody>
                        <?php
                        foreach ( $files as $f ):
                            ?>
                    <tr>
                            <?php
                            foreach( $header as $c ) :
                                $i = '<td>';
                                $da = $f[$c['id']];
                                if ( isset($c['action']) && $c['action'] )
                                {
                                    $acao = $c['action'];
                                    if ( $acao == 'set_link' )
                                    {
                                        $u = $url.$da;
                                        $da = '<a href="'.$u.'" download>'.$da.'</a>';
                                    }
                                    else
                                    {
//                                        var_dump($acao);
                                        $da = $acao($da);
                                    }
                                }
                                $i .= $da;
                                $i .= '</td>';
                                echo $i;
                            endforeach;
                            if ( isset($buttons) ) :
                                echo '<td>';
                                foreach($buttons as $button):
                                    echo str_replace('[name]',$f['name'],$button);
                                endforeach;
                                echo '</td>';
                            endif;
                            ?>
                    </tr>
                            <?php
                        endforeach;
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>