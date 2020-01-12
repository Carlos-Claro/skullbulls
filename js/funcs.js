/**
 * 
 */
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
    var nadmin = base.indexOf("admin.powempresas.com");
    if ( ( nadmin ) >= 0 )
    {
        base = base.replace('https://','');
        u = base.split('/');
        var URI = 'https://' + u[0] + '/';
        var URL_HTTP = 'https://' + u[0] + '/';
        var URL_RAIZ = 'https://' + u[0] + '/';
        var URL_IMAGES = 'https://' + u[0] + '/';
        var local = false;
        
    }
    else
    {
        base = base.replace('http://','');
        u = base.split('/');
        var URI = 'http://' + u[0] + '/';
        var URL_HTTP = 'http://' + u[0] + '/';
        var URL_RAIZ = 'http://' + u[0] + '/';
        var URL_IMAGES = 'http://' + u[0] + '/';
        var local = false;
        
    }
    
}
    var URI_IMAGES_GUIASJP = 'http://www.guiasjp.com/';

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
 
 
$(document).ready(function(){
    $('.carousel').carousel();
//    tarefa.verificar_trabalhando();
//    tarefa.tarefas_hoje();
//    iteracao.iteracoes_abertas();
     //CKEDITOR.replace( 'ckeditor' );
//     campanhas.campanhas_hoje();
    $('.listar_ramais').on('click',function(){
        $.post(URI+'usuario/get_ramais',{},function(data){
            console.log(data);
            var html = '<div style="height:350px;overflow-x:scroll;"><table class="table table-hover"><thead><tr><th>Foto</th><th>Nome</th><th>Ramal</th></tr></thead><tbody>';
            $.each(data.itens,function(k,v){
                html +='<tr>';
                html +='<td style="width:5%">';
                html += v.foto || '<img class="img-responsive" src="'+URI+'images/usuarios/sem-imagem.png">';
                html +='</td>';
                html +='<td style="width:80%">';
                html +=v.nome;
                html +='</td>';
                html +='<td style="width:15%">';
                html +=v.ramal;
                html +='</td>';
                html +='</tr>';
            });
            html +='</tbody></table></div>';
            swal({
                html:true,
                title: "Ramais",
                text: html,
                type: null,
                confirmButtonText: "Ok",
                closeOnConfirm: true
            });
        },'json');
    });
    $('.alterar_ramal').on('click',function(){
        var botao = this;
        var ramal = $(this).attr('data-item');
        swal({
            title: "Alterar Ramal!",
            text: "Digite o numero do seu ramal:",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            html:true,
            confirmButtonText:'Alterar Ramal',
            cancelButtonText:'Está correto',
            inputPlaceholder: "Numero do ramal",
            inputValue:ramal
          },
          function(ramal){
            if (ramal === false) return false;

            if (ramal === "") {
              swal.showInputError("Escreva um ramal!");
              return false
            }
            $.post(URI+'usuario/set_ramal',{ramal:ramal},function(data){
                if(data.status)
                {
                    swal('Sucesso',data.mensagem,'success');
                    $('.alterar_ramal').attr('data-item',ramal);
                }
                else
                {
                    swal('Ops',data.mensagem,'error');
                }
            },'json');
          });
    });
    $('.search-form').on('click',function(){
        $('.procura_empresa').autocomplete({
            source: function( request, response ) {
            $.ajax( {
                url: URI+"empresas/get_select",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response($.map(data, function (item) {
                        return {
                            label: item.descricao,
                            value: item.descricao,
                            id:item.id
                        }
                    }));
                }
            } );
        },
        minLength: 2,
        select: function( event, ui ) {
            console.log(ui.item.id);
            window.location = URI+'empresas/administrar/?b[id]='+ui.item.id;
        }
        });
        
    })
});

var campanhas = {
    campanhas_hoje: function(){
        url = URI + 'campanhas/set_qtde_hoje/';
        $.post(url,[],function(data){
            $('.qtde_campanhas_hoje').html(data);
        });
    },
}

