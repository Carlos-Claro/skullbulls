/**
 * 
 */

$(function(){
    $('.verificar').on({
        click : function(){
            var valor = $('#mail').val();
            console.log(valor);
            $.post(URI + 'login/esqueceu/', { 'email' : valor },  function(data){
                $('.verificado').html(data);
            });
        }
    });
    
    $('.form-group').focusout(function(){
        if($(this).find('input').val() === '')
        {
            $(this).addClass('has-error').find('input').css('border','1px solid #e73d4a');
        }
        else
        {
            $(this).removeClass('has-error').addClass('has-success').find('input').css('border','1px solid #27a4b0');
        }
        console.log($(this).find('input').attr('name'));
        if($(this).find('input').attr('name') === 'c_senha' && $(this).find('input').val() !== $('#senha').val())
        {
            $(this).addClass('has-error').find('input').css('border','1px solid #e73d4a');
        }
    });
    $('.login-form').on('submit',function(event){
        var error = 0;
        var message = '';
        $('.form-group').each(function(){
            if($(this).find('input').val() === '')
            {
                $(this).addClass('has-error').find('input').css('border','1px solid #e73d4a');
                error++;
                message +='Preencha o campo '+$(this).find('label').html()+'<br>';
            }
            else
            {
                if($(this).find('input').attr('name') === 'c_senha' && $(this).find('input').val() !== $('#senha').val())
                {
                    $(this).addClass('has-error').find('input').css('border','1px solid #e73d4a');
                    error++;
                    message+='As senhas não correspondem <br>';
                }
                else
                {
                    $(this).removeClass('has-error').addClass('has-success').find('input').css('border','1px solid #27a4b0');
                }
            }
        });
        if(error === 0)
        {
            if($('.g-recaptcha-response').val() === '')
            {
                
                $('.erro').addClass('alert alert-danger').fadeIn().html('Verificação Falhou, tente novamente');
                return false;
            }
            else
            {
                $(this).addClass('has-error').find('input').css('border','1px solid #e73d4a');
                return true;
            }
        }
        else
        {
            $('.erro').addClass('alert alert-danger').fadeIn().html(message);
        }
        return false;
    });
    
});

