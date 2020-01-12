$(function(){
    paginacao_dinamica.set();
});
var paginacao_dinamica = {
    set: function(){
        var id_elemento = $('.paginacao_dinamica').attr('data-elemento');
        if ( id_elemento !== undefined )
        {
            var qtde = $('.elemento-' + id_elemento).length;
            var paginacao = $('.paginacao_dinamica');
            var classe = paginacao.attr('class');
            var parent = paginacao.parents('div');
            var elemento_pai = $(parent).attr('data-elemento');
            //console.log(id_elemento, qtde, classe, elemento_pai);
            var device = paginacao_dinamica.get_device();
            var por_item = paginacao_dinamica.get_qtde_colunas(device,classe);
            var qtde_paginas = Math.ceil(qtde/por_item);
            var retorno = '<div id="carousel-'+id_elemento+'" class="carousel slide" data-ride="carousel">';
            retorno += paginacao_dinamica.get_indicators(qtde_paginas, id_elemento);
            retorno += '<div class="carousel-inner" role="listbox">';
            var itens = {};
            $.each($('.elemento-' + id_elemento), function(k,v){
                itens[k] = v;
            });
            var j = 0;
            for (var i = 0, max = qtde_paginas; i < max; i++) {
                retorno +=  '<div class="item ' + ( i === 0 ? 'active' : '') + '"><div class="row">';
                for ( l = 0, maxi = por_item; l < maxi; l++ )
                {
                    if ( j < qtde )
                    {
                        //console.log(itens[j]);
                        var item = itens[j];
                        retorno += '<div class="'+classe+'">'+item.innerHTML+'</div>';
                    }
                    j++;
                }
                retorno += '</div></div>';
            }
            retorno += '</div>';
            retorno += paginacao_dinamica.get_control(id_elemento);
            retorno += '</div>';
            $('.elemento-' + elemento_pai).html(retorno);
        }
        
    },
    get_indicators: function(qtde_paginas, id_elemento){
        var retorno = '<ol class="carousel-indicators">';
        for (var i = 0, max = qtde_paginas; i < max; i++) {
            retorno += '<li data-target="#carousel-'+id_elemento+'" data-slide-to="' + i + '" '+(i == 0 ? 'class="active"' : '')+'></li>';
            
        }
        retorno += '</ol>';
        return retorno;
    },
    get_control:function(id_elemento){
        var retorno = '<a class="left carousel-control" href="#carousel-'+id_elemento+'" role="button" data-slide="prev">';
        retorno += '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
        retorno += '<span class="sr-only">Previous</span>';
        retorno += '</a><a class="right carousel-control" href="#carousel-'+id_elemento+'" role="button" data-slide="next">';
        retorno += '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
        retorno += '<span class="sr-only">Next</span></a></div>';
        return retorno;
    },
    get_cabecalho: function(id_elemento){
        return retorno;
    },
    get_rodape: function(){
        return retorno;
    },
    get_device: function(){
        var width = $(window).width();
        var device = 'lg';
        if ( width < 768 )
        {
            device = 'xs';
        }
        else if ( width >= 768 && width < 992 )
        {
            device = 'sm';
        }
        else if ( width >= 992 && width < 1200 )
        {
            device = 'md';
        }
        return device;
        
    },
    get_qtde_colunas: function(device, classe){
        var col = 'col-' + device + '-';
        var regex = "("+col+").[0-9]*";
        var res = regex.toString();
        var reg = new RegExp(res,"g");
        var m = reg.exec(classe);
        var retorno = 1;
        if ( m.length > 0 )
        {
            var item = m[0];
            var a = item.split('-');
            var n = parseInt(a[2]);
            retorno = 12/n;
        }
        return retorno;
    }
};