var tempo = {
    
    hora : {},
}

var helper = {
    
    montado : '',
    
    select : function(nome, id, data, selecionado){
        this.montado = '';
        var html = '';
        html += '<div class="form-group">';
        html += '<label for="'+id+'">'+nome+'</label>';
        html += '<select name="'+id+'" id="'+id+'" class="form-control">';
        html += '<option value="">Selecione...</option>';
        $.each(data, function(k, v){
                       
            html += '<option value="'+v.id+'" '+( (v.id == selecionado) ? 'selected="selected"' : '') +'>'+v.descricao+'</option>';
                        
        });
        html += '</select>';
        html += '</div>';
        
        this.montado = html;
    },
    
    botao : function( data ){
        this.montado = '';
        var html = '';
        $.each(data, function(k,v){
            html += '<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12 empresa btn btn-default" data-item="' + v.id + '" data-titulo="' + v.descricao + '">' + v.descricao + '</div>';
        });
        
        return html;
    },
    
} 
 
var _simbolo_monetario = "R$ ";
var _separador_dezenas = ",";
var _separador_milhar = ".";



jQuery(function($){
  mascara.inicia();
        
           //$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
});
 
function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
        $('<img/>')[0].src = this;
        // Alternatively you could use:
        // (new Image()).src = this;
    });
}

/**
 * classe de imagens para salvar, deletar, fazer upload e thumbs.
 * @type type
 */
