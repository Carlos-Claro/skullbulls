$(function(){
    var maxSize = 5120;
    var dropzone = new Dropzone("#dropzone_teste",{
        paramName: 'upload',
        method:'post',
        maxFilesize: maxSize/1024,
        url: URI + 'anexos/upload_image_com_resposta/'
    });
    dropzone.on('sending', function(file, xhr, formData){
                formData.append('resposta_type', 'json');
                formData.append('limite_kb', 5242880);
                formData.append('input', 'file');
                formData.append('type', 'jpeg|jpg|png|mp4|pdf');
                formData.append('id_empresa', $('.id_empresa').val());
                formData.append('tipo', '30');
                formData.append('ftp', 'pow');
                formData.append('id_pai', $('.id_empresa').val());
                formData.append('id', $('.id_empresa').val());
                formData.append('pasta', 'powsites/' + $('.id_empresa').val() + '/arquivos/');
                });
    dropzone.on('success', function(req, res){
        var resposta = JSON.parse(res);
        console.log(resposta);
        if ( !resposta.erro )
        {
            notificacao('success','Imagem enviada com sucesso, sua tela irá recarregar automaticamente.');
            location.reload(true);
        }
        else
        {
            notificacao('error',resposta.mensagem,'Atenção');
            
        }
        
//        console.log($('.images-lista').length);       
//        var id_empresa = $('.id_empresa').val();
//        var pasta = 'sites/' + id_empresa + '/images/';
//        var l = $('.images-lista').length;       
//        var la = l + 1;
//        var retorno = '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 images-lista image-' + la + '">';
//        retorno += '<div class="thumbnail">';
//        retorno += '<img src="' + URI + pasta + resposta.arquivo + '" class="">';
//        retorno += '<div class="caption"><p>' + URI + pasta + resposta.arquivo + '</p>';
//        retorno += '<a href="#" class="btn btn-danger deleta-image" data-elemento="' + la + '" data-item="' + resposta.arquivo + '" role="button">Deletar</a><p class="resposta help-block"></p><span id="algo" class="btn btn-success btn-sm btn-circle copiar_tag" data-clipboard-action="copy" data-clipboard-text="' + URI + pasta + resposta.arquivo + '">Copiar link</span></div></div></div>';
//        $('.espaco-images').append(retorno);
        
        
            
        });
        
        var texto_copiado;
        
        var clipboard = new Clipboard('.copiar_tag');
        
        clipboard.on('success',function(e){
            texto_copiado = e.text;
            toastr['success']("Tag "+texto_copiado+" copiada com sucesso", "Copiado")

            console.log(texto_copiado);
        });
        
        
        $('.deleta-arquivo').on('click',function(){
            console.log($('.id_empresa').val());
            if ( $('.id_empresa').val() )
            {
                data = {};
                data.id_empresa = $('.id_empresa').val();
                data.id = $(this).attr('data-elemento');
                data.arquivo = $(this).attr('data-arquivo');
                data.tipo = '30';
                data.ftp = 'pow';
                data.pasta = 'powsites/' + $('.id_empresa').val() + '/arquivos/';
                url =  URI + 'anexos/deletar_arquivo_id_pai/';
                $.post(url,data,function(res){
                    if ( res.status )
                    {
                        notificacao('success',res.mensagem);
                        location.reload(true);
                    }
                    else
                    {
                        notificacao('error',res.mensagem,'Atenção');

                    }
                },'json');
                
            }
            else
            {
                        notificacao('error','Você não tem autorização','Atenção');
                
            }
        });
        
        
});
function notificacao(tipo,texto,acao)
{
    if ( tipo == 'error' )
    {
        toastr.options.closeDuration = 3000;
    }
    toastr[tipo](texto, acao);
}