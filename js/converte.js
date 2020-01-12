$(function(){
    $('#converte').on('blur',function(){
        var item = {}
        item.valor = $(this).val();
        var url = URL_HTTP + 'funcoes/calcular/converte';
        $.get(url, item, function(data){
            $('#reverte').val(data).focus();
        });
    })
    $('#reverte').on('blur',function(){
        var item = {}
        item.valor = $(this).val();
        var url = URL_HTTP + 'funcoes/calcular/reverte';
        $.get(url, item, function(data){
            $('#converte').val(data).focus();
        });
        
    })
})