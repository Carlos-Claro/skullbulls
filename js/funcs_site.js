var base = new String(document.baseURI);
var n = base.indexOf("localhost");
var n2 = base.indexOf("192.168");
var n3 = base.indexOf("testes.powempresas");
var npow = base.indexOf("pow.com");
if ( ( n ) >= 0 || ( n2 ) >= 0 || ( n3 ) >= 0 )
{
    base = base.replace('http://','');
    console.log(base);
    u = base.split('/');
    console.log(u);
    var URI = 'http://' + u[0] + '/skullbulls/';
    var URL_HTTP = 'http://' + u[0] + '/skullbulls/';
    var URL_RAIZ = 'http://' + u[0] + '/';
    var URL_IMAGES = 'http://' + u[0] + '/skullbulls/';
    console.log(URL_HTTP);
    var local = true;
}
else if( (npow) >= 0 )
{
    base = base.replace('http://','');
    u = base.split('/');
    var URI = 'http://' + u[0] + '/skullbulls/';
    var URL_HTTP = 'http://' + u[0] + '/skullbulls/';
    var URL_RAIZ = 'http://' + u[0] + '/';
    var URL_IMAGES = 'http://' + u[0] + '/';
    var local = false;
    
}
else 
{
    http = base.indexOf('https') >= 0 ? 'https://' : 'http://';
    console.log(http);
    base = base.replace(http,'');
    u = base.split('/');
    var URI = http + u[0] + '/';
    var URL_HTTP = http + u[0] + '/';
    var URL_RAIZ = http + u[0] + '/';
    var URL_IMAGES = http + u[0] + '/';
    var local = false;
    var URI_IMAGES_GUIASJP = 'http://www.guiasjp.com/';
}
$(function(){
    console.log(URI);
    
});    

function abre_janela(link, id, titulo)
{
	window.open(link, id);
}
function pop_up(mensagem, callback)
{ 
	alert(mensagem);
	if (callback && typeof(callback) === "function") {
	    // execute the callback, passing parameters as necessary
	    callback();
	}
}


    $(document).ready(function($) {
        console.log('preloader');
        var Body = $('body');
        Body.addClass('preloader-site');
    });
 $(function(){
    $(window).load(function() {
        console.log('preloader down');
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    });
     
     if ( $('.carousel .galeria-contador').length > 0 )
     {
         galeria.contador();
     }
     
     $('.carousel').on('slid.bs.carousel',function(){
         galeria.contador();
         
     });
     
     
     
 });
 
 var galeria = {
     contador: function(){
         var qtde = $('.carousel .item').length;
         $('.carousel .galeria-contador .qtde').html(qtde);
         var c = 0;
         if ( qtde > 0 )
         {
             var active = 1;
             $.each($('.carousel .item'),function(k,v){
                 c++;
                 var cl = $(v).attr('class');
                 if ( cl.indexOf('active') >= 0 )
                 {
                     var left_indicator = $(v).offset();
                     active = c;
                 }
             });
         }
         $('.carousel .galeria-contador .sequencia').html(active);
         var offset = $('.carousel .carousel-indicators li.active').offset();
         var width = $('.carousel .carousel-indicators').css('width');
         var tamanho = $('.carousel .carousel-indicators li.active').css('width');
//         console.log(parseInt(width.replace('px','')),parseInt(tamanho.replace('px','')), (parseInt(width.replace('px',''))-parseInt(tamanho.replace('px',''))) );
         width = parseInt(width.replace('px','')) - parseInt(tamanho.replace('px','') - parseInt(150));
         console.log(offset.left,width);
         if ( active > 2 )
         {
             if ( offset.left > width )
             {
                 $('.carousel .carousel-indicators').scrollLeft(parseInt(offset.left)+parseInt(150));
             }
             else
             {
                 $('.carousel .carousel-indicators').scrollLeft(0);

             }
             
         }
         else
         {
             $('.carousel .carousel-indicators').scrollLeft(0);

         }
     },
 };
 
 
 
 $(function(){
     var option_popover = {
        content : function(){
            var cont = $('.popover_ordem .popover-inner').html();
            return cont;
        },
        placement : 'bottom',
        html : true,
        trigger: 'click',
        animation : true
    };
    $('.ordem').popover(option_popover);
 });