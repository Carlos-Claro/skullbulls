$(function(){
    $('.elementos').resizable({
        ghost:true,
        start: function(event, ui){
            console.log(ui);
        },
        resize: function(event, ui){
            console.log(ui);
        },
    });
    
});



