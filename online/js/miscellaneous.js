function init_google_map() {
	var point = new google.maps.LatLng(23.6266557,-102.5375005,5);
	//var point2 = new google.maps.LatLng(20.6754749,-103.3594354,16);
	var mapOptions = {
		zoom: 4,
		center: point,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	var marker = new google.maps.Marker({
		position: point,
		map: map
	});
	$(window).unload(function() { 
		GUnload(); 
	});
}