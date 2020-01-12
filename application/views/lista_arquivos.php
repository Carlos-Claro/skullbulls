
<?php 
    if ( isset($id_empresa) && ( isset($editavel) && $editavel) ):
        ?>
        <input type="hidden" name="editavel" id="editavel" class="form-control editavel" value="<?php echo $editavel;?>">
        <?php
    endif;
?>
<div id="galeria">
    <?php 
    if ( isset($id_empresa) && ( isset($editavel) && $editavel) ):
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="help-block">Arquivos suportados: <br>- Imagem: jpeg,png <br>- Documentos: pdf <br>- Audio: mp4 </p>
                <form class="dropzone" id="dropzone_teste">
                    <input type="hidden" name="id_empresa" id="id_empresa" class="form-control id_empresa" value="<?php echo set_value('id_empresa', isset($id_empresa ) ? $id_empresa : '');?>">
                    <div class="fallback" >
                        <input type="file" name="upload" multiple="multiple"/>
                    </div>
                </form>
            </div>
        </div>
        <?php
    endif;
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>Seus arquivos salvos:</h4>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row espaco-images">
                <?php
                if ( isset($itens) && $itens && count($itens) > 0 ):
                    foreach ( $itens as $image ) :
                        $arquivo_completo = (LOCALHOST ? 'http://localhost/pow.com.br/' : 'http://www.pow.com.br/').str_replace('[id]',$image->id_empresa,$image->pasta).$image->arquivo;
                        $tipo = getimagesize($arquivo_completo);
                        $tipo_mime = get_mime_by_extension($arquivo_completo);
                        echo  '
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 images-lista image-'.$image->id_arquivo.'">
                                <div class="thumbnail">';
                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                        switch($tipo_mime) :
                            case 'application/pdf':
                                echo '<iframe src="'.$arquivo_completo.'" class="img-responsive" width="300" height="300"></iframe>';
                                break;
                            case 'image/jpeg':
                            case 'image/gif':
                            case 'image/png':
                                echo '<img src="'.$arquivo_completo.'" class="img-responsive" style="max-height:250px;">';
                                break;
                            case 'video/mp4':
                                echo ' <video width="320" height="240" controls>
                                        <source src="'.$arquivo_completo.'" type="video/mp4">
                                      Your browser does not support the video tag.
                                      </video> ';
                                break;
                        endswitch;
                        echo '</div>';
                        echo '<div class="col-md-8">';
                        echo '
                                    <div class="caption">
                                        <h3>'.$image->titulo.'</h3>
                                        
                                        <a href="'.$arquivo_completo.'" download="'.$image->titulo.'" class="btn btn-success">Download</a>
                                        <span id="copialink" class="btn btn-success   copiar_tag" data-clipboard-action="copy" data-clipboard-text="'.$arquivo_completo.'">Copiar link</span>
                                            ';
                            if ( isset($id_empresa) && $editavel ):
                                 echo '
                                                 <button class="btn btn-danger deleta-arquivo  " data-arquivo="'.$image->arquivo.'" data-elemento="'.$image->id_arquivo.'" data-item="'.$image->id_arquivo.'" role="button">Deletar</button>
                                                     ';
                            endif;
                            if ( isset($select) ):
                                $usuarios = ! empty($image->usuarios) ? explode(',',$image->usuarios) : array();
                                if ( in_array($post['id_usuario'], $usuarios)):
                                    echo '<button class="btn btn-danger desvincular-arquivo btn-vinculo-'.$image->id_arquivo.'" data-id-arquivo="'.$image->id_arquivo.'" data-usuario="'.$post['id_usuario'].'" role="button" type="button">Desvincular</button>';
                                else:
                                    echo '<button class="btn dark vincular-arquivo btn-vinculo-'.$image->id_arquivo.'" data-id-arquivo="'.$image->id_arquivo.'" data-usuario="'.$post['id_usuario'].'" role="button" type="button">Vincular</button>';
                                endif;
                            endif;
                        echo '
                            
                                        <p class="resposta help-block"></p>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>  ';
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
                                                 <!--<span id="algo" class="btn btn-success btn-sm copiar_tag" data-clipboard-action="copy" data-clipboard-text="'.$arquivo_completo.'">Liberar para usuários</span>-->