var imagens = {
    
    /**
     * Observa o elemento informado para identificar o momento de chamada e fazer upload
     * @param {object} item - objeto com elementos de upload, status, resultado, progresso e id do arquivo
     */
    upload : function (item){
      
        $(item.elemento).fileupload({
            
            dataType: 'json'
            
        }).on('fileuploadsubmit', function(e, data){
            
           $.each(data.files, function (index, file) {

                var ext = file.type.split('/');
                if (! (ext[1] && /^(jpg|png|jpeg|gif|pdf|doc|docx)$/.test(ext[1])))
                { 
                    item.status.show();
                    item.status.addClass('alert alert-danger');
                    item.status.text('Somente JPG, PNG, GIF, PDF e DOCX são permitidas');
                    return false;
                }
                else
                {
                    item.status.hide();
                }
            });
            
        }).on('fileuploaddone', function(e, data){
            
            //var url = 'http://www.guiasjp.com/';
            var url = URI;
            var r = data.result.split("-");
            var src = '';
            var caminho;
            var div_img;
            var rand = Math.floor((Math.random() * 10000) + 1);
            
            //div_img = '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 img-temp-' + r[1] + '">';
            div_img = '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 img-temp-' + rand + '">';
            //div_img += '<input type="hidden" name="id_image_arquivo" class="id_image_arquivo" value="' + r[1] + '" />';

            console.log(r);
            if(r[3].search('.pdf') !== -1){
                src += 'images/icone-pdf.png';
            }
            else if(r[3].search('.doc') !== -1){
                src += 'images/icone-doc.png';
            }
            else{
                if(r[1] == 's'){
                    src += 'images/lixo/'+r[3];
                }
                else{
                    src = (r[2].replace('[id]', item.id)) + r[3];
                }
            }
            caminho = url + src;
            caminho = caminho.replace('admin2_0/', '');
            div_img += '<img class="img-responsive" style="max-height:100px;" src="'+caminho+'">';
            //div_img += '<img class="img-responsive" style="max-height:100px;" src="'+r[3]+'">';
            div_img += '<div class="row">';
            div_img += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">';
            //div_img += '<label for="descricao-' + r[3] + '">Descrição</label>';
            //div_img += '<input type="text" class="form-control" name="descricao-' + r[3] + '" id="descricao-' + r[3] + '">';
            div_img += '<label>';
            div_img += '<input type="text" class="form-control" name="descricao_files[]" />';
            div_img += '</label>';
            div_img += '</div>';
            div_img += '</div>';
            //div_img += '<button class="btn btn-primary salvar-upload" data-id="' + r[1] + '" type="button" >Salvar</button> ';
            div_img += '<input type="hidden" name="uploaded_files[]" value="'+ r[3] +'">';
            div_img += '<button class="btn btn-danger deletar-upload" data-item="' + r[3] + '"  data-del="'+rand+'" data-path="'+r[4]+'" type="button" >Deletar</button>';
            div_img += '</div>';
            $(item.temporario).append(div_img).addClass('success');
            $(item.status).html('');
            
        }).on('fileuploadfail', function(e, data){
            
            $(item.status).html('Erro ao Inserir o arquivo tente novamente').addClass('error');
            
        }).on('fileuploadprogress', function(e, data){
            
            var progress = parseInt(data.loaded / data.total * 100, 10);
            var div_progress = '<div class="progress">';
            div_progress += '<div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="'+data.loaded+'" aria-valuemin="0" aria-valuemax="100" style="width: '+progress+'%">';
            div_progress += '<span class="sr-only">'+progress+'% Complete</span>';
            div_progress += '</div>';
            div_progress += '</div>';
            $(item.progresso).html(div_progress);
            
        });
        
    },
    
    /*
    salvar : function(item){
        
        $.post(URI+item.classe+'/tratar_upload/salvar_upload', { 'id_image_tipo': item.tipo, 'id_image_arquivo': item.arquivo, 'id_pai': item.id, 'descricao': item.descricao }, function(data){

            if(data)
            {
                console.log(data);
                //imagens.getThumb(item);
                $('.img-temp-'+item.arquivo).remove();
                $(item.progresso).html('');
            }
            else
            {
                alert('Erro ao salvar registro. Tente Novamente.');
            }

        });
            
    },*/
    
    deletar : function (item){
            
        $.post(URI+item.classe+'/tratar_upload/deletar_upload', { 'id_image_arquivo': item.arquivo, id_pai: item.id, familia : item.classe, lixo : item.lixo, path : item.path }, function(data){
            
            console.log(data);
            if ( data > 0 )
            {
                //$('#images_temp').remove('.img-temp-'+item.arquivo);
                $('#images_temp .img-temp-'+item.deletar ).remove();
                $(item.progresso).html('');
            }
            else
            {
                alert('problemas ao deletar o arquivo, tente novamente.');
            }
        });
        
    },
    
    remover : function (item){
        
        if (window.confirm('deseja apagar o vinculo com o item selecionado?'))
        {
            $.post(URI+item.classe+'/tratar_upload/remover_upload', { id_image_arquivo : item.arquivo, id_pai : item.id }, function(data){

                if(data)
                {
                    imagens.getThumb(item);
                    $(item.progresso).html('');
                    alert(data);
                }
                else
                {
                    alert('Erro ao excluir. Tente novamente');
                }

            });
        }
    },
    
    /**
     * Captura as imagens salva de determinada pasta e mostra como thumbnail.
     * @param {object} item - objeto com tipo, id, e elemento de resultado
     */
    getThumb: function (item) {
        
        var uri = URI;
        
        $.post(URI+item.classe+'/get_tipo_images/', { id_image_tipo: item.tipo , id_pai: item.id }, function(data){
            
            if(data != '')
            {
                var thumb = '';
                var data_hora = new Date();
                
                $(data).each(function(k, v)
                {
                    var caminho = '';
                    
                    caminho = uri.replace('admin2_0/', '');
                    
                    if(v.arquivo.search('.pdf') !== -1)
                    {
                        caminho += 'images/icone-pdf.png';
                    }
                    else if(v.arquivo.search('.doc') !== -1)
                    {
                         caminho += 'images/icone-doc.png';
                    }
                    else
                    {
                        caminho += (v.pasta.replace('[id]', item.id)) +v.arquivo;
                    }
                    caminho = ( (caminho.search('[ano]') !== -1) ? caminho.replace('[ano]', data_hora.getFullYear() ) : caminho );
                    caminho = ( (caminho.search('[mes]') !== -1) ? caminho.replace('[mes]', '0'+(data_hora.getMonth() + 1) ) : caminho );
                    
                    thumb += '<li class="col-lg-4">';
                    thumb += '<div class="thumbnail">';
                    thumb += '<img src="'+caminho+'" style="width:50px; height:50px;">';
                    thumb += '<h3>'+v.descricao+'</h3>';
                    thumb += '<p>Descrição: '+v.descricao_pai+'</p>';
                    thumb += '<button class="btn btn-alert remover-upload" id="'+v.id_image+'" type="button">Deletar </button>';
                    thumb += '</div>';
                    thumb += '</li>';
                });

                //$('.images').html(thumb);
                $(item.resultado).html(thumb);
            }
            else
            {
                $(item.resultado).html('');
                //$('.images').html('');
            }
            
        }, 'json');
    }
}

