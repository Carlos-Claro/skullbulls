$(function(){
    if(typeof upload !== 'undefined')
    {
        if(! ($('.editavel').val() > 0))
        {
            $('input,textarea,select').prop('disabled','disabled');
        }
        
    var data = new Array();
        data.classe = '.upload';
        data.acao = URI + 'anexos/upload_temporario/';
        data.input = 'upload';
        data.multiple = true;
        data.resposta_classe = '.status';
        data.resposta_type = 'json';
        data.pasta = URI + '/js/upload/';
        data.extra = '<input type="hidden" name="pasta" value="images/usuarios/">';
        data.limite_kb = '1048576';
        data.type = 'jpeg|jpg|png|gif';
        upload.inicia(data);
    $('.deleta-image').on('click',function(){
        var item = $(this).attr('data-item');
        arquivo.deleta_temporario(item);
    });
    }
    
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"usuario/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    $('.busca_empresas').select2({
        ajax: {
            url: URI + "empresas/pesquisa_empresa",
            dataType: 'json',
            type:'post',
            delay: 250,
            data: function (params) {
                return {
                    busca : params.term, // search term
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: layout, // omitted for brevity, see the source of this page
        templateSelection: select // omitted for brevity, see the source of this page
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"usuario/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                   pop_up(data, setTimeout(function(){location.reload()}, 100));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    
    $('.senha').on({
        blur : function(){
            var valor = $(this).val();
            console.log(valor);
            if ( valor != '' )
            {
                $(this).parent('.form-group').addClass('has-success');
                
            }
            else
            {
                $(this).parent('.form-group').addClass('has-warning');
            }
        }
    });
    $('.resenha').on({
        keyup : function(){
            
        }
    });
    
    var valor_sel = new Array();
    var valor_set = new Array();
    
    $('.sel').each(function(i){
         valor_sel[i] = $(this).attr('data-item');
    });
    
    $('.setores').each(function(i){
         valor_set = $(this).attr('data-item');
         if(jQuery.inArray(valor_set, valor_sel) ==-1)
         {
             $(this).show();
         }
         else
         {
             $(this).hide();
         }
    });
    
    
    $('.setores').on({
        click: function(e){
            $('.mensagens').html('').removeClass('text-warning');
            var data = {};
            data.id_setor = $(this).attr('data-item');
            data.id_usuario = $('.id').val();
            var titulo = $(this).html();
            var existe = $('.selecionados').html();
            var montado = monta(data.id_setor,titulo);
            var url = URI + 'usuario/has_setores/';
            $('.mensagens').html('').removeClass('text-warning');
            $.post(url, data, function(resposta){
                console.log(resposta);
                if ( resposta.erro.status )
                {
                    $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                    setTimeout(function(){
                        $('.mensagens').html('').removeClass('text-danger');
                    },5000);
                }
                else
                {
                    $('.selecionados').html(existe + montado);
                    $('.setores[data-item="' + data.id_setor + '"]').remove();
                }
            },'json');
        }
    });
    
    $('.selecionados').on('click', '.close', function(e){
            $('.mensagens').html('').removeClass('text-warning');
            var data = {};
            data.id_setor = $(this).attr('data-item');
            data.id_usuario = $('.id').val();
            var url = URI + 'usuario/deleta_has_setores/';
            $('.mensagens').html('').removeClass('text-warning');
            $.post(url, data, function(resposta){
                if ( resposta.erro.status )
                {
                    $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                    setTimeout(function(){
                        $('.mensagens').html('').removeClass('text-danger');
                    },5000);
                    
                }
                else
                {
                    $('.selecionado-' + data.id_setor ).remove();
                    //$('.setores[data-item="'+item+'"]').show();
                    
                }
            },'json');
    });
    
    $(document).on('click','.checkbox',function(){
        var data = {};
        data.id_setor = $(this).attr('data-setor');
        data.id_usuario = $('.id').val();
        data.edita =  ($( this ).is( ":checked" )) ? 1 : 0;
        var url = URI + 'usuario/has_setores/';
        $.post(url, data, function(resposta){
            if ( resposta.erro.status )
            {
                $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                setTimeout(function(){
                    $('.mensagens').html('').removeClass('text-danger');
                },5000);

            }
        },'json');
        
        var item = $(this).attr('data-item');
        
    });
    
    $('.cronograma-requisita').on('click', function(){
        var id = $('.id').val();
        var url = URI + 'usuario/monta_cronograma/' + id;
        $.post(url,function(data){
            if ( data.status )
            {
                $('.espaco-cronograma').html('').html(data.cronograma);
                $('.espaco-cronograma').addClass('show').removeClass('hide');
            }
            else
            {
                alert(data.mensagem);
            }
        },'json').fail(function(e,r){
            alert('Problemas ao adquirir tarefas, tente novamente. ' + e + r);
        });
    });
    
    $('#setores_select').multiSelect({
        selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Procurar setor'>",
        selectionHeader:  "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Procurar setor'>",
        selectableOptgroup: true,
        cssClass:'test_css',
        enableFiltering: true,
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
            });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
            });
        },
        afterSelect: function(ele){
            var setor = ele[0];
            var id_usuario = $('.id').val();
            var data = {
                id_setor : setor,
                id_usuario : id_usuario
            };
            swal({
                title:'Atenção!',
                text:'Este usuário poderá editar algo neste setor ?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Não!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm)
                {
                    data.edita = 1;
                    $.post(URI+'usuario/has_setores',data,function(data){
                        if(data.erro.status)
                        {
                            toastr.error(data.erro.message);
                        }
                        else
                        {
                            $(this.$selectionUl[0]).find('[data-item="'+setor+'"]').addClass('bg-grey');
                            toastr.success(data.erro.message);
                        }
                    },'json');
                }
                else 
                {
                    data.edita = 0;
                    $.post(URI+'usuario/has_setores',data,function(data){
                            toastr.info(data.erro.message);
                    },'json');
                }
            });
            /*
             * Fazer um swal para verificar se este usuario irá editar ou não este setor.
             */
        },
        afterDeselect:function(ele)
        {
            var data = {};
            data.id_setor = ele[0];
            data.id_usuario = $('.id').val();
            var url = URI + 'usuario/deleta_has_setores/';
            $.post(url, data, function(resposta){
                toastr.info(resposta.erro.message);
            },'json');
        }
    });
    
    $(document).on('switchChange.bootstrapSwitch','.edita_permissao',function(event,state){
        var id_usuario = $('.id').val();
        var setor = $(this).attr('data-item');
        var data = {
            id_setor : setor,
            id_usuario : id_usuario,
            edita: state ? 1 : 0
        };
        $.post(URI+'usuario/has_setores',data,function(data){
            if(data.erro.status)
            {
                toastr.error(data.erro.message);
            }
            else
            {
                toastr.success('Salvo');
            }
        },'json');
    });
    $('a[href="#permissoes"]').on('click',function(){
        var id_usuario = $('.id').val();
        $.post(URI+'usuario/get_setores_selecionados',{id:id_usuario},function(data){
            if(data)
            {
                var html = '';
                $.each(data,function(k,v){
                    html += '<tr>';
                    html += '<td>';
                    html += v.descricao;
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="checkbox" class="make-switch" '+(v.edita ==1 ? 'checked="checked"' : '')+' data-item="'+v.id+'">';
                    html += '</td>';
                    html += '<tr>';
                });
                $('.lista_permissoes').html(html);
                
                $('.make-switch').bootstrapSwitch({
                    onSwitchChange:function(event,state){
                        var id_usuario = $('.id').val();
                        var setor = $(this).attr('data-item');
                        var data = {
                            id_setor : setor,
                            id_usuario : id_usuario,
                            edita: state ? 1 : 0
                        };
                        $.post(URI+'usuario/has_setores',data,function(data){
                            if(data.erro.status)
                            {
                                toastr.error(data.erro.message);
                            }
                            else
                            {
                                toastr.success('Salvo');
                            }
                        },'json');
                    }
                });
            }
        },'json');
    });
    $('a[href="#arquivos"]').on('click',function(){
        req = {};
        req.id_empresa = $('.id_empresa').val();
        req.id_usuario = $('.id').val();
        var url = URI + 'image_arquivo/get_arquivos/';
        $.post(url,req,function(res){
            console.log(res);
            $('.espaco-arquivos').html(res);
        });
        console.log('get_arquivos');
    });
    $(document).on('click','.vincular-arquivo',function(){
        req = {};
        req.acao = 'ativar';
        req.id_usuario = $(this).data('usuario');
        req.id_arquivo = $(this).data('id-arquivo');
        var url = URI + 'image_arquivo/get_vinculo/';
        $.post(url,req,function(res){
            console.log(res);
            if( res.status )
            {
                $('.btn-vinculo-' + res.id_arquivo).removeClass('dark').removeClass('vincular-arquivo').addClass('btn-danger').addClass('desvincular-arquivo').html('Desvincular');
                toastr['success']('Vinculado.', 'Legal');
            }
            else
            {
                toastr.options.closeDuration = 3000;
                toastr['error']('Não foi possivel alterar o vinculo, tente novamente.', 'Atenção');
            }
        },'json');
    });
    $(document).on('click','.desvincular-arquivo',function(){
        req = {};
        req.acao = 'desativar';
        req.id_usuario = $(this).data('usuario');
        req.id_arquivo = $(this).data('id-arquivo');
        var url = URI + 'image_arquivo/get_vinculo/';
        $.post(url,req,function(res){
            console.log(res);
            if( res.status )
            {
                $('.btn-vinculo-' + res.id_arquivo).addClass('dark').addClass(' vincular-arquivo').removeClass('btn-danger').removeClass('desvincular-arquivo').html('Vincular');
                toastr['success']('DesVinculado.', 'Legal');
            }
            else
            {
                toastr.options.closeDuration = 3000;
                toastr['error']('Não foi possivel alterar o vinculo, tente novamente.', 'Atenção');
            }
         },'json');
    });
});

