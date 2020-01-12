$(function(){
    $('.andamento').on('click',function(){
        item = $(this).data('item');
        console.log(item);
        $.getJSON(URI + 'site_sistemas/rastreamento_andamento/'+item,function(res){
            itens = res.itens;
            console.log(itens, itens.length);
            conteudo = '<h4>Evolução da entrega</h4>';
            if ( itens.length > 0 ){
                conteudo += '<ul class="list-group">';
                $.each(itens,function(c,k){
                    conteudo += '<li style="margin-bottom: 15px;" class="list-group-item"><p style="margin: 5px 0;">Data: ' + k.data_status + ' - status: ' + k.status + ' / Observação: ' + k.observacao +'</p>';
                    if (k.arquivo != undefined && k.arquivo != '') {
                       conteudo += '<small style="font-size: 12px;">Arquivo disponível </small>';
                       conteudo += '<a target="blank" class="btn blue" style="margin-right: 7px; font-size: 10px;" target="blank" href="https://admin.powempresas.com/sites/'+$('.id_empresa').val()+'/arquivos/'+ k.arquivo +'">Visualizar</a>';
                    }
                    conteudo += '</li>';
                });
                conteudo += '</ul>';
            }else{
                conteudo += '<div class="alert alert-info">Aguardando coleta</div>';
            }
            $('.modal-body').html(conteudo);
            $('.modal').modal('show');
        });
    });
    
});