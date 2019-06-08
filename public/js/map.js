let map;

let pos = {
  lat: () => parseFloat(document.getElementById('mapsLat').value),
  lng: () => parseFloat(document.getElementById('mapsLng').value)
};

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: pos.lat(),
      lng: pos.lng()
    },
    zoom: 15, // 8 or 9 is typical zoom 
    scrollwheel:  false, // prevent mouse scroll from zooming map. 
    mapTypeControl: true, //default
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
      position: google.maps.ControlPosition.BOTTOM_CENTER
    },
    zoomControl: true, //default
    zoomControlOptions: {
      position: google.maps.ControlPosition.LEFT_CENTER
    },
    streetViewControl: true, //default
    streetViewControlOptions: {
      position: google.maps.ControlPosition.LEFT_TOP
    },
    fullscreenControl: true
  });
  let marker = new google.maps.Marker({
    position: {
      lat: pos.lat(),
      lng: pos.lng()
    },
    map: map,
    title: document.getElementById('markerName').innerText,
  });
}