function monta ( id, titulo)
{
    var retorno = '<div class="row form-group alert alert-success selecionado-' + id + '" data-item="' + id + '" id="' + id + '">';
    retorno += '<label class="pull-left col-lg-7 col-md-7 col-sm-7 col-xs-7">';
    retorno += titulo;
    retorno += '</label>';
    retorno += '<button type="button" class="close pull-right col-lg-2 col-md-2 col-sm-2 col-xs-2" data-item="' + id + '" aria-hidden="true" >&times;</button>'; 
    retorno += '<input type="checkbox" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 checkbox pull-left form-group setor-' + id + '" data-setor="' + id + '" data-usuario="' + $('.id').val() + '" value="1"  title="Pode Editar">';
    retorno += '</div>\n'; 
    
                                        
                                        
    
    
    
    return retorno;
}

var arquivo = {
        acao: function( data ){
            var id = $('.id').val();
            $.each(data,function( k, v ){
                if ( v.erro )
                {
                    $('.arquivo.elemento-' + v.chave ).append(v.mensagem);
                    $('.arquivo.elemento-' + v.chave + ' .espaco-carregando' ).remove();
                }
                else
                {
                    var conta = $('.arquivo-carregado').length;
                    var html = '';
                    $('.arquivo.elemento-' + v.chave ).addClass('col-lg-4 col-md-4 col-sm-4 col-xs-6');
                    html += '<center><img src="' + URL_IMAGES + 'images/usuarios/' + v.arquivo + '" class="img-responsive arquivo-exibe-upload"></center>';
                    html += '<input type="hidden" name="image[' + conta + '][nome]" value="' + v.arquivo + '" class="arquivo">';
                    html += '<div class="form-group">';
                    html += '<div class="deleta-image btn btn-danger" data-item="' + conta + '">Remover esta imagem</div>';
                    html += '</div>';
                    $('.foto-perfil').attr('src',URL_IMAGES + 'images/usuarios/' + v.arquivo);
                    $('.arquivo.elemento-' + v.chave ).html(html);
                    $('.arquivo.elemento-' + v.chave ).removeClass('arquivo').addClass('arquivo-carregado').removeClass('elemento-' + v.chave).addClass('elemento-' + conta).attr('data-item',conta);
                    var post_ = {'arquivo':v.arquivo};
                    var url = URI + 'usuario/adiciona_foto/'+id;
                    $.post(url,post_,function(data){
                        console.log(data);
                    },'json');
                }
            });
        },
        deleta_temporario: function(sequencia){
            var arquivo = $('.elemento-' + sequencia + ' .arquivo').val();
            var post_ = {'arquivo':arquivo,'sequencia':sequencia};
            var url = URI + 'usuario/deleta_temporario';
            $.post(url,post_,function(data){
                console.log(data);
                if ( data.erro )
                {
                    alert(data.mensagem);
                }
                else
                {
                    $('.elemento-' + data.id).remove();
                    $('.foto-perfil').attr('src',URL_IMAGES + 'images/usuarios/sem-imagem.png');
                }
            },'json');
        }
    }



function layout(data) {
    if (data.loading)
    {
        return data.text;
    }
    var html = data.descricao;
        
    return html;
}

function select(data) {
    return data.descricao || data.text;
}