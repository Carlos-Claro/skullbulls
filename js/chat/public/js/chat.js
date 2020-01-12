///*
// * Função que verifica quando o cliente alterou a aba ou minimizou o navegador 
// */
//window.addEventListener('blur', function(){
//    Chat.set_status(1);
//});
///*
// * Função que verifica quando o cliente alterou a aba ou minimizou o navegador 
// */
//window.addEventListener('pagehide', function(){
//    Chat.set_status(1);
//});
///*
// * Função que verifica quando o cliente entrou na aba.
// */
//window.addEventListener('focus', function(){
//    Chat.set_status(2);
//});
//
//setInterval(function(){
//    if(Chat.status === 1)
//    {
//        console.log('Ausente');
//    }
//    else if(Chat.status === 2)
//    {
//        console.log('Online');
//    }
//    else
//    {
//        console.log('Offline');
//    }
//},60000);
//
//var Chat = {
//    status : 0,
//    /*
//     * Status 
//     * [1] = Ausente
//     * [2] = Online
//     */
//    set_status: function(status){
//        Chat.status = status;
//        var url = 'chat/set_status';
//        var id_user = $('.id_usuario').val();
////        console.log(status);
//        var data = {
//            status : status,
//            id_user : id_user
//        };
//    },
//    fechar : function(item)
//    {
////        swal('vc quer mesmo fechar ?','error');
//        $('.chat_'+item).remove();
//    },
//    ocultar : function(item)
//    {
//        var chat = $('.chat_'+item);
//        
//        if(chat.hasClass('chat-open'))
//        {
//            
//            console.log('escondendo o '+item);
//            chat.removeClass('chat-open');
//        }
//        else
//        {
//            console.log('mosrtando o '+item);
//            chat.addClass('chat-open');
//        }
//        
//    }
//}
$(document).ready(function(){
    
    // Pega o id da sala
    var id = Number(window.location.pathname.match(/\/chat\/(\d+)$/)[1]);

    // Conecta com o socket
    var socket = io();
    
    //Define variaveis para cada campo chat
    
    var ChatMensagem = $('#ChatMensagem');
    var chats = $('.conteudo');
    
    var campo = $('.input-mensagem');
    
    var nome = 'Eu';
    
    socket.on('connect', function(){
        socket.emit('load', id);
    });
    
    socket.on('conectou', function(data){
        console.log(data);
        //Se for o primeiro a entrar na sala    
        if(data.quantidade === 0)
        {
            
        }
        //Se for o convidado a entrar na sala    
        else if(data.quantidade === 1) 
        {

        }
        //Se possuir 2 pessoas na sala
        else 
        {

        }

    });
    
    socket.on('typing',function(data){
        $('.typing').text('..digitando').fadeIn('slow');
    });
    
    socket.on('stop_typing',function(data){
        $('.typing').text('..digitando').fadeOut('slow');
    });
    
    campo.keypress(function(e){
        // Submit the form on enter
        if(e.which == 13) {
            e.preventDefault();
            ChatMensagem.trigger('submit');
        }
        else
        {
            socket.emit('typing',{user: nome});
        }
    });
    ChatMensagem.on('submit',function(event){
        event.preventDefault();
        if(campo.val().trim().length) {
            createChatMessage(campo.val(), nome, moment());
            scrollToBottom();
            socket.emit('stop_typing',{user : nome});
            // Send the message to the other person in the chat
            socket.emit('msg', {msg: campo.val(), user: nome});
        }
        // Empty the textarea
        campo.val("");
    });

    function scrollToBottom(){
        $("hmtl,body").animate({ scrollTop: $(document).height()-$(window).height() },1000);
    }
    function createChatMessage(msg,user,now){

        var who = '';

        if(user===nome) {
            who = 'me';
        }
        else {
            who = 'you';
        }

        var balao = $(
                '<div class="'+who+'" data-time="'+now+'">' 
                    +'<span class="mensagens">'
                    +'</span>'+
                '</div>');

        // use the 'text' method to escape malicious user input
        // O metodo 'text' é usado com uma forma de 'trim' do input
        
        balao.find('span').text(msg);
//        li.find('b').text(user);
        
        chats.append(balao);
    }
});