/**
 * classe de busca simples e avançada.
 * @type type
 */
var busca = {
    /**
     * Busca itens no controler para exibir no resultado da busca
     * @param {string} library - canal de referencia
     * @param {string} item - palavra buscada
     * @returns string com resultado se existir
     */
    get_itens : function ( library, item ) {
        
    },
    tratamento: function ( elemento, atributo ) {
        var item = elemento.val();
        if ( item.length > 0 )
        {
            var url_pesquisa = URL_HTTP + 'pesquisa/'+ canais +'/?guiasjp[busca]=';
                $('.resposta-auto-complete').show();
                $('.resposta-auto-complete').css({
                    position: 'absolute',
                    display: 'block',
                    width: tamanho + 30,
                });
                $('.resposta-auto-complete').html('<center><img src="'+URL_HTTP+'/images/loader_azul.gif" alt="carregando"></center>');
                setTimeout(function(){
                    console.log(item,$('.busca-' + canais).val(), canais);
                    if ( item === $('.busca').val() )
                    {
                        if ( item.length > 1 ) 
                        {
                            var url = url_pesquisa + encodeURIComponent( item );
                            $.get(url).done(function(data){
                                var  d = $('.resposta-auto-complete').html();
                                $('.resposta-auto-complete').html(data);
                                
                            }).fail(function(f){
                                $('.resposta-auto-complete').html('<div class="list-group"><a>Problemas na geração de resultado. Tente novamente! </a></div>');
                            }); 
                        }
                        else
                        {
                            $('.resposta-auto-complete').html('<div class="list-group"><a>Continue Digitando para prosseguir!</a></div>');
                        }
                    }
                }, 1000);
        }
        else
        {
            $('.resposta-auto-complete').css({
                position: 'relative',
                display: 'none',
            }).html('');
            $('.busca-empresa').html();
        }
        
        
        
    },
    
    set_lista: function(data)
    {
        var retorno = '';
        if(data.length > 0)
        {
            retorno += '<div class="list-group">';
            $.each(data, function(count,valor)
            {
                //console.log(count,valor);
                retorno += '<a href="#" style="z-index:999" class="list-group-item" id="list-item" data-item="'+ valor.id +'">'
                retorno += '<strong>'+ valor.descricao +'</strong>';
                retorno += '</a>'
            });
            retorno += '</div>'; 
        }
        
        else
        {
            retorno += '<div class="list-group">';
            retorno += '<p class="list-group-item">Nenhum retorno</p>';
            retorno += '</div>';
        }
        
        return retorno;
    },
};

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
        $(".numeros").setMask("99999");
//        $('.preco').setMask("999.999.999.999,99", {reverse: true});
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
/**
 * Classe criada para validar informaÃ§Ãµes pertinentes ao email
 * @since 2014-07-01
 */
var email = {
    valida: function(valor){
        var emailCheck=/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i;
        return emailCheck.test(valor);
    }
};


