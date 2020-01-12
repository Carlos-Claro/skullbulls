$(function(){
    $('.clique-aula').on('click',function(e){
        console.log(e);
        data = {};
        data.id_cursos_aulas = $(this).data('item');
            url = URI + 'site_sistemas/set_aulas_log';
            $.post(url,data,function(res){
            });
    });
});
