 // The following example creates a marker in Stockholm, Sweden using a DROP
      // animation. Clicking on the marker will toggle the animation between a BOUNCE
      // animation and no animation.

      var marker;
      var geocoder;
      function initMap() {
          var latitude = $('#latitude').val() != '' ? Number($('#latitude').val()) : -25.653619969929277;
          var longitude = $('#longitude').val() != '' ? Number($('#longitude').val()) : -49.21223864294433;
        console.log(latitude, longitude);
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
          center: {lat: latitude, lng: longitude,}
        });
        geocoder = new google.maps.Geocoder();
        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: {lat: latitude,lng: longitude,}
        });
        marker.addListener('click', toggleBounce);
        marker.addListener('dragend', drag);
        document.getElementById('numero').addEventListener('change',function(){
            procura_cidade(geocoder, map, marker)
        });
        
        document.getElementById('cep').addEventListener('change',function(){
            procura_cidade(geocoder, map, marker)
        });
        
      }
      
      function drag(){
          console.log('dragend')
          console.log(marker.position.lat());
          $('#latitude').val(marker.position.lat()).trigger('change');
          $('#longitude').val(marker.position.lng()).trigger('change');
          toastr.success('Posição do ponteiro atualizada');
      }
      
      function toggleBounce() {
          console.log('click')
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      }


      function procura_cidade(geocoder, resultsMap, marker_){
          if ( $('#logradouro').val() != '' ){
            var options = {
                'address' : $('#logradouro').val() + ', ' + $('#numero').val() + ' ' + $('#id_cidade').find('[selected="selected"]').html(),
            };
            geocoder.geocode(options,function(results,status){
                console.log(status);
                if(status === 'OK')
                {
                    resultsMap.setCenter(results[0].geometry.location);
//                    marker_.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location)
                    resultsMap.setZoom(17);
                    $('#latitude').val(results[0].geometry.location.lat()).trigger('change');
                    $('#longitude').val(results[0].geometry.location.lng()).trigger('change');
                    toastr.success('endereço encontrado');
                }
                else
                {
                    toastr.error('Nenhum endereço encontrado');
                }
            });
        }
    }