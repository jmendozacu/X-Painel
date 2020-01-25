function initializeMap(divid,locations) {
	console.log(divid);
	console.log(locations);
    var map = new google.maps.Map(document.getElementById(divid), {
      zoom: parseInt(locations[0][5]),
      center: new google.maps.LatLng(locations[0][1], locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) 
    {

    	animacao = locations[i][4] ? google.maps.Animation.BOUNCE : '';
    	icone =	locations[i][3] ? new google.maps.MarkerImage(
		locations[i][3],
				new google.maps.Size(100,100),
				new google.maps.Point(0,0),
				new google.maps.Point(0,35)
		) : '';


      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        title: '',
		draggable: false,
		animation: animacao,
		icon: icone,
		content: locations[i][0],
		maxWidth: 275
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));

      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));

      //  google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
      //   return function() {
      //     infowindow.setContent(locations[i][0]);
      //     infowindow.close();
      //   }
      // })(marker, i));
    }
}