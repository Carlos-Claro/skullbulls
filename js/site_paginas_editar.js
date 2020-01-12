
$(function(){
    var maxSize = 10000;
    var dropzone = new Dropzone("#dropzone_teste",{
        paramName: 'file',
        method:'post',
        maxFilesize: maxSize/1024,
        url: URI + 'anexos/upload_temporario/site'
    });
    dropzone.on('sending', function(file, xhr, formData){
                formData.append('resposta_type', 'json');
                formData.append('limite_kb', 10240000);
                formData.append('input', 'file');
                formData.append('type', 'jpeg|jpg|png|mp4|pdf');
                formData.append('id_empresa', $('.id_empresa').val());
                formData.append('pasta', 'sites/' + $('.id_empresa').val() + '/images/');
                });
    dropzone.on('success', function(req, res){
        var resposta = JSON.parse(res);
        console.log($('.images-lista').length);       
        var id_empresa = $('.id_empresa').val();
        var pasta = 'sites/' + id_empresa + '/images/';
        var l = $('.images-lista').length;       
        var la = l + 1;
        var retorno = '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 images-lista image-' + la + '">';
        retorno += '<div class="thumbnail">';
        retorno += '<img src="' + URI + pasta + resposta.arquivo + '" class="">';
        retorno += '<div class="caption"><p>' + URI + pasta + resposta.arquivo + '</p>';
        retorno += '<a href="#" class="btn btn-danger deleta-image" data-elemento="' + la + '" data-item="' + resposta.arquivo + '" role="button">Deletar</a><p class="resposta help-block"></p><span id="algo" class="btn btn-success btn-sm btn-circle copiar_tag" data-clipboard-action="copy" data-clipboard-text="' + URI + pasta + resposta.arquivo + '">Copiar link</span></div></div></div>';
        $('.espaco-images').append(retorno);
        
        
        console.log(res);
            
        });
        
        var texto_copiado;
        
        var clipboard = new Clipboard('.copiar_tag');
        
        clipboard.on('success',function(e){
            texto_copiado = e.text;
            toastr['success']("Tag "+texto_copiado+" copiada com sucesso", "Copiado")

            console.log(texto_copiado);
        });
        
});
function notificacao(texto,acao)
{
    toastr['success'](texto, acao);
}

