/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Gerenciador de mascaras utilizando o meiomask
 * 
 */
var mascara = {
    /**
     * envia set com tipo e valor
     * @param {string} tipo deve conter . ou # classe
     * @param {string} valor deve conter o valor esperado cep 00000-000
     * @returns {void}
     */
    set : function(tipo,valor){
        if ( tipo == '#fone' || tipo == '#telefone' )
        {
            $(tipo).setMask("(99) 9999-99999").ready(function(event) {
                var target, phone, element;
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unsetMask();
                if(phone.length > 10) {
                    element.setMask("(99) 99999-9999");
                } else {
                    element.setMask("(99) 9999-99999");
                }
            });
        }
        else
        {
            $(tipo).setMask(valor);
        }
        
    },
    inicia : function () {
        $(".data_hora").setMask("9999-99-99 99:99");
        $(".data_db").setMask("9999-99-99"); 
        $(".data_hora_pt_br").setMask("99/99/9999 99:99");
        $(".data").setMask("99/99/9999");
        $(".cep").setMask("99999-999");
        $(".cpf").setMask("999.999.999-99");
        $(".cnpj").setMask("99.999.999/9999-99");
        $(".telefone_0").setMask("(999) 9999-9999");
        $(".telefone_sem_ddd").setMask("9999-99999").ready(function(event) {
                var target;
                var phone;
                var element;
                if (event.currentTarget) 
                {
                    target = event.currentTarget;
                }
                else
                {
                    target = event.srcElement;
                }
                if ( target != undefined )
                {
                    phone = target.value.replace(/\D/g, '');
                    element = $(target);
                    element.unsetMask();
                    if(phone.length > 10) {
                        element.setMask("99999-9999");
                    } else {
                        element.setMask("9999-99999");
                    }
                }
            });
        $(".telefone").setMask("(99) 9999-99999").ready(function(event) {
                var target;
                var phone;
                var element;
                if (event.currentTarget) 
                {
                    target = event.currentTarget;
                }
                else
                {
                    target = event.srcElement;
                }
                if ( target != undefined )
                {
                    phone = target.value.replace(/\D/g, '');
                    element = $(target);
                    element.unsetMask();
                    if(phone.length > 10) {
                        element.setMask("(99) 99999-9999");
                    } else {
                        element.setMask("(99) 9999-99999");
                    }
                }
            });
    
    },
};
function imprimir()
{
    if( window.print() )
    {
        return true;
    }
}
$(function(){
        $('.nav-tabs a').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            $('.tab-content .active').removeClass('active').removeClass('in');
            $(href).addClass('active').addClass('in');
        
            //$(this).tab('show');
        });
        $('.telefone').on('click',function(){
            var telefone = $(this).attr('data-item');
            var primeiros = telefone.substring(0,4);
            var segundos = telefone.substring(4);
            $(this).html('<span class="glyphicon glyphicon-earphone"></span> 41 - <span class="telefone-grande"> ' + primeiros + ' - ' + segundos + '</span>');
            var url = URL_HTTP + 'set_log/95/clicks';
            $.getJSON(url,{},function(){});
        });
        $('.esp-botao').css('float','left');
        //$('#myCarousel').carousel('stop');
        var qtde_li = 0;
        $('.detalhes .nav li').each(function(){
            qtde_li++;
        });
        $('.detalhes .nav li').css('width', ( 100 / qtde_li ) + '%');
        $('.detalhes .nav li').css('height', '65px');
        $('.corpo_Interno').addClass('col-lg-3 col-md-3 col-sm-6 col-xs-6');
    });