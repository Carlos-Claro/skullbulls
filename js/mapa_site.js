$(function(){
    initialize();
});
function initialize() {
   var mapa = $('#map_canvas').attr('data-item');
   var lat_lng = mapa.split(', ');
   var map_center = {};
   map_center.lat = Number(lat_lng[0]);
   map_center.lng = Number(lat_lng[1]);
   console.log(mapa,lat_lng, map_center);
  var mapOptions = {
    zoom: 14,
    center: map_center,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  //center : new google.maps.LatLng(<?php //echo $mapa;?>)
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
   var image = 'http://www.powempresas.com/imagens/imoveis_marcador.png';
  var myLatLng = new google.maps.LatLng(mapa);
  var beachMarker = new google.maps.Marker({
      position: map_center,
      map: map,
      icon: image
  });
}