var verificacao = {
    /**
     * gerencia se a verficaÃ§Ã£o de campos Ã© valido
     * @since 2014-07-15
     * @type Boolean|Boolean|Boolean|Boolean|Boolean|Boolean|Boolean
     */
    valido: true,
    email_cadastro: function( email_cadastro, classe_principal )
    {
        if ( email_cadastro != '' )
        {
            email_verifica = email.valida(email_cadastro);
            if ( email_verifica )
            {
                var dados = { 'email' : email_cadastro };
                var url = URL_HTTP + 'cadastro/verifica_email_existe/json';
                    $.post(url, dados, function(e){
                    },'json').done(function(data){
                        if ( ! data.erro )
                        {
                            $(classe_principal + ' #nome').val(data.item.nome);
                            $(classe_principal + ' #telefone').val(data.item.telefone);
                        }
                    }).fail(function(e){
                    });

            }
            else
            {
                alert('preencha com um e-mail válido, por favor.');
                $( classe_principal + ' #email').focus();
            }
        }
    },
    /**
     * verifica o campo especifico com base em seu tipo e fator, sempre retornando retorno. 
     * insere mensagem de erro caso necessario
     * @since 2014-07-15
     * @param {string} campo - classe do campo
     * @param {object} tipo - {chave, mensagem} tipo de verificaÃ§Ã£o primaria e mensagem que serÃ¡ mostrada em caso de erro
     * @param {boolean} retorno - boolean 
     * @param {string} fator - define o valor extra
     * @returns {Boolean}
     */
    verifica_campo: function( campo, tipo, retorno, fator, classe_principal )
    {
        classe_principal = ( classe_principal == undefined ) ? '' : classe_principal;
        verificacao.valido = true;
        $.each( tipo, function( chave, mensagem ){
            if ( verificacao.valido )
            {
                
                switch(chave)
                {
                    case 'is_empty':
                        if ( $( classe_principal + ' #' + campo ).val() !== '' )
                        {
                            verificacao.valido = false;
                        }
                        break;
                    case 'empty':
                        if ( $( classe_principal + ' #' + campo ).val() == '' )
                        {
                            verificacao.valido = false;
                        }
                        break;
                    case 'selected':
                        var valor = $( classe_principal + ' #' + campo + ' option:selected' ).val();
                        if ( valor == '' )
                        {
                            verificacao.valido = false;
                        }
                    break;
                    case 'checked':
                        if ( ! $( classe_principal + ' #' + campo ).is( ":checked" ) )
                        {
                            verificacao.valido = false;
                        }
                    break;
                    case 'qtde_minima':
                        if ( $( classe_principal + ' #' + campo ).val().length < fator )
                        {
                            verificacao.valido = false;
                        }
                        break;
                    case 'compara':
                        var a = $( classe_principal + ' #' + campo ).val();
                        var b = $( classe_principal + ' #re-' + campo ).val();
                        if ( a !== b )
                        {
                            verificacao.valido = false;
                        }
                        break;
                    case 'valida':
                        if ( ! email.valida( $( classe_principal + ' #' + campo ).val() ) )
                        {
                            verificacao.valido = false;
                        }
                    case 'post':
                        var url = URL_HTTP + 'cadastro/verifica_email_/';
                        var dados = { 'email': $(classe_principal + ' #' + campo).val() };
                        //console.log(dados);
                        $.post(url, dados, function(data){
                            //console.log(data.qtde);
                            if ( data.qtde > 0 )
                            {
                                verificacao.valido = false;
                            }
                        },'json');
                    default:

                }
                if ( fator == 'timeout' )
                {
                    setTimeout(function(){
                        retorno = verificacao.get_campo(campo,mensagem,retorno,classe_principal);
                    },500);
                }
                else
                {
                    retorno = verificacao.get_campo(campo,mensagem,retorno,classe_principal);
                }
                //console.log(retorno);
            }
        });
        return retorno;
    },
    /**
     * Com base no verifica_campo, atribui valor ou empty ao form-group
     * @since 2014-07-15
     * @param {string} campo - classe que receberÃ¡ a mensagem
     * @param {string} mensagem - mensagem que vai atribuir o campo help
     * @param {boolean} retorno - se validou ou nÃ£o o campo
     * @returns {void|boolean}
     */
    get_campo: function( campo, mensagem, retorno, classe_principal )
    {
        if ( classe_principal == undefined )
        {
            classe_principal = '';
        }
        if ( ! verificacao.valido )
        {
            $( classe_principal + ' .form-group.' + campo ).addClass('has-warning');
            $( classe_principal + ' .help-block.' + campo ).html(mensagem);
            $( classe_principal + ' #' + campo ).focus();
            retorno =  false;
        }
        else
        {
            $(classe_principal + ' .form-group.' + campo ).removeClass('has-warning');
            $(classe_principal + ' .help-block.' + campo ).html('');
        }
        return retorno;
    },
    /**
     * Utiliza uma array para criar uma variavel composta de todos os itens e seus valores
     * @since 2014-07-01
     * @param {Array} array valores a serem buscados
     * @returns {object}
     */
    pega_campos: function( array, classe_principal ){
        classe_principal = ( classe_principal == undefined ) ? '' : classe_principal;
        var retorno = {};
        $.each(array,function( c , nome ){
            switch(nome)
            {
                case 'interesse':
                case 'usuarios_designados':
                    var z = {};
                    $( classe_principal + ' #' + nome + ':checked').each(function( a, b ){
                        z[a] = $(this).val();
                    });
                    retorno[nome] = z;
                    break;
                default:
                    retorno[nome] = $( classe_principal + ' #' + nome).val();
                    break;
            }
        });
        return retorno;
    },
};
var monta = {
    
    lista : function( item ){
        retorno = '<li class="resposta btn btn-info" data-logradouro="'+item.logradouro+'" data-id="'+item.id+'" data-bairro="'+item.bairro+'" data-cidade="'+item.cidade+'" data-cep="'+item.cepi+'" >';
        retorno += item.logradouro + ' - n: ' + item.n_inicio + ' até ' + item.n_final + ' - baiiro: ' + item.bairro + ' - cidade: ' + item.cidade + ' - CEP: ' + item.cepi;
        retorno += '</li>';
        return retorno;
    },
    
};

