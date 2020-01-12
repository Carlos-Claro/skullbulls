$(function(){
    console.log('range start');
    
    $(".range").ionRangeSlider({
        type: "double",
        min: $('.range').attr('min'),
        max: $('.range').attr('max'),
        from: $('.range').attr('from'),
        to: $('.range').attr('to'),
        step: 500,
        grid: true
    });
});