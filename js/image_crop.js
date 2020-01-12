var image_crop;

function initCrop()
{
    $('.image_crop').Jcrop({
        onChange: showCoords,
        onSelect: showCoords
    },function(){
        image_crop = this;
    });
}

function setOpt(options)
{
    image_crop.setOptions(options);
}

function cortar()
{
    $('form').trigger('submit');
}

$(function(){
    initCrop();
    
    
    $('form').on('submit',function(event){
        event.preventDefault();
        var url = $(this).attr('action');
        var data = {
            x : this.x.value,
            y : this.y.value,
            h : this.h.value,
            w : this.w.value,
            src : this.src.value,
        }
        if(data.x === "" || data.y === "" || data.h === "" || data.w === "")
        {
            swal('Erro !', 'Você precisa demarcar a área para cortar.','error');
        }
        else if(data.src === "")
        {
            swal('Erro !','Ocorreu um erro, tente novamente mais tarde.','error');
        }
        else
        {
            swal({
                title: "Você deseja cortar esta imagem ?",
                text: "Ao cortar esta imagem, não será mais possível retornar a imagem original?",
                type: "error",
                showCancelButton: true,
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                closeOnConfirm: true
            },
            function(sim){
                if(sim)
                {
                    $.post(url,data,function(data){
                        image_crop.destroy();
                        $('img').attr('src',data.src);
                        initCrop();
                        window.location.reload();
                    });
                }
            });
        }
        
        
    });

});

function showCoords(c)
{
    jQuery('#x').val(c.x);
    jQuery('#y').val(c.y);
    jQuery('#w').val(c.w);
    jQuery('#h').val(c.h);
};