function iframe_resize(elem,tamanho)
{
    setTimeout(function(){
        elem = $(elem);
        var myDoc = (elem.get(0).contentDocument) ? elem.get(0).contentDocument : elem.get(0).contentWindow.document;
    //    $('.iframe_editar_elementos').height(myDoc.body.scrollHeight);;
        var height = myDoc.body.scrollHeight;
        
        elem.animate({
           'height' :  tamanho
        });
    },600);
}
var classe_default = '.form';
var id_pagina,id_empresa;
$(function(){
    id_pagina = $('.id').val();
    id_empresa = $('.id_empresa').val();
    pagina_elementos.id_pagina = id_pagina;
    
    var editorcss = CodeMirror.fromTextArea(document.getElementById('css_campo'),{
        theme: 'base16-dark',
        lineNumbers:true,
//        mode: {name: "css", json: true},
        mode: "css"
    });
    var editorjs = CodeMirror.fromTextArea(document.getElementById('js_campo'),{
        theme: 'base16-dark',
        lineNumbers:true,
//        mode: {name: "javascript", json: true}
        mode: "javascript"
    });
    
    var editorsitecss = CodeMirror.fromTextArea(document.getElementById('site_css_campo'),{
        theme: 'base16-dark',
        lineNumbers:true,
//        mode: {name: "css", json: true},
        mode: "css"
    });
    var editorsitejs = CodeMirror.fromTextArea(document.getElementById('site_js_campo'),{
        theme: 'base16-dark',
        lineNumbers:true,
//        mode: {name: "javascript", json: true}
        mode: "javascript"
    });
    
    $('#elementos_edicao').nestable({
        maxDepth : 1000
    });
    
    $('.dd').on('change',function(){
        var ordem = ($('.dd').nestable('serialize'));
        pagina_elementos.set_ordem(ordem);
    });
    
    $(document).on('click','.editar_elemento',function(){
        var item = $(this).attr('data-item-elemento');
        pagina_elementos.edita(item);
        $('.dd').find('.dd-active').removeClass('dd-active');
        $(this).addClass('dd-active');
        console.log(url);
    });
    
    $(document).on('click','.editar_elemento_vinculo',function(){
        var item = $(this).attr('data-item');
        pagina_elementos.edita(item);
    });
    
    $('.fechar_iframe').on('click',function(){
        $(this).addClass('hidden');
        $('.iframe_editar_elementos').fadeOut('fast');
    });
    
    $('.remove').on('click',function(){
        console.log($(this));
        return false;
    });
    
    $('.atualizar_lista').on('click',function(event){
        event.preventDefault();
        pagina_elementos.get_ordem();
    });
    $(document).on('mouseover','.dd-espaco-config',function(){
        $(this).children('.mascara-on').removeClass('show').addClass('hide');
        $(this).children('.mascara-off').removeClass('hide').addClass('show');
    });
    $(document).on('mouseout','.dd-espaco-config',function(){
        $(this).children('.mascara-off').removeClass('show').addClass('hide');
        $(this).children('.mascara-on').removeClass('hide').addClass('show');
    });
    $(document).on('mouseover','.dd3-content',function(){
        var content = $(this).attr('data-content');
        $(this).children('.titulo').html(content);
    });
    $(document).on('mouseout','.dd3-content',function(){
        var content = $(this).attr('data-content-reduzido');
        $(this).children('.titulo').html(content);
        
    });
    $(document).on('click','.dd3-delete',function(){
        var item = $(this).attr('data-item');
        
        pagina_elementos.deletar(item);
    });
    
    $(document).on('click','.dd3-duplicate',function(){
        var item = $(this).attr('data-item');
        var id_pai = $($(this).closest('ol')).attr('data-item');
        pagina_elementos.duplicar(item, id_pai);
    });
    
    $(document).on('click','.dd3-pagina',function(){
        var item = $(this).attr('data-item');
        pagina_elementos.set_pagina(item);
    });
    
    $(document).on('click','.duplicar-elemento',function(){
        var item = $(this).attr('data-id');
        pagina_elementos.duplicar_seus_elementos(item);
    });
    
    $('.visualizar-desktop').on('click',function(e){
        e.preventDefault();
        var html = '<iframe src="' + URI + 'site_paginas/preview/'+id_pagina+'" style="width:1170px; height:500px; border:none; margin:0 auto;" class="pull-center"></iframe>';
        $('.modal-body').html(html);
        
        $('#modal-base').modal('show');
        $('.modal-dialog').addClass('modal-full').removeClass('modal-xs').removeClass('modal-sm');
        
    });
    $('.visualizar-tablet').on('click',function(e){
        e.preventDefault();
        var html = '<iframe src="' + URI + 'site_paginas/preview/'+id_pagina+'" style="width:780px; height:500px; border:none; margin:0 auto;" class="pull-center"></iframe>';
        $('.modal-body').html(html);
        //$('.modal-body').html('Tablet');
        $('#modal-base').modal('show');
        $('.modal-dialog').addClass('modal-sm').removeClass('modal-full').removeClass('modal-xs');
        
    });
    $('.visualizar-mobile').on('click',function(e){
        e.preventDefault();
        var html = '<iframe src="' + URI + 'site_paginas/preview/'+id_pagina+'" style="width:380px; height:500px; border:none; margin:0 auto;" class="pull-center"></iframe>';
        $('.modal-body').html(html);
        //$('.modal-body').html('Mobile');
        $('#modal-base').modal('show');
        $('.modal-dialog').addClass('modal-xs').removeClass('modal-sm').removeClass('modal-full');
    });

    $('.seus-elementos').on('click', '.adicionar-elemento', function(e){
        e.preventDefault();
        var item = $(this).attr('data-id');
        var sequencia = $('.montagem-pagina .elementos').length;
        var url = URI + 'site_paginas/add_elemento_pagina';
        var post = {};
        post.ordem = (sequencia + 1);
        post.id_pagina = id_pagina;
        post.id_elemento = item;
        $.post(url,post,function(data){
            if(data)
            {
                //TODO refresh_montagem
                pagina_elementos.get_ordem();
                $('html, body').animate({
                    scrollTop: $('#montagem_pagina').offset().top
                }, 500);
            }
        },'json');
        
        
    });


    $('.espaco-images').on('click','.deleta-image',function(e){
        e.preventDefault();
        var post = {};
        var id_empresa = $('.id_empresa').val();
        post['pasta'] = '/sites/' + id_empresa + '/images/';
        var image = $(this).attr('data-item');
        post['arquivo'] = image;
        var elemento = $(this).attr('data-elemento');
        post['sequencia'] = elemento;
        var url = URI + 'anexos/deleta_temporario';
        $.post(url, post, function(data){
            if ( data.erro )
            {
                $('.espaco-images .image-' + data.id + ' .resposta').html(data.mensagem);
            }
            else
            {
                $('.espaco-images .image-' + data.id).remove();
                
            }
        },'json');
        
        
    });

    $('.salvar_css_campo').on('click',function(e){
        e.preventDefault();
        editorcss.getValue();
        monta_arquivo.set(editorcss, 'page', 'css');
    });
    $('.salvar_js_campo').on('click',function(e){
        e.preventDefault();
        editorjs.getValue();
        monta_arquivo.set(editorjs, 'page', 'js');
    });
    $('.salvar_site_css_campo').on('click',function(e){
        e.preventDefault();
        editorsitecss.getValue();
        monta_arquivo.set(editorsitecss, 'site', 'css');
    });
    $('.salvar_site_js_campo').on('click',function(e){
        e.preventDefault();
        editorsitejs.getValue();
        monta_arquivo.set(editorsitejs, 'site', 'js');
    });
    $('.mascara_seo').on('blur',function(e){
        e.preventDefault();
        var array = mascara_seo.get();
        var dados = JSON.stringify(array);
        autosave.salva('mascara_seo', dados, $(this).attr('data-sequencia'), 'site_paginas');
    });
    $('.mascara').on({
        click:function(e){
            e.preventDefault();
            var type = $(this).attr('data-type');
            var item = $(this).attr('data-item');
            var descricao = $(this).html();
            var campo = '';
            switch(type)
            {
                case 'text':
                    var item_campo = '<textarea class="'+item+' form-control"></textarea>';
                    break;
            }
            campo += '<div class="campo-'+item+' form-group">';
            campo += '<label for="">' + descricao + '</label>';
            campo += item_campo;
            campo += '</div>';
            $('.recebe_mascara').append(campo);
        },
    });
    
//    $('[data-toggle="popover_mascara_seo"]').tooltip();
    
    //troca os espaços por + na url
    $('#link').on({
        keyup:function(data)
        {
            $(this).val($(this).val().replace(' ','+'));
        }
    });
    
     $('[data-toggle="popover"]').on('click',function(e){
         e.preventDefault();
         var titulo = $(this).attr('data-titulo') || '';
         var mensagem = $(this).attr('data-mensagem') || '';
         var tipo = $(this).attr('data-tipo') || 'info';
         /**
          * Primeiro parametro é o titulo
          * Segundo parametro é o texto
          * Terceiro é o tipo {'success','info','warning','error'}
          */
         swal(titulo,mensagem,tipo);
     });
    
    $(window).keypress(function(event) {
        var kp = String.fromCharCode(event.which).toLowerCase();
//        console.log(kp,event.ctrlKey);
        if(event.ctrlKey)
        {
            switch(kp)
            {
                case '1':
                    $('[aria-controls="page_vinculo"]').trigger('click');
                    break;
                case '2':
                    $('[aria-controls="mascara_seo"]').trigger('click');
                    break;
                case '3':
                    $('[aria-controls="page_map"]').trigger('click');
                    break;
                case '4':
                    $('[aria-controls="css_aba"]').trigger('click');
                    break;
                case '5':
                    $('[aria-controls="js_aba"]').trigger('click');
                    break;
                case '6':
                    $('[aria-controls="elementos"]').trigger('click');
                    break;
                case '7':
                    $('[aria-controls="galeria"]').trigger('click');
                    break;
                case '8':
                    $('[aria-controls="menu"]').trigger('click');
                    break;
                case '9':
                    $('[aria-controls="formulario"]').trigger('click');
                    break;
                case '0':
                    $('[aria-controls="vinculos"]').trigger('click');
                    break;
            }
            
        }
    });
    //contador.por_classe('#empresa_descricao', '.contador_descricao');
    $('.contavel').on({
        keyup: function(){
            var classe = $(this).attr('name');
            contador.por_classe('#' + classe, '.contador_' + classe);
        }
    });
    var array_vinculos = vinculos.get();
    mascaras_vinculo.get(array_vinculos);
    
    
    var item_deleta = {
        vinculo:undefined,
        vinculo_form:undefined,
        item:undefined,
        type:undefined,
    };
    $('.edita-vinculo-form').on('blur',function(event){
        
        event.preventDefault();
        item_deleta.vinculo = $(this).attr('data-vinculo');
        item_deleta.vinculo_form = $(this).val();
        item_deleta.item = $(this).attr('data-item');
        item_deleta.type = $(this).attr('data-type');
        swal({
            title: "Legal!",
            text: "Você deseja vincular este form a pagina ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
            function(){
                var url = URI + 'site_paginas/edita_vinculo_form';
                var post_ = {};
                post_.id_pagina = id_pagina;
                post_.vinculo = item_deleta.vinculo;
                post_.vinculo_form = item_deleta.vinculo_form;
                post_.item = item_deleta.item;
                post_.type = item_deleta.type;
                post_.acao = 'editar';
                console.log(post_);
                $.post(url, post_, function(data){
                    if ( data.status )
                    {
                        swal('Sucesso','Vinculo Alterado com sucesso.','success');
                        vinculos.refresh_page_vinculo();
                    }
                    else
                    {
                        swal('Erro',data.message,'error');

                    }
                },'json');
            }
        );
    });
    $('.edita-vinculo-mapa').on('blur',function(event){
        
        event.preventDefault();
        item_deleta.vinculo = $(this).attr('data-vinculo');
        item_deleta.vinculo_mapa = $(this).val();
        item_deleta.item = $(this).attr('data-item');
        item_deleta.type = $(this).attr('data-type');
        swal({
            title: "Legal!",
            text: "Você deseja vincular este mapa a pagina ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
            function(){
                var url = URI + 'site_paginas/edita_vinculo_mapa';
                var post_ = {};
                post_.id_pagina = id_pagina;
                post_.vinculo = item_deleta.vinculo;
                post_.vinculo_mapa = item_deleta.vinculo_mapa;
                post_.item = item_deleta.item;
                post_.type = item_deleta.type;
                post_.acao = 'editar';
                console.log(post_);
                $.post(url, post_, function(data){
                    if ( data.status )
                    {
                        swal('Sucesso','Vinculo Alterado com sucesso.','success');
                        vinculos.refresh_page_vinculo();
                    }
                    else
                    {
                        swal('Erro',data.message,'error');

                    }
                },'json');
            }
        );
    });
    $('.edita-vinculo-loop').on('blur',function(event){
        
        event.preventDefault();
        item_deleta.vinculo = $(this).attr('data-vinculo');
        item_deleta.vinculo_loop = $(this).val();
        item_deleta.item = $(this).attr('data-item');
        item_deleta.type = $(this).attr('data-type');
        swal({
            title: "Legal!",
            text: "Você deseja vincular este loop a pagina ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
            function(){
                var url = URI + 'site_paginas/edita_vinculo_loop';
                var post_ = {};
                post_.id_pagina = id_pagina;
                post_.vinculo = item_deleta.vinculo;
                post_.vinculo_loop = item_deleta.vinculo_loop;
                post_.item = item_deleta.item;
                post_.type = item_deleta.type;
                post_.acao = 'editar';
                console.log(post_);
                $.post(url, post_, function(data){
                    if ( data.status )
                    {
                        swal('Sucesso','Vinculo Alterado com sucesso.','success');
                        vinculos.refresh_page_vinculo();
                    }
                    else
                    {
                        swal('Erro',data.message,'error');

                    }
                },'json');
            }
        );
    });
    $('.deleta').on('click',function(event){
        event.preventDefault();
        item_deleta.vinculo = $(this).attr('data-vinculo');
        item_deleta.vinculo_form = $(this).attr('data-vinculo-form');
        item_deleta.item = $(this).attr('data-item');
        item_deleta.type = $(this).attr('data-type');
        swal({
            title: "Cuidado",
            text: "Você deseja desvincular este item desta pagina ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
            function(){
                var url = URI + 'site_paginas/edita_vinculo';
                var post_ = {};
                post_.id_pagina = id_pagina;
                post_.vinculo = item_deleta.vinculo;
                post_.vinculo_form = item_deleta.vinculo_form;
                post_.item = item_deleta.item;
                post_.type = item_deleta.type;
                post_.acao = 'deletar';
                console.log(post_);
                $.post(url, post_, function(data){
                    if ( data.status )
                    {
                        swal('Sucesso','Vinculo removido com sucesso.','success');
                        vinculos.refresh_page_vinculo();
                    }
                    else
                    {
                        swal('Erro',data.message,'error');

                    }
                },'json');
            }
        );
    });
//    $('.edita').on('click',function(){
//            var item = $(this).attr('data-item'),
//            vinculo = $(this).attr('data-vinculo'),
//            tipo = $(this).attr('data-type'),
//            url = URI+'site_paginas/editar_vinculo/'+item+'/'+tipo+'/'+vinculo+'/'+id_pagina;
//            $('.modal-body').load(url);
//            $('#modal-base').modal('show');
//            $('.modal-dialog').addClass('modal-full').removeClass('modal-xs').removeClass('modal-sm');
//    });
    $('.vinculo').on({
        click: function(e){
            e.preventDefault();
            var item = $(this);
            vinculos.get_steps(item);
        }
    });
    $(document).on('click','.proximo_passo',function(){
        vinculos.set_steps('next');

    });
    $(document).on('click','.passo_anterior',function(){
        vinculos.set_steps('previous');
//        var estado = $('.estado').val();
//        if(estado > 0)
//        {
//            $($('.mt-step-col')[estado]).removeClass('active');
//            estado--;
//        }
//        $('.steps').removeClass('active');
//        $('.step-' + ( estado - 1 )).addClass('active');
//        vinculos.refresh_steps();
//        $($('.mt-step-col')[estado]).removeClass('done')
//        $('.estado').val(estado);
    });
    $('#modal-base').on('click', '.mt-widget-3', function(){
        var data = $(this);
        vinculos.set_type(data);
    });
    $('#modal-base').on('click','.set-elemento',function(){
        vinculos.set_elementos();
    });
    $('#modal-base').on('click','.add-elemento-lista',function(){
        var data = $(this);
        vinculos.monta_elementos(data);
    });
    $('#modal-base').on('click','.passo-2',function(){
        vinculos.set_steps(1);
    });
});
    
var vinculos = {
    id:0,
    get: function(data){
        var array = [];
        $.each($('.vinculos-set'),function(k,v){
            var id = $(v).attr('data-id');
            var item = {};
            item.id = $(v).attr('data-item');
            item.type = $(v).attr('data-type');
            item.vinculo = $(v).attr('data-vinculo');
            array[id] = item;
            vinculos.id = id;
        });
        if ( data !== undefined )
        {
            var i = vinculos.id;
            if ( i !== 0 )
            {
                i++;
            }
            array[i] = data;
        }
        return array;
    },
    save: function(data){
        var array = vinculos.get(data);
        var dados = JSON.stringify(array);
        $('.espaco-mascara').attr('data-item', dados);
        autosave.salva('page_vinculo', dados, 0, 'site_paginas');
        setTimeout(function(){
            vinculos.refresh_page_vinculo(true);
            mascaras_vinculo.get(array);
        },2000);
    },
    set_steps: function(estado){
        //var estado = $('.estado').val();
        var qtde_total = $('.mt-step-col').length;
        $('.steps').removeClass('active');
        var anterior = estado;
        var proximo = estado;
        anterior--;
        proximo++;
        $($('.mt-step-col')[anterior]).addClass('done');
        $($('.mt-step-col')[proximo]).removeClass('active');
        $($('.mt-step-col')[estado]).addClass('active');
        var estado_atual = ( estado + 1 );
        $('.step-' + estado_atual).addClass('active');
        vinculos.refresh_steps();
        $('.estado').val(estado);
    },
    get_steps: function(data){
        $('.modal-body').html('');
        var item = data.attr('data-item');
        url = URI+'site_paginas/editar_vinculo/' + item;
        $('.modal-body').load(url, function(){
            vinculos.refresh_steps();
        });
        $('#modal-base').modal('show');
        $('.modal-dialog').addClass('modal-full').removeClass('modal-xs').removeClass('modal-sm');
        vinculos.set_type();
    },
    refresh_steps: function(){
        $('#modal-base .steps').addClass('hide');
        $('#modal-base .steps.active').removeClass('hide');
    },
    set_type: function(data){
        $('#modal-base .mt-head').addClass('bg-green-jungle').removeClass('bg-blue-soft');
        if ( data !== undefined )
        {
            var head = data.children(".mt-head");
            head.removeClass('bg-green-jungle').addClass('bg-blue-soft');
        }
        if ( data !== undefined )
        {
            var array = {};
            array.type = data.attr('data-item');
            array.id = $('#modal-base .item').val();
            if ( array.type === 'item' )
            {
                array.vinculo = false;
                vinculos.save(array);
            }
            else
            {
                $('.page-vinculo').attr('data-novo',JSON.stringify(array));
                vinculos.set_steps(1);
                    $('.espaco-qtdes').addClass('show').removeClass('hide');
            }
        }
    },
    set_elementos: function(){
        var por_linha = $('#modal-base #por_linha').val();
        if ( por_linha === '' )
        {
            $('#modal-base #por_linha').focus().attr('placeholder','Preencha o campo de qtde por linha');
            $('#modal-base .por_linha').addClass('has-error');
        }
        else
        {
            $('#modal-base .por_linha').removeClass('has-error');
            var url = URI + 'site_elementos/set_elementos_filtrado';
            var array = {'id_empresa':$('.id_empresa').val()};
            $('.espaco-elementos-vinculo').load(url);
            vinculos.set_steps(2);
            
        }
    },
    refresh_page_vinculo: function(ok){
        var url = URI + 'site_paginas/set_page_vinculo/';
        console.log({'id':$('.id').val()});
        $.get(url,{'id':$('.id').val()},function(data){
            $('.page-vinculo').html(data);
            if ( ok !== undefined )
            {
                $('#modal-base').modal('hide');
                swal('Perfeito !','Mais um vinculo esta configurado, utilize a lista para gerencia-lo','success');
                $('.page_vinculo').attr('data-novo','');
                $('#modal-base .modal-body').html('');
            }
        });
    },
    monta_elementos: function(data){
        var id = data.attr('data-id');
        var data = {};
        $.each($('#modal-base .step-2 input'),function(k,v){
            data[$(v).attr('name')] = $(v).val();
        });
        data['id'] = $('#modal-base .item').val();
        data['id_pagina'] = $('.id').val();
        data['id_empresa'] = $('.id_empresa').val();
        data['item'] = id;
        var url = URI + 'site_elementos/adicionar_lista';
        $.post(url,data,function(data){
            if(data.status)
            {
                console.log(data);
                $('#modal-base').modal('hide');
                swal('Perfeito !','Mais um vinculo esta configurado, utilize a lista para gerencia-lo','success');
                pagina_elementos.get_ordem();
                vinculos.refresh_page_vinculo();
            }
            else
            {
                alert(data.message);
            }
        }, 'json');
            
        
    },
    
};
    
var mascara_seo = {
    get: function(){
        var array = {};
        $.each($('.mascara_seo'),function(k,v){
            array[$(v).attr('name')] = $(v).val();
        });
        return array;
    },
};
    
var mascaras_vinculo = {
    get: function(array){
        var dados = JSON.stringify(array);
        var url = URI + 'site_paginas/set_vinculos';
        var get = {'id':$('.id').val()};
        $.get(url, get, function(data){
            $('.espaco-mascara').html(data);
        });
        
        
        
    },
    set: function(conteudo) {
        var conteudo = '';
        console.log('Set', conteudo);
    }
}
var pagina_elementos = {
    nao_pode_filhos : 0,
    id_pagina : 0 ,
    edita: function(id){
        var iframe = $('.iframe_editar_elementos');
        iframe.fadeOut('fast');
        $('.fechar_iframe').addClass('hidden');
        
        var url = URI + 'site_elementos/editar/'+id+'/'+id_empresa ;
        iframe.attr('src',url);
        iframe.on('load',function(){
            iframe_resize(iframe);
            iframe.fadeIn('slow').removeClass('hidden');
            $('.fechar_iframe').removeClass('hidden');
        })
    },
    verifica : function(ordem,elemento_pai)
    {
        if( ordem.length > 0 )
        {
            $.each(ordem,function(key,value){
                var item = value.id;
                var elemento = $('[data-item-elemento="'+item+'"]');
//                console.log(item);
//                console.log(elemento);
                if(elemento_pai !== undefined)
                {
                    /**
                     * Tag do tipo de elemento ( div, p, a )
                     */
                    var tag = elemento.attr('data-tipo-item');
                    /**
                     * Tag do tipo que o pai aceita (Ex: div, p, a )
                     */
                    var tag_filhos = elemento_pai.attr('data-filho');
                    console.log(tag,tag_filhos);
                    /**
                     * Verifica se a tag do pai 
                     * é tudo, não recebe nada
                     */
                    if(tag_filhos === undefined)
                    {
                        console.log(elemento_pai);
                    }
                    if(tag_filhos !== '*' && tag_filhos !== 'false')
                    {
                        /**
                         * Separa os filhos em um array para 
                         * verificar com o indexOf
                         */
                        
                        tag_filhos = tag_filhos.split('|');
                        /**
                         * Atenção para a negação da tag
                         */
                        if( ! (tag_filhos.indexOf(tag) > -1))
                        {
                            /**
                             * Se ele nao estã na array ele não pode receber aquele tipo de filho
                             */
                            pagina_elementos.nao_pode_filhos++;
                            swal('Erro','O elemento "'+elemento_pai.attr('data-tipo-item')+'" só pode ter o(s) seguinte(s) elemento(s) : '+tag_filhos.join(', '),'error');
                            return false;
                        }
                    }
                    /**
                     * Se ele é um elemento que 
                     * não pode receber filhos ( uma imagem por exemplo )
                     * Ele já seta que não pdoe ter filhos
                     */
                    else if(tag_filhos === 'false')
                    {
                        pagina_elementos.nao_pode_filhos++;
                        swal('Erro','O elemento "'+elemento_pai.attr('data-tipo-item')+'" não aceita elementos','error');
                        return false;
                    }
                }
                if(value.children !== undefined)
                {
                    pagina_elementos.verifica(value.children,elemento);
                }
            });
            if(pagina_elementos.nao_pode_filhos > 0 )
            {
                pagina_elementos.get_ordem(pagina_elementos.id_pagina);
            }
        }
    },
    set_ordem : function(ordem)
    {
        pagina_elementos.nao_pode_filhos = 0;
        pagina_elementos.verifica(ordem);
        if(pagina_elementos.nao_pode_filhos === 0 )
        {
            var url = URI + 'site_paginas/set_ordem';
            var itens = {
                data : ordem,
                id_pagina : pagina_elementos.id_pagina
            };
            $.post(url,itens,function(data){});
        }
    },
    get_ordem: function()
    {
        var carregando = $(this).find('.fa-refresh');
        carregando.css({
            animation: '4s rotate360 infinite linear'
        },1000);
        var url = URI + 'site_paginas/get_elementos/'+id_pagina;
        $.get(url,function(html){
//            console.log(html);
            $('#elementos_edicao').html(html);
        });
    },
    deletar : function(item)
    {
        if(item !== "")
        {
            var mensagem = 'Você deseja desvicular este elemento desta página ? ';
            var filhos = $('[data-id="'+item+'"]').find('ol');
            
            if(filhos.length > 0)
            {
                mensagem += '( todos os elementos ligados à ele serão desvinculados ) ';
            }
            var ordem = ($('.dd').nestable('serialize'));
            
            swal({
              title: "Cuidado",
              text: mensagem,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "Sim",
              cancelButtonText: "Não!",
              closeOnConfirm: false,
              closeOnCancel: true,
              showLoaderOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    mensagem = 'foi desvinculado';
                    if(filhos.length > 0)
                    {
                        mensagem = 'e todos os elementos ligados à ele foram desvinculados';
                    }
                    swal({
              title: "Desvinculado!",
              text: "O elemento "+mensagem+" com sucesso.",
              type: "success",
              showCancelButton: false,
              confirmButtonClass: "btn-primary",
              confirmButtonText: "Ok",
              closeOnConfirm: true,
              showLoaderOnConfirm: false
            });
                    $('[data-id="'+item+'"]').remove();
                    var ordem = ($('.dd').nestable('serialize'));
                    pagina_elementos.set_ordem(ordem);
                } else {
                    swal("Cancelado", "Seu elemento não foi desvinculado :)", "error");
                }
            });

        }
    },
    duplicar: function(item, id_pai){
        if(item !== "")
        {
            var mensagem = 'Você esta prestes a duplicar o elemento ' + item + ' e ligada ao elemento pai ' + id_pai + '';
            swal({
              title: "Veja bem!",
              text: mensagem,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Continuar",
              cancelButtonText: "Abortar!",
              closeOnConfirm: false,
              closeOnCancel: true,
              showLoaderOnConfirm: true
            },function(isConfirm) {
                if (isConfirm) {
                    var url = URI + 'site_elementos/duplicar/';
                    var post = {};
                    post.id_elemento = item;
                    post.id_pagina = id_pagina;
                    post.id_empresa = id_empresa;
                    post.id_pai = id_pai;
                    $.post(url, post,function(data){
                        if ( data.status )
                        {
                            pagina_elementos.get_ordem();
                            swal("Ok", "Seu elemento foi duplicado e adicionado a lista", "success");
                        }
                        else
                        {
                            pagina_elementos.get_ordem();
                            swal("Algo aconteceu", "Seu elemento não foi duplicado, erro: " + data.mensagem + ")", "error");
                        }
                    },'json').fail(function(){
                        pagina_elementos.get_ordem();
                        swal("Algo aconteceu!", "Seu elemento não foi duplicado e não retornou nenhum erro...", "error");
                        
                    });
                } 
            });
            
        }
                
    },
    duplicar_seus_elementos: function(item){
        if(item !== "")
        {
            var mensagem = 'Você esta prestes a duplicar o elemento ' + item ;
            swal({
              title: "Veja bem!",
              text: mensagem,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Continuar",
              cancelButtonText: "Abortar!",
              closeOnConfirm: false,
              closeOnCancel: true,
              showLoaderOnConfirm: true
            },function(isConfirm) {
                if (isConfirm) {
                    var url = URI + 'site_elementos/duplicar/';
                    var post = {};
                    post.id_elemento = item;
                    post.id_empresa = id_empresa;
                    $.post(url, post,function(data){
                        if ( data.status )
                        {
                            pagina_elementos.reload_seus_elementos();
                            swal("Ok", "Seu elemento foi duplicado", "success");
                        }
                        else
                        {
                            pagina_elementos.reload_seus_elementos();
                            swal("Algo aconteceu", "Seu elemento não foi duplicado, erro: " + data.mensagem + ")", "error");
                        }
                    },'json').fail(function(){
                        pagina_elementos.reload_seus_elementos();
                        swal("Algo aconteceu!", "Seu elemento não foi duplicado e não retornou nenhum erro...", "error");
                        
                    });
                } 
            });
            
        }
                
    },
    set_pagina: function(item){
        if(item !== "")
        {
            var mensagem = 'Gostaria mesmo de ligar o item ' + item + ' a esta empresa? ';
            swal({
              title: "Veja bem!",
              text: mensagem,
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Continuar",
              cancelButtonText: "Abortar!",
              closeOnConfirm: false,
              closeOnCancel: true,
              showLoaderOnConfirm: true
            },function(isConfirm) {
                if (isConfirm) {
                    var url = URI + 'site_elementos/set_elemento_pagina/';
                    var post = {};
                    post.id_elemento = item;
                    post.id_empresa = id_empresa;
                    $.post(url, post,function(data){
                        if ( data.status )
                        {
                            pagina_elementos.reload_seus_elementos();
                            swal("Ok", "Seu elemento foi adicionado a seus elementos", "success");
                        }
                        else
                        {
                            pagina_elementos.get_ordem();
                            swal("Algo aconteceu", "Seu elemento não foi adicionado..., erro: " + data.mensagem + ")", "error");
                        }
                    },'json').fail(function(){
                        pagina_elementos.get_ordem();
                        swal("Algo aconteceu!", "Seu elemento não foi inserido e não retornou nenhum erro...", "error");
                        
                    });
                } 
            });
            
        }
    },
    reload_seus_elementos: function(){
        var url = URI + 'site_paginas/get_seus_elementos/' + id_empresa;
        var carregando = $(this).find('.seus-elementos .reload');
        carregando.css({
            animation: '4s rotate360 infinite linear'
        },2000);
        $('.seus-elementos .itens').html('');
        $.post(url,function(data){$('.seus-elementos .itens').html(data);});
    },
}
