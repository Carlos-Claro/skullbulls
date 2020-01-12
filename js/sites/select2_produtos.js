$(function(){
    var localizacaoSelect = $('.busca-select2')
    local = localizacaoSelect.select2({
        tags: false,
        placeholder: 'Digite o produto que busca',
        amdLanguageBase: '/metronic/global/plugins/select2/i18n/',
        language: 'pt-BR.js',
        closeOnSelect: true,
        ajax: {
            url: function(params){
                cidade  = $('.busca-select2').val();
                id_empresa = $('.identifica_pow').data('empresa');
                url = URI + 'set_cidades_select2/' + id_empresa + '/' + (params.term !== undefined ? params.term : '');
                return url;
            },
            dataType: 'json',
            delay: 100,
            processResults: function(data) {
                console.log(data);
                return {
                    results: data
                };
            } 
        }
    });
    local.on('select2:closing',function(e){});
    local.on('select2:opening',function(e){
//        data = {id:0,text:'teste'};
//        console.log(data);
//        var newOption = new Option(data.id, data.text, true, true);
//        $('.set-cidade-bairro').append(newOption).trigger('change');
        
//        localizacaoSelect.data = data;
    });
    local.on('select2:select', function(e){
        var text = e.params.data.text;
        var id = e.params.data.id;
        i = id.split('-')
        t = id.split('-')
            $('.cidade').val(i[1])
            $('.bairro').val(i[2])
            
    });
    local.on('select2:unselect', function(e){});
    
    console.log('select2');
    
});

select2_imoveis = {
    setSelectcidade: function(valor){
            i = valor.id;
            a = i.split(';');
            texto = valor.text;
            t = texto.split('*');
            ba = t[2].split(',');
            item = '';
            data = {'id':a[0],'text':t[1]};
//            item += filtro.setItem(data,'localidade cidade');
            b = a[1].split(',');
            $.each(b,function(k,v){
                if ( v !== '' )
                {
                    data = {'id':v,'text':ba[k]};
//                    item += filtro.setItem(data,'localidade bairro');
                }
            });
//            item = filtro.setItem(valor,'localidade cidade');
//        filtro.addItem(item, '.localidades', false);
//        pesquisa.getValores();
    },
    setSelectbairro: function(valor){
            text = valor.text;
            id = valor.id;
            t = text.split('*');
            c = [];
            bi = id.indexOf(',') >= 0 ? id.split(',') : [id];
            bt = t[1].indexOf(',') >= 0 ? t[1].split(',') : [t[1]];
            item = '';
            $.each(bi,function(k,v){
                if ( v !== '' )
                {
                    data = {'id':v,'text':bt[k]};
//                    item += filtro.setItem(data,'localidade bairro');
                }
            });
//        filtro.addItem(item, '.localidades', false);
//        pesquisa.getValores();
    },
}