$(function(){
    $(document).on('click','.add-carrinho',function(){
        console.log('aa-carrinho');
        orcamento.add(this);
    });
    $('.carrinho').on('click',function(){
        console.log('carrinho');
        orcamento.aba();
    });
    $(document).on('click','.remove',function(){
        orcamento.remove( $(this).data('item') );
    });
    $('.c-layout-quick-sidebar').on('click','.remove',function(){
        orcamento.remove( $(this).data('item') );
    });
    $(document).on('click','.remove-todos',function(){
        orcamento.remove_todos();
    });
});
$(document).ready(function(){
    orcamento.inicia();
    
});
orcamento = {
    url: {
        check: URI + 'site_sistemas/get_itens_orcamento', 
        add: URI + 'site_sistemas/set_item_orcamento',
        remove: URI + 'site_sistemas/delete_item_orcamento',
        envio: URI + 'site_sistemas/envio_orcamento'},
    inicia: function(){
        $.post(orcamento.url.check,[],function(data){
            $('.carrinho .qtde').html(data.qtde);
        },'json');
    },
    add: function(i){
        item = $(i).data('item');
        titulo = $(i).data('titulo');
        html = $(i).html();
        html_a = html + '  <span class="fa fa-spinner fa-spin"></span>';
        $(i).html(html_a);
        $.post(orcamento.url.add,{id:item,titulo:titulo},function(data){
            if ( data.status ){
                swal('Sucesso',titulo + ' Adicionado ao seu carrinho com sucesso, as quantidades serão inseridas no carrinho.','success');
            }else{
                swal('Problemas!', 'Problemas para adicionar ' + titulo + ', tente novamente.', 'danger');
            }
            $(i).html(html);
            orcamento.inicia();
//            console.log(data);
            
        },'json');
        
    },
    remove: function(id){
        $.post(orcamento.url.remove,{id:id},function(data){
            if ( data.status ){
                orcamento.inicia();
                $('.produto-'+id).remove();
                swal('Sucesso',' Deletado do seu carrinho com sucesso.','success');
            }else{
                swal('Problemas!', 'Problemas para deletar o item, tente novamente.', 'danger');
            }
            
        },'json');
        
    },
    remove_todos: function(){
        $.post(orcamento.url.remove,[],function(data){
            if ( data.status ){
                orcamento.inicia();
                $('.produto-cot').remove();
            }
        },'json');
        
    },
    aba: function(){
        $('nav .produto-cotacao').html('<center><span class="fa-spin fa fa-spinner"></span> Aguarde um momento.</center>');
        $.post(orcamento.url.check,[],function(data){
            console.log(data);
            console.log(data.qtde);
            if ( data.qtde > 0 ){
                html = '';
                $.each(data.data, function(c,v){
                    html += '<div class="row produto-'+v.id+'">';
                    html += '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" >';
                    html += '<img alt="'+v.nome+'" class="img-responsive" src="'+v.foto_completa+'" style="border-radius: 13px!important;box-shadow: 3px 3px 18px -2px rgba(0,0,0,0.68);">';
                    html += '</div>';
                    html += '<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" >';
                    html += '<h3 class="c-font-15 c-font-bold " style="color: #f35c11; margin: 0; ">';
                    html += '<a href="'+URI+'produto/'+v.id+'">';
                    html += v.nome;
                    html += '</a>';
                    html += '</h3>';
                    html += '<button class="btn btn-danger c-btn-circle remove" data-item="'+v.id+'" type="button">Remover</button>';
//                    html += '<p class="c-font-14 c-font-black " style="margin: 0px;">Lorem ipsum dolor sit amet, consectetur adipiscing…</p>';
                    html += '</div>';
                    html += '</div>';
                });
                $('.produto-cotacao').html(html);
                
            }else{
                $('.produto-cotacao').html('<center>Nenhum item selecionado.</center>');
            }
                
        },'json');
    },
    campos: {
        qtdei: {type:'array',obrigatorio:true, mensagem:"A qtde de todos os itens é obrigatório"},
        nome: {type:'text',obrigatorio:true, mensagem:"O nome é obrigatório"},
        telefone: {type:'text',obrigatorio:true, mensagem:"O telefone é obrigatório"},
        email: {type:'text',obrigatorio:true, mensagem:"O e-mail é obrigatório"},
        cidade: {type:'text',obrigatorio:false, mensagem:"A cidade é obrigatória"},
        empresa: {type:'text',obrigatorio:false, mensagem:"A empresa é obrigatória"},
        observacao: {type:'textarea',obrigatorio:false},
//        {valida: {type:'checkbox',obrigatorio:true, mensagem:"O termo de concordância é obrigatório"}},
    },
    envia: function(){
        post = {};
        error = {};
        $.each(orcamento.campos, function(c,v){
            switch(v.type){
                case 'array':
                    array = [];
                    $.each($('.' + c), function(a,b){
                        console.log(a,$(b).data('item'),);
                        d = $(this).val();
                        console.log(d);
                        
                        if ( d == '' && orcamento.campos[c].obrigatorio ){
                            error[c] = true;
                        }else{
                            array[a] = {'item':$(this).data('item'),'qtde':$(this).val()};
                        }
                    });
                    post[c] = array;
                    break;
                case 'textarea':
                case 'text':
                    d = $('#' + c).val();
                    if ( d == '' && orcamento.campos[c].obrigatorio ){
                        error[c] = true;
                    }else{
                        post[c] = d;
                    }
                    break;
            }
        });
        console.log(error);
        erro = '';
        $.each(error, function(i,j){
            erro += ' , ' + orcamento.campos[i].mensagem;
        });
        console.log(erro);
        if ( erro === '' ){
            swal({
                title: 'Confirme',
                text: " confirma os dados para enviar o orçamento ",
                html: true,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true,
                closeOnCancel: true,
            },function(confirm){
                if(confirm){
                    $.post(orcamento.url.envio,post,function(data){
                        swal("Enviado", "Seu orçamento foi enviado com sucesso, você recebe uma copia em seu e-mail.", 'success');
                        $('input').val('');
                        orcamento.remove_todos();
                        window.location();
                    },'json');
                }else{
                        swal("Problemas", "Tivemos dificuldades no envio, favor tente novamente.", 'danger');

                }
            });
        }else{
            swal('revise os itens', erro);
            
        }
        
    },
};