var contador = {
        por_classe: function(classe, contador) {
            var descricao = $(classe).val();
            if ( descricao != undefined )
            {
                var qtde = descricao.length;
                $(contador).html(qtde);
            }
        },
    };
    
    
/**
 * Função criada para gerenciar autosalvamento de campos do admin.
 * Utilizado primeiramente no setor de projetos, para salvar auto o projeto.
 * Deve seguir o formato do formulario de tarefas_projetos
 * @since 1.0 2016-02-10 - adicionado controller
 */    
var autosave = {
    retorno : false,
    notificacao : false,
    salva : function( campo, valor, sequencia, controller, campo_chave ){
        campo_chave = (campo_chave != undefined) ? campo_chave : 'id';
        autosave.retorno = false;
        toastr.clear();
        if ( valor != undefined )
        {
            var data = {};
            data.id = $('.' + campo_chave).val();
            switch( controller )
            {
                case 'tarefas_projetos':
                    data.id_tarefas_portfolio = $('.id_tarefas_portfolio').val();
                    break;
                case 'publicidade_campanhas':
                case 'imoveis_dest_listagem':
                case 'Imoveis_destaque_bairro':
                case 'formularios':
                    data.id_empresa = $('.id_empresa').val();
                    break;
                case 'hotsite_parametros': 
                case 'hotsite_menu':
                    data.id_empresa = $('#empresa').attr('data-item');
                    break;
                case 'empresas_dominio':
                    data.id_empresa = $('.id_empresa').val();
                    data.dominio_principal = $('.dominio_principal').val();
                    break;
                case 'formularios_campo':
                    data.id_formulario = $('.id').val();
                    break;
            }
            data.campo = campo;
            data.valor = valor;
            autosave.block_campos();
            var url = URI + controller + '/salvar_campo/';
            $.post(url,data,function(data){
                if ( data.status )
                {
                    if ( data.muda_url )
                    {
                        window.history.pushState('Salvo','Documento Salvo', data.muda_url)
                    }
                    sequencia++;
                    autosave.unblock_campos(true,'Salvo', campo);
                    $('.id').val(data.id);
                    if ( sequencia !== undefined && sequencia > 0 )
                    {
                        $('.campo-' + sequencia).focus();
                    }
                    autosave.retorno = true;
                }
                else
                {
                    autosave.unblock_campos(false,'Não pode ser salvo - '+campo+'. Clique e tente de novo');
                    if ( sequencia !== undefined && sequencia > 0)
                    {
                        $('.campo-' + sequencia).focus();
                    }
                    autosave.retorno = false;
                }
            },'json').fail(function(e,r){
                autosave.unblock_campos(false,'Erro ao salvar - '+campo+', tente novamente');
                
                //alert('tente novamente');
                if ( sequencia !== undefined && sequencia > 0)
                {
                    $('.campo-' + sequencia).focus();
                }
                autosave.retorno = false;
            });
        }
        if ( controller == 'tarefas_projetos' )
        {
            autosave.verifica_hide();
        }
        if( controller == 'hotsite_menu_link' || controller == 'hotsite_menu')
        {
            Menu.preview();
        }
    },
    block_campos : function(){
        $('input,select,textarea').attr('disabled','disabled');
        $('.status_servico').removeClass('glyphicon-download').addClass('glyphicon-cloud-upload');
        $('.status').html('salvando...');
    },
    unblock_campos : function(sucesso,message, campo){
        if ( sucesso )
        {
            $('.status_servico').addClass('glyphicon-download').removeClass('glyphicon-cloud-upload').removeClass('glyphicon-remove');
        }
        else
        {
            $('.status_servico').addClass('glyphicon-remove').removeClass('glyphicon-cloud-upload');
        }
        $('.status').html(message);
        if(autosave.notificacao)
        {
            var tipo = sucesso === true ? 'success' : 'error';
            var mensagem = sucesso === true ? 'Campo salvo - ' + campo : message;
            toastr[tipo](mensagem);
        }
        $('input,select,textarea').removeAttr('disabled');
    },
    verifica_hide : function(){
        if ( $('.id').val() > 0 )
        {
            var itens = $('.itens').attr('class');
            var hide = itens.indexOf('hide');
            if ( hide > 0 )
            {
                $('.itens').removeClass('hide').addClass('show');
            }
        }
    },
    matriz: function( tipo ){
        var data = {};
        data.has_usuarios = {'id_usuario' : 'required', 'papel' : 'required'};
        data.aceite = {'objetivo' : 'required', 'indicador' : 'required', 'id_responsavel' : 'required', 'data_medida' : 'required', 'meta' : 'required'};
        data.comunicacao = {'objetivo' : 'required', 'informacao' : 'required', 'id_responsavel' : 'required', 'frequencia' : 'required', 'metodo' : 'required'};
        data.marcos = {'titulo' : 'required', 'data' : 'required'};
        data.qualidade = {'indicador' : 'required', 'frequencia' : 'required', 'metodo' : 'required', 'armazenamento' : 'required', 'interpretacao' : 'required', 'id_responsavel' : 'required', 'meta' : 'required'};
        data.riscos = {'descricao_risco' : 'required', 'descricao_impacto' : 'required', 'probabilidade' : 'required', 'impacto' : 'required', 'estrategia_resposta' : 'required', 'plano_resposta' : 'required', 'plano_contingencia' : 'required'};
        data.iteracao = {'message' : 'required', 'usuarios' : 'required'};
        return data[tipo];
    }
};

jQuery(document).ready(function() {    
//   App.init(); // init metronic core componets
//   Layout.init(); // init layout
//   QuickSidebar.init() // init quick sidebar
//   Index.init();
//   Index.initDashboardDaterange();
//   Index.initJQVMAP(); // init index page's custom scripts
//   Index.initCalendar(); // init index page's custom scripts
//   Index.initCharts(); // init index page's custom scripts
//   Index.initChat();
//   Index.initMiniCharts();
//   Index.initIntro();
//   Tasks.initDashboardWidget();
});