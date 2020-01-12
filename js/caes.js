$(function(){
    if(typeof upload !== 'undefined')
    {
    var data = new Array();
        data.classe = '.upload';
        data.acao = URI + 'anexos/upload_temporario';
        data.input = 'upload';
        data.multiple = false;
        data.resposta_classe = '.status';
        data.resposta_type = 'json';
        data.pasta = URI + '/js/upload/';
        data.extra = '<input type="hidden" name="id_pai" value="'+$('.id').val()+'"><input type="hidden" name="pasta" value="arquivos/caes/' + +$('.id').val() + '/">';
        data.limite_kb = '1048576';
        data.type = 'jpeg|jpg|png|gif|pdf';
        upload.inicia(data);

    $(document).on('click','.deleta-imagem',function(){
        var item = $(this).attr('data-item');
        arquivo.deleta_temporario(item);
    });
 
    console.log(arquivo);
    
    }
    $('.monta-arvore .reload').on('click',function(){
        url = URI + 'caes/get_arvore/' + $('.id').val();
        $.get(url,function(res){
            $('.arvore').html(res);
            
        });
    });
    
    $('#data_nascimento').datetimepicker();
    
    $('.cep').on('change',function(){
        cep = $(this).val();
        req_logradouro = {'cepi':cep};
        endereco = 'https://republicavirtual.com.br/web_cep.php?cep=' + cep + '&formato=json';
        $.getJSON(endereco,function(data){
            if (data.resultado == 2){
                toastr.info('Cidade com logradouro unico, preenche o endereço.');
            }else if (data.resultado == 1){
                toastr.info('Endereço encontrado com sucesso!');
                $('#endereco_entrega').val(data.uf + ' - ' + data.cidade + ' - ' + data.bairro + ', ' + data.tipo_logradouro + ' ' + data.logradouro).trigger('change')
            }
        });
    });
    $('.salvar_status').on('click',function(e){
        e.preventDefault()
        data = {};
        data.observacao = $('.observacao').val();
        data.id_status = $('.status_coleta').val();
        data.id_coleta = $('.id_coleta').val();
        data.arquivo = $('.arquivo').val();
        console.log(data);
        if (data.id_status != undefined && data.id_status != ''){
          $.post(URI + 'rastreamento_coleta/adicionar_status',data,function(res){
                if(res.status){
                    toastr.info('Status adicionado com sucesso.');
                    $('.status-atual').html(res.item.status);
                    coleta_status.set_data(res.data);
                }else{
                    swal('Não foi possivel salvar seu status, tente novamente ou contate o administrador');
                }
            },'json');
        }
        else {
          swal('Selecione um status!');
        }
    });
    coleta_status.get_data();
    $('.avisar').on('click',function(){
        coleta_status.avisar();
    });
    $('#id_cliente').on('change',function(){
        data = {};
        data.id_cliente = $(this).val();
        $.post(URI + 'rastreamento_clientes/get_por_id/', data, function(res){
            console.log(res);
            if (res.status){
                $('#endereco_coleta').val(res.item.logradouro);
            }
        },'json');
    });
    $('#nota_fiscal').on('change',function(){
        data = {};
        data.nota_fiscal = $(this).val();
        url = URI + "rastreamento_coleta/get_notafiscal";
        $.getJSON(url, data, function(res){
            console.log(res);
            if (res.status){
                $('.nota_fiscal_message').text('Nota fiscal existente');
                $('.nota_fiscal_message').append('<a href="'+ URI +'rastreamento_coleta/editar/'+ res.item.id +'" target="blank" style="margin-left: 20px;">Visualizar coleta</a>');

            }
            else {
                $('.nota_fiscal_message').text('');
            }
        });
    });
});
var arquivo = {
    acao : function(data)
        {
            console.log(data);
            if(!data.erro)
            {
                $('.arquivo').val(data.arquivo);
                $('.image').val(data.arquivo);
                $('.elemento-0').remove();
                $('.deleta-image').show().removeClass('hide');
                toastr.success('Arquivo incluído com sucesso! ');
                $('.status-arquivo').append('<div><img src="'+data.caminho+'" class="img-responsive"><small style="float: left;">Arquivo incluído!</small><a target="blank" style="float: left;    font-weight: 600;" href="'+data.caminho+'">Visualizar enova aba</a><div>');
            }
            else
            {
                swal('erro',data.mensagem)
                toastr.error(data.mensagem);
            }
        },
};
var coleta_status = {
    data: [],
    get_data: function(){
        conteudo = $('.lista_status').html();
        $('.lista_status').html('<i class="fa fa-spin"></i>');
        lista = JSON.parse(conteudo);
        coleta_status.set_data(lista);
    },
    set_data: function(data_json){
        itens = data_json.itens;
        console.log(itens, itens.length);
        conteudo = '<hr><h4>Evolução da entrega</h4>';
        if ( itens.length > 0 ){
            conteudo += '<ul class="list-group">';
            $.each(itens,function(c,k){
                conteudo += '<li class="list-group-item row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">Data: ' + k.data_status + ' - status: ' + k.status + ' / Observação: ' + k.observacao + '</div>';
                conteudo += '<div style="padding: 0;" class="col-lg-3 col-md-3 col-sm-12 col-xs-12">'
                if (k.arquivo != undefined && k.arquivo != '') {
                   conteudo += '<small style="font-size: 10px;">Arquivo disponível </small>';
                   conteudo += '<a class="btn blue" style="margin-right: 7px; font-size: 10px;" target="blank" href="'+ URL_HTTP +'sites/'+$('.id_empresa').val()+'/arquivos/'+ k.arquivo +'">Visualizar</a>';
//                   conteudo += '<a target="blank" class="btn blue"style="font-size: 10px;" href="https://pow.com.br/powsites/'+$('.id_empresa').val()+'/arquivos/'+ k.arquivo +'" download="'+k.arquivo+'">Download</a>';
                }
                conteudo += '</div>';
            });
            conteudo += '</li></ul>';
        }else{
            conteudo += '<div class="alert alert-info">Aguardando coleta</div>';
        }
        coleta_status.set_conteudo(conteudo);
    },
    set_conteudo: function(conteudo){
        $('.lista_status').html(conteudo);
    },
    avisar: function(){
        id = $('.id_coleta').val();
        swal({
            title: "Confirme",
            text: "Confirma o aviso para o cliente: " + $('#cliente').val() + " do Status: " + $('.status-atual').html(),
            type: "info",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
            function(){
                var url = URI + 'rastreamento_coleta/avisar/'+id;
                $.post(url, {}, function(data){
                    if ( data.status )
                    {
                        swal('Sucesso',data.message + '.','success');
                    }
                    else
                    {
                        swal('Erro',data.message,'error');

                    }
                },'json').fail(function(){
                    swal('Erro','Não sei não, mas algo deu errado... veja na tabela correpondente.','error');
                    
                });
            }
        );
    }
}