$(function(){
    
    $('.adicionar').on({
        click : function(){
           
            var url = URI+"site_paginas/editar/" + $(this).attr('data-item');
            window.location.href = url;
            
        }
    });
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"site_paginas/editar/" + $('.adicionar').attr('data-item') + "/" +$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"site_paginas/remover/";
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
    
    
    
});    
  
